<?php

////////////////////////////////////////////////////////////////////////////////
//                                                                            //
//   Copyright (C) 2006  Phorum Development Team                              //
//   http://www.phorum.org                                                    //
//                                                                            //
//   This program is free software. You can redistribute it and/or modify     //
//   it under the terms of either the current Phorum License (viewable at     //
//   phorum.org) or the Phorum License that was distributed with this file    //
//                                                                            //
//   This program is distributed in the hope that it will be useful,          //
//   but WITHOUT ANY WARRANTY, without even the implied warranty of           //
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                     //
//                                                                            //
//   You should have received a copy of the Phorum License                    //
//   along with this program.                                                 //
////////////////////////////////////////////////////////////////////////////////

// cvs-info: $Id: mysqli.php 2456 2007-09-12 22:32:38Z brian $

if (!defined("PHORUM")) return;

/**
 * The other Phorum code does not care how the messages are stored.
 *    The only requirement is that they are returned from these functions
 *    in the right way.  This means each database can use as many or as
 *    few tables as it likes.  It can store the fields anyway it wants.
 *    The only thing to worry about is the table_prefix for the tables.
 *    all tables for a Phorum install should be prefixed with the
 *    table_prefix that will be entered in include/db/config.php.  This
 *    will allow multiple Phorum installations to use the same database.
 */

/**
 * These are the table names used for this database system.
 */

// tables needed to be "partitioned"
$PHORUM["message_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_messages";
$PHORUM["user_newflags_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_user_newflags";
$PHORUM["subscribers_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_subscribers";
$PHORUM["files_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_files";
$PHORUM["search_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_search";

// tables common to all "partitions"
$PHORUM["settings_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_settings";
$PHORUM["forums_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_forums";
$PHORUM["user_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_users";
$PHORUM["user_permissions_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_user_permissions";
$PHORUM["groups_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_groups";
$PHORUM["forum_group_xref_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_forum_group_xref";
$PHORUM["user_group_xref_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_user_group_xref";
$PHORUM['user_custom_fields_table'] = "{$PHORUM['DBCONFIG']['table_prefix']}_user_custom_fields";
$PHORUM["banlist_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_banlists";
$PHORUM["pm_messages_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_pm_messages";
$PHORUM["pm_folders_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_pm_folders";
$PHORUM["pm_xref_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_pm_xref";
$PHORUM["pm_buddies_table"] = "{$PHORUM['DBCONFIG']['table_prefix']}_pm_buddies";
/*
* fields which are always strings, even if they contain only numbers
* used in post-message and update-message, otherwise strange things happen
*/
$PHORUM['string_fields']= array('author', 'subject', 'body', 'email');

/* A piece of SQL code that can be used for identifying moved messages. */
define('PHORUM_SQL_MOVEDMESSAGES', "({$PHORUM['message_table']}.parent_id = 0 and {$PHORUM['message_table']}.thread != {$PHORUM['message_table']}.message_id)");

/**
 * This function executes a query to select the visible messages from
 * the database for a given page offset. The main Phorum code handles
 * actually sorting the threads into a threaded list if needed.
 *
 * By default, the message body is not included in the fetch queries.
 * If the body is needed in the thread list, $PHORUM['TMP']['bodies_in_list']
 * must be set to "1" (for example using setting.tpl).
 *
 * NOTE: ALL dates should be returned as Unix timestamps
 *
 * @param offset - the index of the page to return, starting with 0
 * @param messages - an array containing forum messages
 */

function phorum_db_get_thread_list($offset)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($offset, "int");

    $conn = phorum_db_mysqli_connect();

    $table = $PHORUM["message_table"];

    // The messagefields that we want to fetch from the database.
    $messagefields =
       "$table.author,
        $table.datestamp,
        $table.email,
        $table.message_id,
        $table.meta,
        $table.moderator_post,
        $table.modifystamp,
        $table.parent_id,
        $table.msgid,
        $table.sort,
        $table.status,
        $table.subject,
        $table.thread,
        $table.thread_count,
        $table.user_id,
        $table.viewcount,
        $table.closed";
    if(isset($PHORUM['TMP']['bodies_in_list']) && $PHORUM['TMP']['bodies_in_list'] == 1) {
        $messagefields .= "\n,$table.body";
    }

    // The sort mechanism to use.
    if($PHORUM["float_to_top"]){
            $sortfield = "modifystamp";
            $index = "list_page_float";
    } else{
            $sortfield = "thread";
            $index = "list_page_flat";
    }

    // Initialize the return array.
    $messages = array();

    // The groups of messages we want to fetch from the database.
    $groups = array();
    if ($offset == 0) $groups[] = "specials";
    $groups[] = "threads";
    if ($PHORUM["threaded_list"]) $groups[] = "replies";

    // for remembering message ids for which we want to fetch the replies.
    $replymsgids = array();

    // Process all groups.
    foreach ($groups as $group) {


        $sql = NULL;

        switch ($group) {

            // Announcements and stickies.
            case "specials":

                $sql = "select $messagefields
                       from $table
                       where
                         status=".PHORUM_STATUS_APPROVED." and
                         ((parent_id=0 and sort=".PHORUM_SORT_ANNOUNCEMENT."
                           and forum_id={$PHORUM['vroot']})
                         or
                         (parent_id=0 and sort=".PHORUM_SORT_STICKY."
                          and forum_id={$PHORUM['forum_id']}))
                       order by
                         sort, $sortfield desc";
                break;

            // Threads.
            case "threads":

                if ($PHORUM["threaded_list"]) {
                    $limit = $PHORUM['list_length_threaded'];
                    $extrasql = '';
                } else {
                    $limit = $PHORUM['list_length_flat'];
                }
                $start = $offset * $limit;

                $sql = "select $messagefields
                        from $table use index ($index)
                        where
                          $sortfield > 0 and
                          forum_id = {$PHORUM["forum_id"]} and
                          status = ".PHORUM_STATUS_APPROVED." and
                          parent_id = 0 and
                          sort > 1
                        order by
                          $sortfield desc
                        limit $start, $limit";
                break;

            // Reply messages.
            case "replies":

                // We're done if we did not collect any messages with replies.
                if (! count($replymsgids)) break;

                $sortorder = "sort, $sortfield desc, message_id";
                if(isset($PHORUM["reverse_threading"]) && $PHORUM["reverse_threading"])
                    $sortorder.=" desc";

                $sql = "select $messagefields
                        from $table
                        where
                          status = ".PHORUM_STATUS_APPROVED." and
                          thread in (" . implode(",",$replymsgids) .")
                        order by $sortorder";
                break;

        } // End of switch ($group)

        // Continue with the next group if no SQL query was formulated.
        if (is_null($sql)) continue;

        // Fetch the messages for the current group.
        $res = mysqli_query($conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
        $rows = mysqli_num_rows($res);
        if($rows > 0){
            while ($rec = mysqli_fetch_assoc($res)){
                $messages[$rec["message_id"]] = $rec;
                $messages[$rec["message_id"]]["meta"] = array();
                if(!empty($rec["meta"])){
                    $messages[$rec["message_id"]]["meta"] = unserialize($rec["meta"]);
                }

                // We need the message ids for fetching reply messages.
                if ($group == 'threads' && $rec["thread_count"] > 1) {
                    $replymsgids[] = $rec["message_id"];
                }
            }
        }
    }

    return $messages;
}

/**
 * This function executes a query to get the recent messages for
 * all forums the user can read, a particular forum, or a particular
 * thread, and and returns an array of the messages order by message_id.
 *
 * In reality, this function is not used in the Phorum core as of the time
 * of its creationg.  However, several modules have been written that created
 * a function like this.  Therefore, it has been added to aid in module development
 *
 * The bulk of this function came from Jim Winstead of mysql.com
 */
function phorum_db_get_recent_messages($count, $forum_id = 0, $thread = 0, $threads_only = 0)
{
    $PHORUM = $GLOBALS["PHORUM"];
    settype($count, "int");
    settype($forum_id, "int");
    settype($thread, "int");
    settype($threads_only, "bool");
    phorum_db_sanitize_mixed($forum_id, "int");

    $arr = array();

    $conn = phorum_db_mysqli_connect();

    // we need to differentiate on which key to use
    // last_post_time is for sort by modifystamp
    // forum_max_message is for sort by message-id
    if($threads_only) {
        $use_key='last_post_time';
    } else {
        $use_key='post_count';
    }

    $sql = "SELECT {$PHORUM['message_table']}.* FROM {$PHORUM['message_table']} USE KEY($use_key) WHERE status=".PHORUM_STATUS_APPROVED;

    // have to check what forums they can read first.
    // even if $thread is passed, we have to make sure
    // the user can read the forum
    if($forum_id <= 0) {
        $allowed_forums=phorum_user_access_list(PHORUM_USER_ALLOW_READ);

        // if they are not allowed to see any forums, return the emtpy $arr;
        if(empty($allowed_forums))
            return $arr;
    } elseif(is_array($forum_id)) {
        // for an array, check each one and return if none are allowed
        foreach($forum_id as $id){
            $id = (int)$id;
            if(phorum_user_access_allowed(PHORUM_USER_ALLOW_READ,$id)) {
                $allowed_forums[]=$id;
            }
        }

        // if they are not allowed to see any forums, return the emtpy $arr;
        if(empty($allowed_forums))
            return $arr;
    } else {
        // only single forum, *much* fast this way
        if(!phorum_user_access_allowed(PHORUM_USER_ALLOW_READ,$forum_id)) {
            return $arr;
        }
    }

    if($forum_id > 0){
        $sql.=" and forum_id=$forum_id";
    } else {
        $sql.=" and forum_id in (".implode(",", $allowed_forums).")";
    }

    if($thread){
        $sql.=" and thread=$thread";
    }

    if($threads_only) {
        $sql.= " and parent_id = 0";
        $sql.= " ORDER BY thread DESC";
    } else {
        $sql.= " ORDER BY message_id DESC";
    }

    if($count){
        $sql.= " LIMIT $count";
    }

    $res = mysqli_query($conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    while ($rec = mysqli_fetch_assoc($res)){
        $arr[$rec["message_id"]] = $rec;

        // convert meta field
        if(empty($rec["meta"])){
            $arr[$rec["message_id"]]["meta"]=array();
        } else {
            $arr[$rec["message_id"]]["meta"]=unserialize($rec["meta"]);
        }
        if(empty($arr['users'])) $arr['users']=array();
        if($rec["user_id"]){
            $arr['users'][]=$rec["user_id"];
        }

    }

    return $arr;
}


/**
 * This function executes a query to select messages from the database
 * and returns an array.  The main Phorum code handles actually sorting
 * the threads into a threaded list if needed.
 *
 * NOTE: ALL dates should be returned as Unix timestamps
 * @param forum - the forum id to work with. Omit or NULL for all forums.
 *                You can also pass an array of forum_id's.
 * @param waiting_only - only take into account messages which have to
 *                be approved directly after posting. Do not include
 *                messages which are hidden by a moderator.
 */

function phorum_db_get_unapproved_list($forum = NULL, $waiting_only=false,$moddays=0,$countonly = false)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($waiting_only, "bool");
    settype($moddays, "int");
    settype($countonly, "bool");
    phorum_db_sanitize_mixed($forum, "int");

    $conn = phorum_db_mysqli_connect();

    $table = $PHORUM["message_table"];

    $arr = array();
    $sum = 0;

    // do we want only a count here?
    if($countonly) {
        $selecting = "count(*) as msgcnt";

    // or the full messages?
    } else {
        $selecting = "$table.*";

    }

    $sql = "select
            $selecting
          from
            $table ";

    if (is_array($forum)){
        $sql .= "where forum_id in (" . implode(",", $forum) . ") and ";
    } elseif (! is_null($forum)){
        settype($forum, "int");
        $sql .= "where forum_id = $forum and ";
    } else {
        $sql .= "where ";
    }

    if($moddays > 0) {
        $checktime=time()-(86400*$moddays);
        $sql .=" datestamp > $checktime AND";
    }

    if($waiting_only){
        $sql.=" status=".PHORUM_STATUS_HOLD;
    } else {
        $sql="($sql status=".PHORUM_STATUS_HOLD.") " .
             "union ($sql status=".PHORUM_STATUS_HIDDEN.")";
    }

    if(!$countonly) {
        $sql .=" order by thread, message_id";
    }

    $res = mysqli_query($conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    while ($rec = mysqli_fetch_assoc($res)){
        if($countonly) {
            $sum += $rec['msgcnt'];
        } else {
            $arr[$rec["message_id"]] = $rec;
            $arr[$rec["message_id"]]["meta"] = array();
            if(!empty($rec["meta"])){
                $arr[$rec["message_id"]]["meta"] = unserialize($rec["meta"]);
            }
        }
    }

    if($countonly) {
        return $sum;
    } else {
        return $arr;
    }
}


/**
 * This function posts a message to the tables.
 * The message is passed by reference and message_id and thread are filled
 */

function phorum_db_post_message(&$message,$convert=false){
    $PHORUM = $GLOBALS["PHORUM"];
    $table = $PHORUM["message_table"];

    settype($convert, "bool");

    $conn = phorum_db_mysqli_connect();

    $success = false;

    foreach($message as $key => $value){
        if (is_numeric($value) && !in_array($key,$PHORUM['string_fields'])){
            $message[$key] = (int)$value;
        } elseif(is_array($value)) {
            $message[$key] = mysqli_real_escape_string ($conn, serialize($value));
        } else{
            $message[$key] = mysqli_real_escape_string ($conn, $value);
        }
    }

    if(!$convert) {
        $NOW = time();
    } else {
        $NOW = $message['datestamp'];
    }

    // duplicate-check
    if(isset($PHORUM['check_duplicate']) && $PHORUM['check_duplicate'] && !$convert) {
        // we check for dupes in that number of minutes
        $check_minutes=60;
        $check_timestamp =$NOW - ($check_minutes*60);
        // check_query
        $chk_query="SELECT message_id FROM $table WHERE forum_id = {$message['forum_id']} AND author='{$message['author']}' AND subject='{$message['subject']}' AND body='{$message['body']}' AND datestamp > $check_timestamp";
        $res = mysqli_query($conn, $chk_query);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $chk_query");
        if(mysqli_num_rows($res))
            return 0;
    }

    if(isset($message['meta'])){
        $metaval=",meta='{$message['meta']}'";
    } else {
        $metaval="";
    }

    $sql = "Insert into $table set
            forum_id = {$message['forum_id']},
            datestamp=$NOW,
            thread={$message['thread']},
            parent_id={$message['parent_id']},
            author='{$message['author']}',
            subject='{$message['subject']}',
            email='{$message['email']}',
            ip='{$message['ip']}',
            user_id={$message['user_id']},
            moderator_post={$message['moderator_post']},
            status={$message['status']},
            sort={$message['sort']},
            msgid='{$message['msgid']}',
            body='{$message['body']}',
            closed={$message['closed']}
            $metaval";

    // if in conversion we need the message-id too
    if($convert && isset($message['message_id'])) {
        $sql.=",message_id=".$message['message_id'];
    }

    if(isset($message['modifystamp'])) {
        $sql.=",modifystamp=".$message['modifystamp'];
    }

    if(isset($message['viewcount'])) {
        $sql.=",viewcount=".$message['viewcount'];
    }


    $res = mysqli_query($conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if ($res){
        $message["message_id"] = mysqli_insert_id($conn);

        if(!empty($message["message_id"])){

            $message["datestamp"]=$NOW;

            if ($message["thread"] == 0){
                $message["thread"] = $message["message_id"];
                $sql = "update $table set thread={$message['message_id']} where message_id={$message['message_id']}";
                $res = mysqli_query($conn, $sql);
                if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
            }

            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            // start ft-search stuff
            $search_text="$message[author] | $message[subject] | $message[body]";
            $sql="insert delayed into {$PHORUM['search_table']} set message_id={$message['message_id']}, forum_id={$message['forum_id']}, search_text='$search_text'";
            $res = mysqli_query($conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            // end ft-search stuff

            $success = true;
            // some data for later use, i.e. email-notification
            $GLOBALS['PHORUM']['post_returns']['message_id']=$message["message_id"];
            $GLOBALS['PHORUM']['post_returns']['thread_id']=$message["thread"];
        }
    }

    return $success;
}

/**
 * This function deletes messages from the messages table.
 *
 * @param message $ _id the id of the message which should be deleted
 * mode the mode of deletion, PHORUM_DELETE_MESSAGE for reconnecting the children, PHORUM_DELETE_TREE for deleting the children
 */

function phorum_db_delete_message($message_id, $mode = PHORUM_DELETE_MESSAGE)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($message_id, "int");
    settype($mode, "int");

    $threadset = 0;
    // get the parents of the message to delete.
    $sql = "select forum_id, message_id, thread, parent_id from {$PHORUM['message_table']} where message_id = $message_id ";
    $res = mysqli_query( $conn, $sql);
    $rec = mysqli_fetch_assoc($res);
    if (empty($rec)) {
        phorum_db_mysqli_error("No message found for message_id $message_id");
    }

    if($mode == PHORUM_DELETE_TREE){
        $mids = phorum_db_get_messagetree($message_id, $rec['forum_id']);
    }else{
        $mids = $message_id;
    }

    // unapprove the messages first so replies will not get posted
    $sql = "update {$PHORUM['message_table']} set status=".PHORUM_STATUS_HOLD." where message_id in ($mids)";
    $res = mysqli_query($conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $thread = $rec['thread'];
    if($thread == $message_id && $mode == PHORUM_DELETE_TREE){
        $threadset = 1;
    }else{
        $threadset = 0;
    }

    if($mode == PHORUM_DELETE_MESSAGE){
        $count = 1;
        // change the children to point to their parent's parent
        // forum_id is in here for speed by using a key only
        $sql = "update {$PHORUM['message_table']} set parent_id=$rec[parent_id] where forum_id=$rec[forum_id] and parent_id=$rec[message_id]";
        mysqli_query( $conn, $sql);
    }else{
        $count = count(explode(",", $mids));
    }

    // delete the messages
    $sql = "delete from {$PHORUM['message_table']} where message_id in ($mids)";
    mysqli_query( $conn, $sql);

    // start ft-search stuff
    $sql="delete from {$PHORUM['search_table']} where message_id in ($mids)";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    // end ft-search stuff

    // it kind of sucks to have this here, but it is the best way
    // to ensure that it gets done if stuff is deleted.
    // leave this include here, it needs to be conditional
    include_once("include/thread_info.php");
    phorum_update_thread_info($thread);

    // we need to delete the subscriptions for that thread too
    $sql = "DELETE FROM {$PHORUM['subscribers_table']} WHERE forum_id > 0 AND thread=$thread";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // this function will be slow with a lot of messages.
    phorum_db_update_forum_stats(true);

    return explode(",", $mids);
}

/**
 * gets all attached messages to a message
 *
 * @param id $ id of the message
 */
function phorum_db_get_messagetree($parent_id, $forum_id){
    $PHORUM = $GLOBALS["PHORUM"];

    settype($parent_id, "int");
    settype($forum_id, "int");

    $conn = phorum_db_mysqli_connect();

    $sql = "Select message_id from {$PHORUM['message_table']} where forum_id=$forum_id and parent_id=$parent_id";

    $res = mysqli_query( $conn, $sql);

    $tree = "$parent_id";

    while($rec = mysqli_fetch_row($res)){
        $tree .= "," . phorum_db_get_messagetree($rec[0],$forum_id);
    }

    return $tree;
}

/**
 * This function updates the message given in the $message array for
 * the row with the given message id.  It returns non 0 on success.
 */

function phorum_db_update_message($message_id, $message)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($message_id, "int");

    if (count($message) > 0){
        $conn = phorum_db_mysqli_connect();

        foreach($message as $field => $value){
            if(phorum_db_validate_field($field)){
                if (is_numeric($value) && !in_array($field,$PHORUM['string_fields'])){
                    $fields[] = "$field=$value";
                }elseif (is_array($value)){
                    $value = mysqli_real_escape_string ($conn, serialize($value));
                    $fields[] = "$field='$value'";
                    $message[$field] = $value;
                }else{
                    $value = mysqli_real_escape_string ($conn, $value);
                    $fields[] = "$field='$value'";
                    $message[$field] = $value;
                }
            }
        }

        $sql = "update {$PHORUM['message_table']} set " . implode(", ", $fields) . " where message_id=$message_id";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        if($res){
            // start ft-search stuff
            if(isset($message["author"]) && isset($message["subject"]) && isset($message["body"])){
                $search_text="$message[author] | $message[subject] | $message[body]";
                $sql="replace delayed into {$PHORUM['search_table']} set message_id={$message_id}, forum_id={$message['forum_id']}, search_text='$search_text'";
                $res = mysqli_query( $conn, $sql);
                if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
            }
            // end ft-search stuff
        }

        return ($res > 0) ? true : false;

    }else{
        trigger_error("\$message cannot be empty in phorum_update_message()", E_USER_ERROR);
    }
}


/**
 * This function executes a query to get the row with the given value
 * in the given field and returns the message in an array.
 */

function phorum_db_get_message($value, $field="message_id", $ignore_forum_id=false)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if(!phorum_db_validate_field($field)){
        return false;
    }

    $multiple=false;

    phorum_db_sanitize_mixed($value, "string");
    settype($ignore_forum_id, "bool");

    $forum_id_check = "";
    if (!$ignore_forum_id && !empty($PHORUM["forum_id"])){
        $forum_id_check = "(forum_id = {$PHORUM['forum_id']} OR forum_id={$PHORUM['vroot']}) and";
    }

    if(is_array($value)) {
        $checkvar="$field IN('".implode("','",$value)."')";
        $multiple=true;
    } else {
        $checkvar="$field='$value'";
    }


    $sql = "select {$PHORUM['message_table']}.* from {$PHORUM['message_table']} where $forum_id_check $checkvar";
    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $ret = $multiple ? array() : NULL;

    if(mysqli_num_rows($res)){
        if($multiple) {
            while($rec=mysqli_fetch_assoc($res)) {
                // convert meta field
                if(empty($rec["meta"])){
                    $rec["meta"]=array();
                } else {
                    $rec["meta"]=unserialize($rec["meta"]);
                }
                $ret[$rec['message_id']]=$rec;
            }
        } else {
            $rec = mysqli_fetch_assoc($res);

            // convert meta field
            if(empty($rec["meta"])){
                $rec["meta"]=array();
            } else {
                $rec["meta"]=unserialize($rec["meta"]);
            }
            $ret=$rec;
        }
    }

    return $ret;
}

/**
 * This function executes a query to get the rows with the given thread
 * id and returns an array of the message.
 */
function phorum_db_get_messages($thread,$page=0,$ignore_mod_perms = 0)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($thread, "int");
    settype($page, "int");
    settype($ignore_mod_perms, "bool");

    $conn = phorum_db_mysqli_connect();

    $forum_id_check = "";
    if (!empty($PHORUM["forum_id"])){
        $forum_id_check = "(forum_id = {$PHORUM['forum_id']} OR forum_id={$PHORUM['vroot']}) and";
    }

    // are we really allowed to show this thread/message?
    $approvedval = "";
    if(!$ignore_mod_perms && !phorum_user_access_allowed(PHORUM_USER_ALLOW_MODERATE_MESSAGES)) {
        $approvedval="AND {$PHORUM['message_table']}.status =".PHORUM_STATUS_APPROVED;
    }

    if($page > 0) {
           $start=$PHORUM["read_length"]*($page-1);
           $sql = "select {$PHORUM['message_table']}.* from {$PHORUM['message_table']} where $forum_id_check thread=$thread $approvedval order by message_id LIMIT $start,".$PHORUM["read_length"];
    } else {
           $sql = "select {$PHORUM['message_table']}.* from {$PHORUM['message_table']} where $forum_id_check thread=$thread $approvedval order by message_id";
           if(isset($PHORUM["reverse_threading"]) && $PHORUM["reverse_threading"]) $sql.=" desc";
    }

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $arr = array();

    while ($rec = mysqli_fetch_assoc($res)){
        $arr[$rec["message_id"]] = $rec;

        // convert meta field
        if(empty($rec["meta"])){
            $arr[$rec["message_id"]]["meta"]=array();
        } else {
            $arr[$rec["message_id"]]["meta"]=unserialize($rec["meta"]);
        }
        if(empty($arr['users'])) $arr['users']=array();
        if($rec["user_id"]){
            $arr['users'][]=$rec["user_id"];
        }

    }

    if(count($arr) && $page != 0) {
        // selecting the thread-starter
        $sql = "select {$PHORUM['message_table']}.* from {$PHORUM['message_table']} where $forum_id_check message_id=$thread $approvedval";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
        if(mysqli_num_rows($res) > 0) {
            $rec = mysqli_fetch_assoc($res);
            $arr[$rec["message_id"]] = $rec;
            $arr[$rec["message_id"]]["meta"]=unserialize($rec["meta"]);
        }
    }
    return $arr;
}

/**
 * this function returns the index of a message in a thread
 */
function phorum_db_get_message_index($thread=0,$message_id=0) {
    $PHORUM = $GLOBALS["PHORUM"];

    // check for valid values
    if(empty($thread) || empty($message_id)) {
        return 0;
    }

    settype($thread, "int");
    settype($message_id, "int");

    $approvedval="";
    $forum_id_check="";

    $conn = phorum_db_mysqli_connect();

    if (!empty($PHORUM["forum_id"])){
        $forum_id_check = "(forum_id = {$PHORUM['forum_id']} OR forum_id={$PHORUM['vroot']}) AND";
    }

    if(!phorum_user_access_allowed(PHORUM_USER_ALLOW_MODERATE_MESSAGES)) {
        $approvedval="AND {$PHORUM['message_table']}.status =".PHORUM_STATUS_APPROVED;
    }

    $sql = "select count(*) as msg_index from {$PHORUM['message_table']} where $forum_id_check thread=$thread $approvedval AND message_id <= $message_id order by message_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $rec = mysqli_fetch_assoc($res);

    return $rec['msg_index'];
}

/**
 * This function searches the database for the supplied search
 * criteria and returns an array with two elements.  One is the count
 * of total messages that matched, the second is an array of the
 * messages from the results based on the $start (0 base) given and
 * the $length given.
 */

function phorum_db_search($search, $offset, $length, $match_type, $match_date, $match_forum)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($offset, "int");
    settype($length, "int");
    settype($match_date, "int");

    $start = $offset * $PHORUM["list_length"];

    $arr = array("count" => 0, "rows" => array());

    $conn = phorum_db_mysqli_connect();

    // have to check what forums they can read first.
    $allowed_forums=phorum_user_access_list(PHORUM_USER_ALLOW_READ);
    // if they are not allowed to search any forums, return the emtpy $arr;
    if(empty($allowed_forums) || ($PHORUM['forum_id']>0 && !in_array($PHORUM['forum_id'], $allowed_forums)) ) return $arr;

    // Add forum 0 (for announcements) to the allowed forums.
    $allowed_forums[] = 0;

    if($PHORUM['forum_id']!=0 && $match_forum!="ALL"){
        $forum_where=" and forum_id={$PHORUM['forum_id']}";
    } else {
        $forum_where=" and forum_id in (".implode(",", $allowed_forums).")";
    }

    // prepare terms
    if($match_type=="PHRASE"){

        if(isset($PHORUM["DBCONFIG"]["mysql_use_ft"]) && $PHORUM["DBCONFIG"]["mysql_use_ft"]){
            $terms = array('"'.$search.'"');
        } else {
            $terms = array($search);
        }

    } elseif($match_type=="AUTHOR"){

        $terms = mysqli_real_escape_string($conn, $search);

    } else {

        $quote_terms=array();
        if ( strstr( $search, '"' ) ){
            //first pull out all the double quoted strings (e.g. '"iMac DV" or -"iMac DV"')
            preg_match_all( '/-*"(.*?)"/', $search, $match );
            $search = preg_replace( '/-*".*?"/', '', $search );
            $quote_terms = $match[0];
        }

        //finally pull out the rest words in the string
        $terms = preg_split( "/\s+/", $search, 0, PREG_SPLIT_NO_EMPTY );

        //merge them all together and return
        $terms = array_merge($terms, $quote_terms);

    }


    if(isset($PHORUM["DBCONFIG"]["mysql_use_ft"]) && $PHORUM["DBCONFIG"]["mysql_use_ft"]){

        if($match_type=="AUTHOR"){

            $id_table=$PHORUM['search_table']."_auth_".md5(microtime());

            $sql = "create temporary table $id_table (key(message_id)) ENGINE=HEAP select message_id from {$PHORUM['message_table']} where author='$terms' $forum_where";
            if($match_date>0){
                $ts=time()-86400*$match_date;
                $sql.=" and datestamp>=$ts";
            }

            $res = mysqli_query($conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        } else {


            if(count($terms)){

                $use_key="";
                $extra_where="";

                /* using this code on larger forums has shown to make the search faster.
                   However, on smaller forums, it does not appear to help and in fact
                   appears to slow down searches.

                if($match_date){
                    $min_time=time()-86400*$match_date;
                    $sql="select min(message_id) as min_id from {$PHORUM['message_table']} where datestamp>=$min_time";
                    $res=mysqli_query($conn, $sql);
                    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
                    list($min_id) = mysqli_fetch_row($res);
                    $use_key=" use key (primary)";
                    $extra_where="and message_id>=$min_id";
                }
                */

                $id_table=$PHORUM['search_table']."_ft_".md5(microtime());

                $against = "";

                if($match_type=="ALL" && count($terms)>1){
                    foreach($terms as $term){
                        if($term[0] == "+" || $term[0] == "-"){
                            $against .= mysqli_real_escape_string($conn, $term)." ";
                        } else {
                            $against .= "+".mysqli_real_escape_string($conn, $term)." ";
                        }
                    }
                    $against = trim($against);
                } else {
                    $against=mysqli_real_escape_string($conn, implode(" ", $terms));
                }

                $clause="MATCH (search_text) AGAINST ('$against' IN BOOLEAN MODE)";

                $sql = "create temporary table $id_table (key(message_id)) ENGINE=HEAP select message_id from {$PHORUM['search_table']} $use_key where $clause $extra_where";
                $res = mysqli_query($conn, $sql);
                if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            }
        }


        if(isset($id_table)){

            // create a temporary table of the messages we want
            $table=$PHORUM['search_table']."_".md5(microtime());
            $sql="create temporary table $table (key (forum_id, status, datestamp)) ENGINE=HEAP select {$PHORUM['message_table']}.message_id, {$PHORUM['message_table']}.datestamp, status, forum_id from {$PHORUM['message_table']} inner join $id_table using (message_id) where status=".PHORUM_STATUS_APPROVED." $forum_where";

            if($match_date>0){
                $ts=time()-86400*$match_date;
                $sql.=" and datestamp>=$ts";
            }

            $res=mysqli_query($conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            $sql="select count(*) as count from $table";
            $res = mysqli_query($conn, $sql);

            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
            list($total_count) = mysqli_fetch_row($res);

            $sql="select message_id from $table order by datestamp desc limit $start, $length";
            $res = mysqli_query($conn, $sql, MYSQLI_USE_RESULT);

            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            $idstring="";
            while ($rec = mysqli_fetch_row($res)){
                $idstring.="$rec[0],";
            }
            $idstring=substr($idstring, 0, -1);

            mysqli_free_result($res);
        }

    } else { // not using full text matching

        if($match_type=="AUTHOR"){

            $sql_core = "from {$PHORUM['message_table']} where author='$terms' $forum_where $sql_date";

            if($match_date>0){
                $ts=time()-86400*$match_date;
                $sql_core.=" and datestamp>=$ts";
            }

            $sql = "select count(*) $sql_core";
            $res = mysqli_query($conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
            list($total_count) = mysqli_fetch_row($res);


            $sql = "select message_id $sql_core order by datestamp desc limit $start, $length";

            $res = mysqli_query($conn, $sql, MYSQLI_USE_RESULT);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            $idstring="";
            while ($rec = mysqli_fetch_row($res)){
                $idstring.="$rec[0],";
            }
            $idstring=substr($idstring, 0, -1);

            mysqli_free_result($res);

        } else {

            if(count($terms)){

                if($match_type=="ALL"){
                    $conj="and";
                } else {
                    $conj="or";
                }

                // quote strings correctly
                foreach ($terms as $id => $term) {
                    $terms[$id] = mysqli_real_escape_string($conn, $term);
                }

                $sql_date = "";
                if($match_date>0){
                    $ts=time()-86400*$match_date;
                    $sql_date =" and datestamp>=$ts";
                }

                $clause = "( concat(author, ' | ', subject, ' | ', body) like '%".implode("%' $conj concat(author, ' | ', subject, ' | ', body) like '%", $terms)."%' )";

                $sql = "select count(*) from {$PHORUM['message_table']} where status=".PHORUM_STATUS_APPROVED." and $clause $forum_where $sql_date";
                $res = mysqli_query($conn, $sql);

                if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
                list($total_count) = mysqli_fetch_row($res);

                $sql = "select message_id from {$PHORUM['message_table']} where status=".PHORUM_STATUS_APPROVED." and $clause $forum_where $sql_date order by datestamp desc limit $start, $length";
                $res = mysqli_query($conn, $sql, MYSQLI_USE_RESULT);
                if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

                $idstring="";
                while ($rec = mysqli_fetch_row($res)){
                    $idstring.="$rec[0],";
                }
                $idstring=substr($idstring, 0, -1);

                mysqli_free_result($res);
            }

        }

    }

    if($idstring){
        $sql= "SELECT * FROM {$PHORUM['message_table']} WHERE message_id in ($idstring) ORDER BY datestamp desc";
        $res = mysqli_query($conn, $sql, MYSQLI_USE_RESULT);

        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        $rows = array();

        while ($rec = mysqli_fetch_assoc($res)){
            $rows[$rec["message_id"]] = $rec;
        }

        mysqli_free_result($res);

        $arr = array("count" => $total_count, "rows" => $rows);
    }

    return $arr;
}

function phorum_db_get_older_thread($key) {
    return phorum_db_get_neighbour_thread($key, "older");
}
function phorum_db_get_newer_thread($key) {
    return phorum_db_get_neighbour_thread($key, "newer");
}
function phorum_db_get_neighbour_thread($key, $direction)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($key, "int");

    $conn = phorum_db_mysqli_connect();

    $keyfield = ($PHORUM["float_to_top"]) ? "modifystamp" : "thread";

    switch ($direction) {
        case "newer": $compare = ">"; $orderdir = "ASC";  break;
        case "older": $compare = "<"; $orderdir = "DESC"; break;
        default:
            raise_error(
                "phorum_db_get_neighbour_thread(): " .
                "Illegal direction \"".htmlspecialchars($direction)."\"",
                E_USER_ERROR
            );
    }

    // If the current user is not a moderator for the forum, then
    // the neighbour message should be approved.
    $approvedval = "";
    if (!phorum_user_access_allowed(PHORUM_USER_ALLOW_MODERATE_MESSAGES)) {
        $approvedval = "AND status = ".PHORUM_STATUS_APPROVED;
    }

    $sql = "select thread from {$PHORUM['message_table']} where forum_id={$PHORUM['forum_id']} and parent_id = 0 $approvedval and $keyfield $compare $key order by $keyfield $orderdir limit 1";

    $res = mysqli_query($conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res)) {
        $tmp_row=mysqli_fetch_row($res);
        $retid=$tmp_row[0];
    } else {
        $retid=0;
    }

    return $retid;
}

/**
 * This function executes a query to get bad items of type $type and
 * returns an array of the results.
 */

function phorum_db_load_settings(){
    global $PHORUM;


    $conn = phorum_db_mysqli_connect();

    $sql = "select * from {$PHORUM['settings_table']}";

    $res = mysqli_query( $conn, $sql);
    if(!$res && !defined("PHORUM_ADMIN")){
        if (mysqli_errno($conn)==1146){
            // settings table does not exist
            return;
        } elseif(($err = mysqli_error($conn))){
            phorum_db_mysqli_error("$err: $sql");
        }
    }

    if (empty($err) && $res){
        while ($rec = mysqli_fetch_assoc($res)){

            // only load the default forum options in the admin
            if($rec["name"]=="default_forum_options" && !defined("PHORUM_ADMIN")) continue;

            if ($rec["type"] == "V"){
                if ($rec["data"] == 'true'){
                    $val = true;
                }elseif ($rec["data"] == 'false'){
                    $val = false;
                }elseif (is_numeric($rec["data"])){
                    $val = $rec["data"];
                }else{
                    $val = "$rec[data]";
                }
            }else{
                $val = unserialize($rec["data"]);
            }

            $PHORUM[$rec['name']]=$val;
            $PHORUM['SETTINGS'][$rec['name']]=$val;
        }
    }
}

/**
 * This function executes a query to get bad items of type $type and
 * returns an array of the results.
 */

function phorum_db_update_settings($settings){
    global $PHORUM;

    if (count($settings) > 0){
        $conn = phorum_db_mysqli_connect();

        foreach($settings as $field => $value){
            if (is_numeric($value)){
                $type = 'V';
            }elseif (is_string($value)){
                $value = mysqli_real_escape_string ( $conn, $value);
                $type = 'V';
            }else{
                $value = mysqli_real_escape_string ( $conn, serialize($value));
                $type = 'S';
            }

            $field = mysqli_real_escape_string($conn, $field);

            $sql = "replace into {$PHORUM['settings_table']} set data='$value', type='$type', name='$field'";
            $res = mysqli_query( $conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
        }

        return ($res > 0) ? true : false;
    }else{
        trigger_error("\$settings cannot be empty in phorum_db_update_settings()", E_USER_ERROR);
    }
}

/**
 * This function executes a query to select all forum data from
 * the database for a flat/collapsed display and returns the data in
 * an array.
 */


function phorum_db_get_forums($forum_ids = 0, $parent_id = -1, $vroot = null, $inherit_id = null){
    $PHORUM = $GLOBALS["PHORUM"];

    phorum_db_sanitize_mixed($forum_ids, "int");
    settype($parent_id, "int");
    if($vroot != null) settype($vroot, "int");
    if($inherit_id != null) settype($inherit_id, "int");

    $conn = phorum_db_mysqli_connect();

    if (is_array($forum_ids)) {
        $int_ids = array();
        foreach ($forum_ids as $id) {
            settype($id, "int");
            $int_ids[] = $id;
        }
        $forum_ids = implode(",", $int_ids);
    } else {
        settype($forum_ids, "int");
    }

    $sql = "select * from {$PHORUM['forums_table']} ";
    if ($forum_ids){
        $sql .= " where forum_id in ($forum_ids)";
    } elseif ($inherit_id !== null) {
        $sql .= " where inherit_id = $inherit_id";
        if(!defined("PHORUM_ADMIN")) $sql.=" and active=1";
    } elseif ($parent_id >= 0) {
        $sql .= " where parent_id = $parent_id";
        if(!defined("PHORUM_ADMIN")) $sql.=" and active=1";
    }  elseif($vroot !== null) {
        $sql .= " where vroot = $vroot";
    } else {
        $sql .= " where forum_id <> 0";
    }

    $sql .= " order by display_order ASC, name";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $forums = array();

    while ($row = mysqli_fetch_assoc($res)){
        $forums[$row["forum_id"]] = $row;
    }

    return $forums;
}

/**
 * This function updates the forums stats.  If refresh is true, it pulls the
 * numbers from the table.
 */

function phorum_db_update_forum_stats($refresh=false, $msg_count_change=0, $timestamp=0, $thread_count_change=0, $sticky_count_change=0)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($refresh, "bool");
    settype($msg_count_change, "int");
    settype($timestamp, "int");
    settype($thread_count_change, "int");
    settype($sticky_count_change, "int");

    $conn = phorum_db_mysqli_connect();

    // always refresh on small forums
    if (isset($PHORUM["message_count"]) && $PHORUM["message_count"]<1000) {
        $refresh=true;
    }

    if($refresh || empty($msg_count_change)){
        $sql = "select count(*) as message_count from {$PHORUM['message_table']} where forum_id={$PHORUM['forum_id']} and status=".PHORUM_STATUS_APPROVED;

        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        $tmp_row=mysqli_fetch_row($res);

        $message_count = (int)$tmp_row[0];
    } else {
        $message_count="message_count+$msg_count_change";
    }

    if($refresh || empty($timestamp)){

        $sql = "select max(modifystamp) as last_post_time from {$PHORUM['message_table']} where status=".PHORUM_STATUS_APPROVED." and forum_id={$PHORUM['forum_id']}";

        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        $tmp_row=mysqli_fetch_row($res);
        $last_post_time = (int)$tmp_row[0];
    } else {

        $last_post_time = $timestamp;
    }

    if($refresh || empty($thread_count_change)){

        $sql = "select count(*) as thread_count from {$PHORUM['message_table']} where forum_id={$PHORUM['forum_id']} and parent_id=0 and status=".PHORUM_STATUS_APPROVED;
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        $tmp_row=mysqli_fetch_row($res);
        $thread_count = (int)$tmp_row[0];

    } else {

        $thread_count="thread_count+$thread_count_change";
    }

    if($refresh || empty($sticky_count_change)){

        $sql = "select count(*) as sticky_count from {$PHORUM['message_table']} where forum_id={$PHORUM['forum_id']} and sort=".PHORUM_SORT_STICKY." and parent_id=0 and status=".PHORUM_STATUS_APPROVED;
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        $tmp_row=mysqli_fetch_row($res);
        $sticky_count = (int)$tmp_row[0];

    } else {

        $sticky_count="sticky_count+$sticky_count_change";
    }

    $sql = "update {$PHORUM['forums_table']} set thread_count=$thread_count, message_count=$message_count, sticky_count=$sticky_count, last_post_time=$last_post_time where forum_id={$PHORUM['forum_id']}";
    mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

}

/**
 * actually moves a thread to the given forum
 */
function phorum_db_move_thread($thread_id, $toforum)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($thread_id, "int");
    settype($toforum, "int");

    if($toforum > 0 && $thread_id > 0){
        $conn = phorum_db_mysqli_connect();
        // retrieving the messages for the newflags and search updates below
        $thread_messages=phorum_db_get_messages($thread_id);

        // just changing the forum-id, simple isn't it?
        $sql = "UPDATE {$PHORUM['message_table']} SET forum_id=$toforum where thread=$thread_id";

        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        // we need to update the number of posts in the current forum
        phorum_db_update_forum_stats(true);

        // and of the new forum
        $old_id=$GLOBALS["PHORUM"]["forum_id"];
        $GLOBALS["PHORUM"]["forum_id"]=$toforum;
        phorum_db_update_forum_stats(true);
        $GLOBALS["PHORUM"]["forum_id"]=$old_id;

        // move the new-flags and the search records for this thread
        // to the new forum too
        unset($thread_messages['users']);

        $new_newflags=phorum_db_newflag_get_flags($toforum);
        $message_ids = array();
        $delete_ids = array();
        $search_ids = array();
        foreach($thread_messages as $mid => $data) {
            // gather information for updating the newflags
            if($mid > $new_newflags['min_id']) { // only using it if its higher than min_id
                $message_ids[]=$mid;
            } else { // newflags to delete
                $delete_ids[]=$mid;
            }

            // gather the information for updating the search table
            $search_ids[] = $mid;
        }

        if(count($message_ids)) { // we only go in if there are messages ... otherwise an error occured

            phorum_db_newflag_update_forum($message_ids);

            $ids_str=implode(",",$message_ids);

            // then doing the update to subscriptions
            $sql="UPDATE {$PHORUM['subscribers_table']} SET forum_id = $toforum where thread IN($ids_str)";
            $res = mysqli_query( $conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        }

        if(count($delete_ids)) {
            $ids_str=implode(",",$delete_ids);
            // then doing the delete
            $sql="DELETE FROM {$PHORUM['user_newflags_table']} where message_id IN($ids_str)";
            mysqli_query( $conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
        }

        if (count($search_ids)) {
            $ids_str = implode(",",$search_ids);
            // then doing the search table update
            $sql = "UPDATE {$PHORUM['search_table']} set forum_id = $toforum where message_id in ($ids_str)";
            mysqli_query( $conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
        }

    }
}

/**
 * closes the given thread
 */
function phorum_db_close_thread($thread_id){
    $PHORUM = $GLOBALS["PHORUM"];

    settype($thread_id, "int");

    if($thread_id > 0){
        $conn = phorum_db_mysqli_connect();

        $sql = "UPDATE {$PHORUM['message_table']} SET closed=1 where thread=$thread_id";

        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    }
}

/**
 * (re)opens the given thread
 */
function phorum_db_reopen_thread($thread_id){
    $PHORUM = $GLOBALS["PHORUM"];

    settype($thread_id, "int");

    if($thread_id > 0){
        $conn = phorum_db_mysqli_connect();

        $sql = "UPDATE {$PHORUM['message_table']} SET closed=0 where thread=$thread_id";

        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    }
}

/**
 * This function executes a query to insert a forum into the forums
 * table and returns the forums id on success or 0 on failure.
 */

function phorum_db_add_forum($forum)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    foreach($forum as $key => $value){
        if(phorum_db_validate_field($key)){
            if (is_numeric($value)){
                $value = (int)$value;
                $fields[] = "$key=$value";
            } elseif($value=="NULL") {
                $fields[] = "$key=$value";
            }else{
                $value = mysqli_real_escape_string($conn, $value);
                $fields[] = "$key='$value'";
            }
        }
    }

    $sql = "insert into {$PHORUM['forums_table']} set " . implode(", ", $fields);

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $forum_id = 0;

    if ($res){
        $forum_id = mysqli_insert_id($conn);
    }

    return $forum_id;
}

/**
 * This function executes a query to remove a forum from the forums
 * table and its messages.
 */

function phorum_db_drop_forum($forum_id)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($forum_id, "int");

    $conn = phorum_db_mysqli_connect();

    $tables = array (
        $PHORUM['message_table'],
        $PHORUM['user_permissions_table'],
        $PHORUM['user_newflags_table'],
        $PHORUM['subscribers_table'],
        $PHORUM['forum_group_xref_table'],
        $PHORUM['forums_table'],
        $PHORUM['banlist_table'],
        $PHORUM['search_table']
    );

    foreach($tables as $table){
        $sql = "delete from $table where forum_id=$forum_id";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    }

$sql = "select file_id from {$PHORUM['files_table']} left join {$PHORUM['message_table']} using (message_id) where {$PHORUM['files_table']}.message_id > 0 AND link='" . PHORUM_LINK_MESSAGE . "' AND {$PHORUM['message_table']}.message_id is NULL";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    while($rec=mysqli_fetch_assoc($res)){
        $files[]=$rec["file_id"];
    }
    if(isset($files)){
        $sql = "delete from {$PHORUM['files_table']} where file_id in (".implode(",", $files).")";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    }


}

/**
 * This function executes a query to remove a folder from the forums
 * table and change the parent of its children.
 */

function phorum_db_drop_folder($forum_id)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($forum_id, "int");

    $conn = phorum_db_mysqli_connect();

    $sql = "select parent_id from {$PHORUM['forums_table']} where forum_id=$forum_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $tmp_row=mysqli_fetch_row($res);
    $new_parent_id = $tmp_row[0];

    $sql = "update {$PHORUM['forums_table']} set parent_id=$new_parent_id where parent_id=$forum_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $sql = "delete from {$PHORUM['forums_table']} where forum_id=$forum_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
}

/**
 * This function executes a query to update a forum in the forums
 * table and returns non zero on success or 0 on failure.
 */

function phorum_db_update_forum($forum){
    $PHORUM = $GLOBALS["PHORUM"];

    $res = 0;

    if (!empty($forum["forum_id"])){

        phorum_db_sanitize_mixed($forum["forum_id"], "int");

        // this way we can also update multiple forums at once
        if(is_array($forum["forum_id"])) {
            $forumwhere="forum_id IN (".implode(",",$forum["forum_id"]).")";
        } else {
            $forumwhere="forum_id=".$forum["forum_id"];
        }

        unset($forum["forum_id"]);

        $conn = phorum_db_mysqli_connect();

        foreach($forum as $key => $value){
            if(phorum_db_validate_field($key)){
                if (is_numeric($value)){
                    $value = (int)$value;
                    $fields[] = "$key=$value";
                } elseif($value=="NULL") {
                    $fields[] = "$key=$value";
                } else {
                    $value = mysqli_real_escape_string($conn, $value);
                    $fields[] = "$key='$value'";
                }
            }
        }

        $sql = "update {$PHORUM['forums_table']} set " . implode(", ", $fields) . " where $forumwhere";

        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    }else{
        trigger_error("\$forum[forum_id] cannot be empty in phorum_update_forum()", E_USER_ERROR);
    }

    return $res;
}

/**
*
*/

function phorum_db_get_groups($group_id=0)
{
    $PHORUM = $GLOBALS["PHORUM"];
    $conn = phorum_db_mysqli_connect();


    phorum_db_sanitize_mixed($group_id,"int");


    if(is_array($group_id) && count($group_id)) {
        $group_str=implode(',',$group_id);
        $where_str=" where group_id IN($group_str)";
    } elseif(!is_array($group_id) && $group_id!=0) {
        $where_str=" where group_id=$group_id";
    } else {
        $where_str="";
    }


    $sql="select * from {$PHORUM['groups_table']}".$where_str;

    $res = mysqli_query( $conn, $sql);

    $groups=array();
    while($rec=mysqli_fetch_assoc($res)){

        $groups[$rec["group_id"]]=$rec;
        $groups[$rec["group_id"]]["permissions"]=array();
    }

    $sql="select * from {$PHORUM['forum_group_xref_table']}".$where_str;

    $res = mysqli_query( $conn, $sql);

    while($rec=mysqli_fetch_assoc($res)){

        $groups[$rec["group_id"]]["permissions"][$rec["forum_id"]]=$rec["permission"];

    }

    return $groups;

}

/**
* Get the members of a group.
* @param group_id - can be an integer (single group), or an array of groups
* @param status - a specific status to look for, defaults to all
* @return array - users (key is userid, value is group membership status)
*/

function phorum_db_get_group_members($group_id, $status = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];
    $conn = phorum_db_mysqli_connect();

    phorum_db_sanitize_mixed($group_id, "int");
    if ($status !== NULL) settype($status, "int");

    if(is_array($group_id)){
        $group_id=implode(",", $group_id);
    }

    // this join is only here so that the list of users comes out sorted
    // if phorum_db_user_get() sorts results itself, this join can go away
    $sql="select {$PHORUM['user_group_xref_table']}.user_id, {$PHORUM['user_group_xref_table']}.status from {$PHORUM['user_table']}, {$PHORUM['user_group_xref_table']} where {$PHORUM['user_table']}.user_id = {$PHORUM['user_group_xref_table']}.user_id and group_id in ($group_id)";
    if ($status !== NULL) $sql.=" and {$PHORUM['user_group_xref_table']}.status = $status";
    $sql .=" order by username asc";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    $users=array();
    while($rec=mysqli_fetch_assoc($res)){
        $users[$rec["user_id"]]=$rec["status"];
    }

    return $users;

}

/**
*
*/

function phorum_db_save_group($group)
{
    $PHORUM = $GLOBALS["PHORUM"];
    $conn = phorum_db_mysqli_connect();

    $ret=false;
    $permissions = $group["permissions"];
    phorum_db_sanitize_mixed($group, "string");
    $group["permissions"] = $permissions;

    if(isset($group["name"])){
        $sql="update {$PHORUM['groups_table']} set name='{$group['name']}', open={$group['open']} where group_id={$group['group_id']}";

        $res=mysqli_query( $conn, $sql);

        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    }

    if(!$err){

        if(isset($group["permissions"])){
            $sql="delete from {$PHORUM['forum_group_xref_table']} where group_id={$group['group_id']}";

            $res=mysqli_query( $conn, $sql);

            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            foreach($group["permissions"] as $forum_id=>$permission){
                settype($forum_id, "int");
                settype($permission, "int");
                $sql="insert into {$PHORUM['forum_group_xref_table']} set group_id={$group['group_id']}, permission=$permission, forum_id=$forum_id";
                $res=mysqli_query( $conn, $sql);
                if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
                if(!$res) break;
            }
        }
    }

    if($res>0) $ret=true;

    return $ret;

}

function phorum_db_delete_group($group_id)
{
    $PHORUM = $GLOBALS["PHORUM"];
    $conn = phorum_db_mysqli_connect();

    settype($group_id, "int");

    $sql = "delete from {$PHORUM['groups_table']} where group_id = $group_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // delete things associated with groups
    $sql = "delete from {$PHORUM['user_group_xref_table']} where group_id = $group_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $sql = "delete from {$PHORUM['forum_group_xref_table']} where group_id = $group_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
}

/**
 * phorum_db_add_group()
 *
 * @param $group_name $group_id
 * @return
 **/
function phorum_db_add_group($group_name,$group_id=0)
{
    $PHORUM = $GLOBALS["PHORUM"];
    $conn = phorum_db_mysqli_connect();

    settype($group_id, "int");
    $group_name = mysqli_real_escape_string($conn, $group_name);

    if($group_id > 0) { // only used in conversion
        $sql="insert into {$PHORUM['groups_table']} (group_id,name) values ($group_id,'$group_name')";
    } else {
        $sql="insert into {$PHORUM['groups_table']} (name) values ('$group_name')";
    }

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $group_id = 0;

    if ($res) {
        $group_id = mysqli_insert_id($conn);
    }

    return $group_id;
}

/**
* This function returns all moderators for a particular forum
*/
function phorum_db_user_get_moderators($forum_id,$ignore_user_perms=false,$for_email=false) {

   $PHORUM = $GLOBALS["PHORUM"];
   $userinfo=array();

   $conn = phorum_db_mysqli_connect();

   settype($forum_id, "int");
   settype($ignore_user_perms, "bool");
   settype($for_email, "bool");

   if(!$ignore_user_perms) { // sometimes we just don't need them
       if(!$PHORUM['email_ignore_admin']) {
            $admincheck=" OR user.admin=1";
       } else {
            $admincheck="";
       }


       $sql="SELECT DISTINCT user.user_id, user.email, user.moderation_email FROM {$PHORUM['user_table']} as user LEFT JOIN {$PHORUM['user_permissions_table']} as perm ON perm.user_id=user.user_id WHERE (perm.permission >= ".PHORUM_USER_ALLOW_MODERATE_MESSAGES." AND (perm.permission & ".PHORUM_USER_ALLOW_MODERATE_MESSAGES." > 0) AND perm.forum_id=$forum_id)$admincheck";


       $res = mysqli_query( $conn, $sql);

       if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

       while ($row = mysqli_fetch_row($res)){
           if(!$for_email || $row[2] == 1)
                $userinfo[$row[0]]=$row[1];
       }

   }

   // get users who belong to groups that have moderator access
   $sql = "SELECT DISTINCT user.user_id, user.email, user.moderation_email FROM {$PHORUM['user_table']} AS user, {$PHORUM['groups_table']} AS groups, {$PHORUM['user_group_xref_table']} AS usergroup, {$PHORUM['forum_group_xref_table']} AS forumgroup WHERE user.user_id = usergroup.user_id AND usergroup.group_id = groups.group_id AND groups.group_id = forumgroup.group_id AND forum_id = $forum_id AND permission & ".PHORUM_USER_ALLOW_MODERATE_MESSAGES." > 0 AND usergroup.status >= ".PHORUM_USER_GROUP_APPROVED;

   $res = mysqli_query( $conn, $sql);

   if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

   while ($row = mysqli_fetch_row($res)){
       if(!$for_email || $row[2] == 1)
           $userinfo[$row[0]]=$row[1];
   }
   return $userinfo;
}

/**
 * This function executes a query to select data about a user including
 * his permission data and returns that in an array.
 */

function phorum_db_user_get($user_id, $detailed)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    phorum_db_sanitize_mixed($user_id, "int");

    if(is_array($user_id)){
        if (count($user_id)) {
            $user_ids=implode(",", $user_id);
        } else {
            return array();
        }
    } else {
        $user_ids=(int)$user_id;
    }

    $users = array();

    $sql = "select * from {$PHORUM['user_table']} where user_id in ($user_ids)";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res)){
        while($rec=mysqli_fetch_assoc($res)){
            $users[$rec["user_id"]] = $rec;
        }

        if ($detailed){
            // get the users' permissions
            $sql = "select * from {$PHORUM['user_permissions_table']} where user_id in ($user_ids)";

            $res = mysqli_query( $conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            while ($row = mysqli_fetch_assoc($res)){
                $users[$row["user_id"]]["forum_permissions"][$row["forum_id"]] = $row["permission"];
            }

            // get the users' groups and forum permissions through those groups
            $sql = "select user_id, {$PHORUM['user_group_xref_table']}.group_id, forum_id, permission from {$PHORUM['user_group_xref_table']} left join {$PHORUM['forum_group_xref_table']} using (group_id) where user_id in ($user_ids) AND {$PHORUM['user_group_xref_table']}.status >= ".PHORUM_USER_GROUP_APPROVED;

            $res = mysqli_query( $conn, $sql);
            if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

            while ($row = mysqli_fetch_assoc($res)){
                $users[$row["user_id"]]["groups"][$row["group_id"]] = $row["group_id"];
                if(!empty($row["forum_id"])){
                    if(!isset($users[$row["user_id"]]["group_permissions"][$row["forum_id"]])) {
                         $users[$row["user_id"]]["group_permissions"][$row["forum_id"]] = 0;
                    }
                    $users[$row["user_id"]]["group_permissions"][$row["forum_id"]] = $users[$row["user_id"]]["group_permissions"][$row["forum_id"]] | $row["permission"];
                }
            }

        }
        $sql = "select * from {$PHORUM['user_custom_fields_table']} where user_id in ($user_ids)";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        while ($row = mysqli_fetch_assoc($res)){
            if(isset($PHORUM["PROFILE_FIELDS"][$row['type']])) {
                if($PHORUM["PROFILE_FIELDS"][$row['type']]['html_disabled']) {
                    $users[$row["user_id"]][$PHORUM["PROFILE_FIELDS"][$row['type']]['name']] = htmlspecialchars($row["data"]);
                } else { // not html-disabled
                    if(substr($row["data"],0,6) == 'P_SER:') {
                        // P_SER (PHORUM_SERIALIZED) is our marker telling this field is serialized
                        $users[$row["user_id"]][$PHORUM["PROFILE_FIELDS"][$row['type']]['name']] = unserialize(substr($row["data"],6));
                    } else {
                        $users[$row["user_id"]][$PHORUM["PROFILE_FIELDS"][$row['type']]['name']] = $row["data"];
                    }
                }
            }
        }

    }

    if(is_array($user_id)){
        return $users;
    } else {
        return isset($users[$user_id]) ? $users[$user_id] : NULL;
    }

}

/*
 * Generic function to retrieve a couple of fields from the user-table
 * for a couple of users or only one of them
 *
 * result is always an array with one or more users in it
 */

function phorum_db_user_get_fields($user_id, $fields)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    phorum_db_sanitize_mixed($user_id, "int");

    // input could be either array or string
    if(is_array($user_id)){
        $user_ids=implode(",", $user_id);
    } else {
        $user_ids=(int)$user_id;
    }

    if(!is_array($fields)) {
        $fields = array($fields);
    }

    $users = array();

    foreach($fields as $key=>$field){
        if(!phorum_db_validate_field($field)){
            unset($fields[$key]);
        }
    }


    $sql = "select user_id, ".implode(",", $fields)." from {$PHORUM['user_table']} where user_id in ($user_ids)";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res)){
        while($rec=mysqli_fetch_assoc($res)){
            $users[$rec["user_id"]] = $rec;
        }
    }

    return $users;

}

/**
 * Get a list of all users of a certain type.
 *
 * @param $type - what type of list to retrieve.
 *                0 = all users
 *                1 = all active users
 *                2 = all inactive users
 * @return array - key: userid, value: array (username, displayname)
 */
function phorum_db_user_get_list($type = 0){
   $PHORUM = $GLOBALS["PHORUM"];

   settype($type, "int");

   $conn = phorum_db_mysqli_connect();

   $where = '';
   if ($type == 1) $where = "where active = 1";
   elseif ($type == 2) $where = "where active != 1";

   $users = array();
   $sql = "select user_id, username from {$PHORUM['user_table']} $where order by username asc";
   $res = mysqli_query( $conn, $sql);
   if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

   while ($row = mysqli_fetch_assoc($res)){
       $users[$row["user_id"]] = array("username" => $row["username"], "displayname" => $row["username"]);
   }

   return $users;
}

/**
 * This function executes a query to select data about a user including
 * his permission data and returns that in an array.
 */

function phorum_db_user_check_pass($username, $password, $temp_password=false){
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    $username = mysqli_real_escape_string($conn, $username);

    $password = mysqli_real_escape_string($conn, $password);

    settype($temp_password, "bool");

    $pass_field = ($temp_password) ? "password_temp" : "password";

    $sql = "select user_id from {$PHORUM['user_table']} where username='$username' and $pass_field='$password'";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if($res && mysqli_num_rows($res)) {
        $tmp_row=mysqli_fetch_row($res);
        $retval=$tmp_row[0];
    } else {
        $retval=0;
    }
    return  $retval;
}

/**
 * This function executes a query to check for the given field in the
 * user tableusername and return the user_id of the user it matches or 0
 * if no match is found.
 *
 * The parameters can be arrays.  If they are, all must be passed and all
 * must have the same number of values.
 *
 * If $return_array is true, an array of all matching rows will be returned.
 * Otherwise, only the first user_id from the results will be returned.
 */

function phorum_db_user_check_field($field, $value, $operator="=", $return_array=false){
    $PHORUM = $GLOBALS["PHORUM"];

    $ret = 0;

    $conn = phorum_db_mysqli_connect();

    if(!is_array($field)){
        $field=array($field);
    }

    if(!is_array($value)){
        $value=array($value);
    }

    if(!is_array($operator)){
        $operator=array($operator);
    }

    if(count($field)!=count($value) || count($field)!=count($operator) || count($operator)!=count($value)){
        return $ret;
    }

    $valid_operators = array("=", "<>", "!=", ">", "<", ">=", "<=");

    foreach($field as $key=>$name){
        if(in_array($operator[$key], $valid_operators) && phorum_db_validate_field($name)){
            $value[$key] = mysqli_real_escape_string($conn, $value[$key]);
            $clauses[]="$name $operator[$key] '$value[$key]'";
        }
    }

    $sql = "select user_id from {$PHORUM['user_table']} where ".implode(" and ", $clauses);

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if ($res && mysqli_num_rows($res)){
        if($return_array){
            $ret=array();
            while($row=mysqli_fetch_assoc($res)){
                $ret[$row["user_id"]]=$row["user_id"];
            }
        } else {
            $tmp_row=mysqli_fetch_row($res);
            $ret = $tmp_row[0];
        }
    }

    return $ret;
}


/**
 * This function executes a query to add the given user data to the
 * database and returns the userid or 0
 */

function phorum_db_user_add($userdata){
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if (isset($userdata["forum_permissions"]) && !empty($userdata["forum_permissions"])){
        $forum_perms = $userdata["forum_permissions"];
        unset($userdata["forum_permissions"]);
    }

    if (isset($userdata["user_data"]) && !empty($userdata["user_data"])){
        $user_data = $userdata["user_data"];
        unset($userdata["user_data"]);
    }


    $sql = "insert into {$PHORUM['user_table']} set ";

    $values = array();

    foreach($userdata as $key => $value){
        if (phorum_db_validate_field($key)){
            if (!is_numeric($value)){
                $value = mysqli_real_escape_string($conn, $value);
                $values[] = "$key='$value'";
            }else{
                $values[] = "$key=$value";
            }
        }
    }

    $sql .= implode(", ", $values);

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $user_id = 0;
    if ($res){
        $user_id = mysqli_insert_id($conn);
    }

    if ($res){
        if(isset($forum_perms)) {
            // storing forum-permissions
            foreach($forum_perms as $fid => $p){
                $sql = "insert into {$PHORUM['user_permissions_table']} set user_id=$user_id, forum_id=$fid, permission=$p";
                $res = mysqli_query( $conn, $sql);
                if ($err = mysqli_error($conn)){
                    phorum_db_mysqli_error("$err: $sql");
                    break;
                }
            }
        }
        if(isset($user_data)) {
            /* storing custom-fields */
            foreach($user_data as $key => $val){
                if(is_array($val)) { /* arrays need to be serialized */
                    $val = 'P_SER:'.serialize($val);
                    /* P_SER: (PHORUM_SERIALIZED is our marker telling this Field is serialized */
                } else { /* other vars need to be escaped */
                    $val = mysqli_real_escape_string($conn, $val);
                }
                $sql = "insert into {$PHORUM['user_custom_fields_table']} (user_id,type,data) VALUES($user_id,$key,'$val')";
                $res = mysqli_query( $conn, $sql);
                if ($err = mysqli_error($conn)){
                    phorum_db_mysqli_error("$err: $sql");
                    break;
                }
            }
        }
    }

    return $user_id;
}


/**
 * This function executes a query to update the given user data in the
 * database and returns the true or false
 */
function phorum_db_user_save($userdata){
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if(isset($userdata["permissions"])){
        unset($userdata["permissions"]);
    }

    if (isset($userdata["forum_permissions"])){
        $forum_perms = $userdata["forum_permissions"];
        unset($userdata["forum_permissions"]);
    }

    if (isset($userdata["groups"])){
        $groups = $userdata["groups"];
        unset($userdata["groups"]);
        unset($userdata["group_permissions"]);
    }
    if (isset($userdata["user_data"])){
        $user_data = $userdata["user_data"];
        unset($userdata["user_data"]);
    }

    $user_id = $userdata["user_id"];
    unset($userdata["user_id"]);

    if(count($userdata)){

        $sql = "update {$PHORUM['user_table']} set ";

        $values = array();

        foreach($userdata as $key => $value){
            $values[] = "$key='".mysqli_real_escape_string($conn, $value)."'";
        }

        $sql .= implode(", ", $values);

        $sql .= " where user_id=$user_id";

        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    }

    if (isset($forum_perms)){

        $sql = "delete from {$PHORUM['user_permissions_table']} where user_id = $user_id";
        $res=mysqli_query( $conn, $sql);

        foreach($forum_perms as $fid=>$perms){
            $sql = "insert into {$PHORUM['user_permissions_table']} set user_id=$user_id, forum_id=$fid, permission=$perms";
            $res = mysqli_query( $conn, $sql);
            if ($err = mysqli_error($conn)){
                phorum_db_mysqli_error("$err: $sql");
            }
        }
    }
    if(isset($user_data)) {
        // storing custom-fields
        $sql = "delete from {$PHORUM['user_custom_fields_table']} where user_id = $user_id";
        $res=mysqli_query( $conn, $sql);

        if(is_array($user_data)) {
            foreach($user_data as $key => $val){
                if(is_array($val)) { /* arrays need to be serialized */
                    $val = 'P_SER:'.serialize($val);
                    /* P_SER: (PHORUM_SERIALIZED is our marker telling this Field is serialized */
                } else { /* other vars need to be escaped */
                    $val = mysqli_real_escape_string($conn, $val);
                }

                $sql = "insert into {$PHORUM['user_custom_fields_table']} (user_id,type,data) VALUES($user_id,$key,'$val')";
                $res = mysqli_query( $conn, $sql);
                if ($err = mysqli_error($conn)){
                    phorum_db_mysqli_error("$err: $sql");
                    break;
                }
            }
        }
    }

    return (bool)$res;
}

/**
 * This function saves a users group permissions.
 */
function phorum_db_user_save_groups($user_id, $groups)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($user_id, "int");

    if (!$user_id > 0){
        return false;
    }

    // erase the group memberships they have now
    $conn = phorum_db_mysqli_connect();
    $sql = "delete from {$PHORUM['user_group_xref_table']} where user_id = $user_id";
    $res=mysqli_query( $conn, $sql);

    foreach($groups as $group_id => $group_perm){
        $group_id = (int)$group_id;
        $group_perm = (int)$group_perm;
        $sql = "insert into {$PHORUM['user_group_xref_table']} set user_id=$user_id, group_id=$group_id, status=$group_perm";
        mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)){
            phorum_db_mysqli_error("$err: $sql");
            break;
        }
    }
    return (bool)$res;
}

/**
 * This function executes a query to subscribe a user to a forum/thread.
 */

function phorum_db_user_subscribe($user_id, $forum_id, $thread, $type)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($user_id, "int");
    settype($forum_id, "int");
    settype($thread, "int");
    settype($type, "int");

    $conn = phorum_db_mysqli_connect();

    $sql = "replace into {$PHORUM['subscribers_table']} set user_id=$user_id, forum_id=$forum_id, sub_type=$type, thread=$thread";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    return (bool)$res;
}

/**
  * This function increases the post-counter for a user by one
  */
function phorum_db_user_addpost() {

        $conn = phorum_db_mysqli_connect();

        $sql="UPDATE ".$GLOBALS['PHORUM']['user_table']." SET posts=posts+1 WHERE user_id = ".$GLOBALS['PHORUM']['user']['user_id'];
        $res=mysqli_query( $conn, $sql);

        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        return (bool)$res;
}

/**
 * This function executes a query to unsubscribe a user to a forum/thread.
 */

function phorum_db_user_unsubscribe($user_id, $thread, $forum_id=0)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($user_id, "int");
    settype($forum_id, "int");
    settype($thread, "int");

    $conn = phorum_db_mysqli_connect();

    $sql = "DELETE FROM {$PHORUM['subscribers_table']} WHERE user_id=$user_id AND thread=$thread";
    if($forum_id) $sql.=" and forum_id=$forum_id";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    return (bool)$res;
}

/**
 * This function will return a list of groups the user
 * is a member of, as well as the users permissions.
 */
function phorum_db_user_get_groups($user_id)
{
    $PHORUM = $GLOBALS["PHORUM"];
    $groups = array();

    settype($user_id, "int");

    if (!$user_id > 0){
           return $groups;
    }

    $conn = phorum_db_mysqli_connect();
    $sql = "SELECT group_id, status FROM {$PHORUM['user_group_xref_table']} WHERE user_id = $user_id ORDER BY status DESC";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    while($row = mysqli_fetch_assoc($res)){
        $groups[$row["group_id"]] = $row["status"];
    }

    return $groups;
}

/**
 * This function executes a query to select data about a user including
 * his permission data and returns that in an array.
 * If $search is empty, all users should be returned.
 */

function phorum_db_search_users($search)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    $users = array();

    $search = mysqli_real_escape_string($conn, trim($search));

    $sql = "select user_id, username, email, active, posts, date_last_active from {$PHORUM['user_table']} where username like '%$search%' or email like '%$search%'order by username";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res)){
        while ($user = mysqli_fetch_assoc($res)){
            $users[$user["user_id"]] = $user;
        }
    }

    return $users;
}


/**
 * This function gets the users that await approval
 */

function phorum_db_user_get_unapproved()
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    $sql="select user_id, username, email from {$PHORUM['user_table']} where active in(".PHORUM_USER_PENDING_BOTH.", ".PHORUM_USER_PENDING_MOD.") order by username";
    $res=mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }

    $users=array();
    if($res){
        while($rec=mysqli_fetch_assoc($res)){
            $users[$rec["user_id"]]=$rec;
        }
    }

    return $users;

}
/**
 * This function deletes a user completely
 * - entry in the users-table
 * - entries in the permissions-table
 * - entries in the newflags-table
 * - entries in the subscribers-table
 * - entries in the group_xref-table
 * - entries in the private-messages-table
 * - entries in the files-table
 * - sets entries in the messages-table to anonymous
 *
 */
function phorum_db_user_delete($user_id) {
    $PHORUM = $GLOBALS["PHORUM"];

    // how would we check success???
    $ret = true;

    settype($user_id, "int");

    $conn = phorum_db_mysqli_connect();
    // user-table
    $sql = "delete from {$PHORUM['user_table']} where user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // permissions-table
    $sql = "delete from {$PHORUM['user_permissions_table']} where user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // newflags-table
    $sql = "delete from {$PHORUM['user_newflags_table']} where user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // subscribers-table
    $sql = "delete from {$PHORUM['subscribers_table']} where user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // group-xref-table
    $sql = "delete from {$PHORUM['user_group_xref_table']} where user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // private messages
    $sql = "select * from {$PHORUM["pm_xref_table"]} where user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    while ($row = mysqli_fetch_assoc($res)) {
        $folder = $row["pm_folder_id"] == 0 ? $row["special_folder"] : $row["pm_folder_id"];
        phorum_db_pm_delete($row["pm_message_id"], $folder, $user_id);
    }

    // pm_buddies
    $sql = "delete from {$PHORUM["pm_buddies_table"]} where user_id=$user_id or buddy_user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // private message folders
    $sql = "delete from {$PHORUM["pm_folders_table"]} where user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // files-table
    $sql = "delete from {$PHORUM['files_table']} where user_id=$user_id and message_id=0 and link='" . PHORUM_LINK_USER . "'";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // custom-fields-table
    $sql = "delete from {$PHORUM['user_custom_fields_table']} where user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // messages-table
    if(PHORUM_DELETE_CHANGE_AUTHOR) {
      $sql = "update {$PHORUM['message_table']} set user_id=0,email='',author='".mysqli_real_escape_string($conn,$PHORUM['DATA']['LANG']['AnonymousUser'])."' where user_id=$user_id";
    } else {
      $sql = "update {$PHORUM['message_table']} set user_id=0,email='' where user_id=$user_id";
    }
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    return $ret;
}


/**
 * This function gets the users file list
 */

function phorum_db_get_user_file_list($user_id)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($user_id, "int");

    $files=array();

    $sql="select file_id, filename, filesize, add_datetime from {$PHORUM['files_table']} where user_id=$user_id and message_id=0 and link='" . PHORUM_LINK_USER . "'";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }

    if($res){
        while($rec=mysqli_fetch_assoc($res)){
            $files[$rec["file_id"]]=$rec;
        }
    }

    return $files;
}


/**
 * This function gets the message's file list
 */

function phorum_db_get_message_file_list($message_id)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($message_id, "int");

    $files=array();

    $sql="select file_id, filename, filesize, add_datetime from {$PHORUM['files_table']} where message_id=$message_id and link='" . PHORUM_LINK_MESSAGE . "'";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }

    if($res){
        while($rec=mysqli_fetch_assoc($res)){
            $files[$rec["file_id"]]=$rec;
        }
    }

    return $files;
}


/**
 * This function retrieves a file from the db
 */

function phorum_db_file_get($file_id)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($file_id, "int");

    $file=array();

    $sql="select * from {$PHORUM['files_table']} where file_id=$file_id";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }

    if($res){
        $file=mysqli_fetch_assoc($res);
    }

    return $file;
}


/**
 * This function saves a file to the db
 */

function phorum_db_file_save($user_id, $filename, $filesize, $buffer, $message_id=0, $link=null)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    $file_id=0;

    settype($user_id, "int");
    settype($message_id, "int");
    settype($filesize, "int");

    if (is_null($link)) {
        $link = $message_id ? PHORUM_LINK_MESSAGE : PHORUM_LINK_USER;
    } else {
        $link = mysqli_real_escape_string($conn, $link);
    }

    $filename=mysqli_real_escape_string($conn, $filename);
    $buffer=mysqli_real_escape_string($conn, $buffer);

    $sql="insert into {$PHORUM['files_table']} set user_id=$user_id, message_id=$message_id, link='$link', filename='$filename', filesize=$filesize, file_data='$buffer', add_datetime=".time();

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }

    if($res){
        $file_id=mysqli_insert_id($conn);
    }

    return $file_id;
}


/**
 * This function saves a file to the db
 */

function phorum_db_file_delete($file_id)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($file_id, "int");

    $sql="delete from {$PHORUM['files_table']} where file_id=$file_id";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }

    return $res;
}

/**
 * This function links a file to a specific message
 */

function phorum_db_file_link($file_id, $message_id, $link = null)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($file_id, "int");
    settype($message_id, "int");

    if (is_null($link)) {
        $link = $message_id ? PHORUM_LINK_MESSAGE : PHORUM_LINK_USER;
    } else {
        $link = mysqli_real_escape_string($conn, $link);
    }

    $sql="update {$PHORUM['files_table']} " .
         "set message_id=$message_id, link='$link' " .
         "where file_id=$file_id";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }

    return $res;
}

/**
 * This function reads the current total size of all files for a user
 */

function phorum_db_get_user_filesize_total($user_id)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($user_id, "int");

    $total=0;

    $sql="select sum(filesize) as total from {$PHORUM['files_table']} where user_id=$user_id and message_id=0 and link='" . PHORUM_LINK_USER . "'";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }

    if($res){
        $tmp_row=mysqli_fetch_row($res);
        $total=$tmp_row[0];
    }

    return $total;

}

/**
 * This function is used for cleaning up stale files from the
 * database. Stale files are files that are not linked to
 * anything. These can for example be caused by users that
 * are writing a message with attachments, but never post
 * it.
 * @param live_run - If set to false (default), the function
 *                  will return a list of files that will
 *                  be purged. If set to true, files will
 *                  be purged.
 */
function phorum_db_file_purge_stale_files($live_run = false)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    $where = "link='" . PHORUM_LINK_EDITOR. "' " .
             "and add_datetime<". (time()-PHORUM_MAX_EDIT_TIME);

    // Purge files.
    if ($live_run) {

        // Delete files that are linked to the editor and are
        // added a while ago. These are from abandoned posts.
        $sql = "delete from {$PHORUM['files_table']} " .
               "where $where";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        return true;

    // Only select a list of files that can be purged.
    } else {

        // Select files that are linked to the editor and are
        // added a while ago. These are from abandoned posts.
        $sql = "select file_id, filename, filesize, add_datetime " .
               "from {$PHORUM['files_table']} " .
               "where $where";

        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        $purge_files = array();
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $row["reason"] = "Stale editor file";
                $purge_files[$row["file_id"]] = $row;
            }
        }

        return $purge_files;
    }
}

/**
 * This function returns the newinfo-array for markallread
 */

function phorum_db_newflag_allread($forum_id=0)
{
    $PHORUM = $GLOBALS['PHORUM'];
    $conn = phorum_db_mysqli_connect();

    settype($forum_id, "int");

    if(empty($forum_id)) $forum_id=$PHORUM["forum_id"];

    // delete all newflags for this user and forum
    phorum_db_newflag_delete(0,$forum_id);

    // get the maximum message-id in this forum
    $sql = "select max(message_id) from {$PHORUM['message_table']} where forum_id in ($forum_id, {$PHORUM['vroot']})";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)){
        phorum_db_mysqli_error("$err: $sql");
    }elseif (mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_row($res);
        if($row[0] > 0) {
            // set this message as min-id
            phorum_db_newflag_add_read(array(0=>array('id'=>$row[0],'forum'=>$forum_id)));
        }
    }

}


/**
* This function returns the read messages for the current user and forum
* optionally for a given forum (for the index)
*/
function phorum_db_newflag_get_flags($forum_id=0)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($forum_id, "int");

    $read_msgs=array('min_id'=>0);

    if(empty($forum_id)) $forum_id=$PHORUM["forum_id"];

    $sql="SELECT message_id,forum_id FROM ".$PHORUM['user_newflags_table']." WHERE user_id={$PHORUM['user']['user_id']} AND forum_id IN({$forum_id},{$PHORUM['vroot']})";

    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    while($row=mysqli_fetch_row($res)) {
        // set the min-id if given flag is set
        if($row[1] != $PHORUM['vroot'] && ($read_msgs['min_id']==0 || $row[0] < $read_msgs['min_id'])) {
            $read_msgs['min_id']=$row[0];
        } else {
            $read_msgs[$row[0]]=$row[0];
        }
    }

    return $read_msgs;
}


/**
* This function returns the count of unread messages the current user and forum
* optionally for a given forum (for the index)
*/
function phorum_db_newflag_get_unread_count($forum_id=0)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($forum_id, "int");

    if(empty($forum_id)) $forum_id=$PHORUM["forum_id"];

    // get the read message array
    $read_msgs = phorum_db_newflag_get_flags($forum_id);

    if($read_msgs["min_id"]==0) return array(0,0);

    $sql="SELECT count(*) as count FROM ".$PHORUM['message_table']." WHERE message_id NOT in (".implode(",", $read_msgs).") and message_id > {$read_msgs['min_id']} and forum_id in ({$forum_id},{$PHORUM['vroot']}) and status=".PHORUM_STATUS_APPROVED." and not ".PHORUM_SQL_MOVEDMESSAGES;

    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $tmp_row=mysqli_fetch_row($res);
    $counts[] = $tmp_row[0];

    $sql="SELECT count(*) as count FROM ".$PHORUM['message_table']." WHERE message_id NOT in (".implode(",", $read_msgs).") and message_id > {$read_msgs['min_id']} and forum_id in ({$forum_id},{$PHORUM['vroot']}) and parent_id=0 and status=".PHORUM_STATUS_APPROVED." and not ".PHORUM_SQL_MOVEDMESSAGES;

    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $tmp_row=mysqli_fetch_row($res);
    $counts[] = $tmp_row[0];

    return $counts;
}


/**
 * This function marks a message as read
 */
function phorum_db_newflag_add_read($message_ids) {
    $PHORUM = $GLOBALS["PHORUM"];

    $num_newflags=phorum_db_newflag_get_count();

    // maybe got just one message
    if(!is_array($message_ids)) {
        $message_ids=array(0=>(int)$message_ids);
    }
    // deleting messages which are too much
    $num_end=$num_newflags+count($message_ids);
    if($num_end > PHORUM_MAX_NEW_INFO) {
        phorum_db_newflag_delete($num_end - PHORUM_MAX_NEW_INFO);
    }
    // building the query
    $values=array();
    $cnt=0;

    foreach($message_ids as $id=>$data) {
        if(is_array($data)) {
            $data["forum"] = (int)$data["forum"];
            $data["id"] = (int)$data["id"];
            $values[]="({$PHORUM['user']['user_id']},{$data['forum']},{$data['id']})";
        } else {
            $data = (int)$data;
            $values[]="({$PHORUM['user']['user_id']},{$PHORUM['forum_id']},$data)";
        }
        $cnt++;
    }
    if($cnt) {
        $insert_sql="INSERT IGNORE INTO ".$PHORUM['user_newflags_table']." (user_id,forum_id,message_id) VALUES".join(",",$values);

        // fire away
        $conn = phorum_db_mysqli_connect();
        $res = mysqli_query($conn, $insert_sql);

        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $insert_sql");
    }
}

/**
* This function returns the number of newflags for this user and forum
*/
function phorum_db_newflag_get_count($forum_id=0)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($forum_id, "int");

    if(empty($forum_id)) $forum_id=$PHORUM["forum_id"];

    $sql="SELECT count(*) FROM ".$PHORUM['user_newflags_table']." WHERE user_id={$PHORUM['user']['user_id']} AND forum_id={$forum_id}";

    // fire away
    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $row=mysqli_fetch_row($res);

    return $row[0];
}

/**
* This function removes a number of newflags for this user and forum
*/
function phorum_db_newflag_delete($numdelete=0,$forum_id=0)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($forum_id, "int");
    settype($numdelete, "int");

    if(empty($forum_id)) $forum_id=$PHORUM["forum_id"];

    if($numdelete>0) {
        $lvar=" ORDER BY message_id ASC LIMIT $numdelete";
    } else {
        $lvar="";
    }
    // delete the number of newflags given
    $del_sql="DELETE FROM ".$PHORUM['user_newflags_table']." WHERE user_id={$PHORUM['user']['user_id']} AND forum_id={$forum_id}".$lvar;
    // fire away
    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query($conn, $del_sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $del_sql");
}


/**
 * This function executes a query to fix any newflags that are assigned to the wrong forum
 */

function phorum_db_newflag_update_forum($message_ids) {

    if(!is_array($message_ids)) {
        return;
    }

    phorum_db_sanitize_mixed($message_ids, "int");

    $ids_str=implode(",",$message_ids);

    // then doing the update to newflags
    $sql="UPDATE IGNORE {$GLOBALS['PHORUM']['user_newflags_table']} as flags, {$GLOBALS['PHORUM']['message_table']} as msg SET flags.forum_id=msg.forum_id where flags.message_id=msg.message_id and flags.message_id IN ($ids_str)";
    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query($conn, $del_sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $del_sql");

}

/**
 * This function executes a query to get the user ids of the users
 * subscribed to a forum/thread.
 */

function phorum_db_get_subscribed_users($forum_id, $thread, $type){
    $PHORUM = $GLOBALS["PHORUM"];

    settype($forum_id, "int");
    settype($thread, "int");
    settype($type, "int");

    $conn = phorum_db_mysqli_connect();

    $userignore="";
    if ($PHORUM["DATA"]["LOGGEDIN"])
       $userignore="and b.user_id != {$PHORUM['user']['user_id']}";

    $sql = "select DISTINCT(b.email),user_language from {$PHORUM['subscribers_table']} as a,{$PHORUM['user_table']} as b where a.forum_id=$forum_id and (a.thread=$thread or a.thread=0) and a.sub_type=$type and b.user_id=a.user_id $userignore";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        $arr=array();

    while ($rec = mysqli_fetch_row($res)){
        if(!empty($rec[1])) // user-language is set
            $arr[$rec[1]][] = $rec[0];
        else // no user-language is set
            $arr[$PHORUM['language']][]= $rec[0];
    }

    return $arr;
}

/**
 * This function executes a query to get the subscriptions of a user-id,
 * together with the forum-id and subjects of the threads
 */

function phorum_db_get_message_subscriptions($user_id,$days=2){
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($user_id, "int");
    settype($days, "int");

    $userignore="";
    if ($PHORUM["DATA"]["LOGGEDIN"])
       $userignore="and b.user_id != {$PHORUM['user']['user_id']}";

    if($days > 0) {
         $timestr=" AND (".time()." - b.modifystamp) <= ($days * 86400)";
    } else {
        $timestr="";
    }

    $sql = "select a.thread, a.forum_id, a.sub_type, b.subject,b.modifystamp,b.author,b.user_id,b.email from {$PHORUM['subscribers_table']} as a,{$PHORUM['message_table']} as b where a.user_id=$user_id and b.message_id=a.thread and (a.sub_type=".PHORUM_SUBSCRIPTION_MESSAGE." or a.sub_type=".PHORUM_SUBSCRIPTION_BOOKMARK.")"."$timestr ORDER BY b.modifystamp desc";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $arr=array();
    $forum_ids=array();

    while ($rec = mysqli_fetch_assoc($res)){
        $unsub_url=phorum_get_url(PHORUM_CONTROLCENTER_URL, "panel=".PHORUM_CC_SUBSCRIPTION_THREADS, "unsub_id=".$rec['thread'], "unsub_forum=".$rec['forum_id'], "unsub_type=".$rec['sub_type']);
        $rec['unsubscribe_url']=$unsub_url;
        $arr[] = $rec;
        $forum_ids[]=$rec['forum_id'];
    }
    $arr['forum_ids']=$forum_ids;

    return $arr;
}

/**
 * This function executes a query to find out if a user is subscribed to a thread
 */

function phorum_db_get_if_subscribed($forum_id, $thread, $user_id, $type=PHORUM_SUBSCRIPTION_MESSAGE)
{
    $PHORUM = $GLOBALS["PHORUM"];

    settype($forum_id, "int");
    settype($thread, "int");
    settype($user_id, "int");
    settype($type, "int");

    $conn = phorum_db_mysqli_connect();

    $sql = "select user_id from {$PHORUM['subscribers_table']} where forum_id=$forum_id and thread=$thread and user_id=$user_id and sub_type=$type";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res) > 0){
        $retval = true;
    }else{
        $retval = false;
    }

    return $retval;
}


/**
 * This function retrieves the banlists for the current forum
 */

function phorum_db_get_banlists($ordered=false) {
    $PHORUM = $GLOBALS["PHORUM"];

    $retarr = array();
    $forumstr = "";

    $conn = phorum_db_mysqli_connect();

    if(isset($PHORUM['forum_id']) && !empty($PHORUM['forum_id']))
        $forumstr = "WHERE forum_id = {$PHORUM['forum_id']} OR forum_id = 0";

    if(isset($PHORUM['vroot']) && !empty($PHORUM['vroot']))
        $forumstr .= " OR forum_id = {$PHORUM['vroot']}";



    $sql = "SELECT * FROM {$PHORUM['banlist_table']} $forumstr";

    if($ordered) {
        $sql.= " ORDER BY type, string";
    }

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_assoc($res)) {
            $retarr[$row['type']][$row['id']]=array('pcre'=>$row['pcre'],'string'=>$row['string'],'forum_id'=>$row['forum_id']);
        }
    }
    return $retarr;
}


/**
 * This function retrieves one item from the banlists
 */

function phorum_db_get_banitem($banid) {
    $PHORUM = $GLOBALS["PHORUM"];

    $retarr = array();

    $conn = phorum_db_mysqli_connect();

    settype($banid, "int");

    $sql = "SELECT * FROM {$PHORUM['banlist_table']} WHERE id = $banid";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_assoc($res)) {
            $retarr=array('pcre'=>$row['pcre'],'string'=>$row['string'],'forum_id'=>$row['forum_id'],'type'=>$row['type']);
        }
    }
    return $retarr;
}


/**
 * This function deletes one item from the banlists
 */

function phorum_db_del_banitem($banid) {
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($banid, "int");

    $sql = "DELETE FROM {$PHORUM['banlist_table']} WHERE id = $banid";

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if(mysqli_affected_rows($conn) > 0) {
        return true;
    } else {
        return false;
    }
}


/**
 * This function adds or modifies a banlist-entry
 */

function phorum_db_mod_banlists($type,$pcre,$string,$forum_id,$id=0) {
    $PHORUM = $GLOBALS["PHORUM"];

    $retarr = array();

    $conn = phorum_db_mysqli_connect();

    settype($type, "int");
    settype($pcre, "int");
    settype($forum_id, "int");
    settype($id, "int");

    if($id > 0) { // modifying an entry
        $sql = "UPDATE {$PHORUM['banlist_table']} SET forum_id = $forum_id, type = $type, pcre = $pcre, string = '".mysqli_real_escape_string($conn, $string)."' where id = $id";
    } else { // adding an entry
        $sql = "INSERT INTO {$PHORUM['banlist_table']} (forum_id,type,pcre,string) VALUES($forum_id,$type,$pcre,'".mysqli_real_escape_string($conn, $string)."')";
    }

    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) {
        phorum_db_mysqli_error("$err: $sql");
        return false;
    } else {
        return true;
    }
}



/**
 * This function lists all private messages in a folder.
 * @param folder - The folder to use. Either a special folder
 *                 (PHORUM_PM_INBOX or PHORUM_PM_OUTBOX) or the
 *                 id of a user's custom folder.
 * @param user_id - The user to retrieve messages for or NULL
 *                 to use the current user (default).
 * @param reverse - If set to a true value (default), sorting
 *                 of messages is done in reverse (newest first).
 */

function phorum_db_pm_list($folder, $user_id = NULL, $reverse = true)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");

    $folder_sql = "user_id = $user_id AND ";
    if (is_numeric($folder)) {
        $folder_sql .= "pm_folder_id=$folder";
    } elseif ($folder == PHORUM_PM_INBOX || $folder == PHORUM_PM_OUTBOX) {
        $folder_sql .= "pm_folder_id=0 AND special_folder='$folder'";
    } else {
        die ("Illegal folder '$folder' requested for user id '$user_id'");
    }

    $sql = "SELECT m.pm_message_id, from_user_id, from_username, subject, " .
           "datestamp, meta, pm_xref_id, user_id, pm_folder_id, " .
           "special_folder, read_flag, reply_flag " .
           "FROM {$PHORUM['pm_messages_table']} as m, {$PHORUM['pm_xref_table']} as x " .
           "WHERE $folder_sql " .
           "AND x.pm_message_id = m.pm_message_id " .
           "ORDER BY x.pm_message_id " . ($reverse ? "DESC" : "ASC");
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $list = array();
    if (mysqli_num_rows($res) > 0){
        while($row = mysqli_fetch_assoc($res)) {

            // Add the recipient information unserialized to the message..
            $meta = unserialize($row['meta']);
            $row['recipients'] = $meta['recipients'];

            $list[$row["pm_message_id"]]=$row;
        }
    }

    return $list;
}

/**
 * This function retrieves a private message from the database.
 * @param pm_id - The id for the private message to retrieve.
 * @param user_id - The user to retrieve messages for or NULL
 *                 to use the current user (default).
 * @param folder_id - The folder to retrieve the message from or
 *                    NULL if the folder does not matter.
 */

function phorum_db_pm_get($pm_id, $folder = NULL, $user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");
    settype($pm_id, "int");

    if (is_null($folder)) {
        $folder_sql = '';
    } elseif (is_numeric($folder)) {
        $folder_sql = "pm_folder_id=$folder AND ";
    } elseif ($folder == PHORUM_PM_INBOX || $folder == PHORUM_PM_OUTBOX) {
        $folder_sql = "pm_folder_id=0 AND special_folder='$folder' AND ";
    } else {
        die ("Illegal folder '$folder' requested for message id '$pm_id'");
    }

    $sql = "SELECT * " .
           "FROM {$PHORUM['pm_messages_table']} as m, {$PHORUM['pm_xref_table']} as x " .
           "WHERE $folder_sql x.pm_message_id = $pm_id AND x.user_id = $user_id " .
           "AND x.pm_message_id = m.pm_message_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);

        // Add the recipient information unserialized to the message..
        $meta = unserialize($row['meta']);
        $row['recipients'] = $meta['recipients'];

        return $row;
    } else {
        return NULL;
    }
}

/**
 * This function creates a new folder for a user.
 * @param foldername - The name of the folder to create.
 * @param user_id - The user to create the folder for or
 *                  NULL to use the current user (default).
 */
function phorum_db_pm_create_folder($foldername, $user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");

    $sql = "INSERT INTO {$PHORUM['pm_folders_table']} SET " .
           "user_id=$user_id, " .
           "foldername='".mysqli_real_escape_string($conn, $foldername)."'";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $folder_id = 0;
    if ($res){
        $folder_id = mysqli_insert_id($conn);
    }

    return $folder_id;
}

/**
 * This function renames a folder for a user.
 * @param folder_id - The id of the folder to rename.
 * @param newname - The new name for the folder.
 * @param user_id - The user to rename the folder for or
 *                  NULL to use the current user (default).
 */
function phorum_db_pm_rename_folder($folder_id, $newname, $user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");
    settype($folder_id, "int");

    $sql = "UPDATE {$PHORUM['pm_folders_table']} " .
           "SET foldername = '".mysqli_real_escape_string($conn, $newname)."' " .
           "WHERE pm_folder_id = $folder_id AND user_id = $user_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    return $res;
}



/**
 * This function deletes a folder for a user. Along with the
 * folder, all contained messages are deleted as well.
 * @param folder_id - The id of the folder to delete.
 * @param user_id - The user to delete the folder for or
 *                  NULL to use the current user (default).
 */
function phorum_db_pm_delete_folder($folder_id, $user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");
    settype($folder_id, "int");

    // Get messages in this folder and delete them.
    $list = phorum_db_pm_list($folder_id, $user_id);
    foreach ($list as $id => $data) {
        phorum_db_pm_delete($id, $folder_id, $user_id);
    }

    // Delete the folder itself.
    $sql = "DELETE FROM {$PHORUM['pm_folders_table']} " .
           "WHERE pm_folder_id = $folder_id AND user_id = $user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    return $res;
}

/**
 * This function retrieves the list of folders for a user.
 * @param user_id - The user to retrieve folders for or NULL
 *                 to use the current user (default).
 * @param count_messages - Count the number of messages for the
 *                 folders. Default, this is not done.
 */
function phorum_db_pm_getfolders($user_id = NULL, $count_messages = false)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");

    // Setup the list of folders. Our special folders are
    // not in the database, so these are added here.
    $folders = array(
        PHORUM_PM_INBOX => array(
            'id'   => PHORUM_PM_INBOX,
            'name' => $PHORUM["DATA"]["LANG"]["INBOX"],
        ),
    );

    // Select all custom folders for the user.
    $sql = "SELECT * FROM {$PHORUM['pm_folders_table']} " .
           "WHERE user_id = $user_id ORDER BY foldername";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // Add them to the folderlist.
    if (mysqli_num_rows($res) > 0){
        while (($row = mysqli_fetch_assoc($res))) {
            $folders[$row["pm_folder_id"]] = array(
                'id' => $row["pm_folder_id"],
                'name' => $row["foldername"],
            );
        }
    }

    // Add the outgoing box.
    $folders[PHORUM_PM_OUTBOX] = array(
        'id'   => PHORUM_PM_OUTBOX,
        'name' => $PHORUM["DATA"]["LANG"]["SentItems"],
    );

    // Count messages if requested.
    if ($count_messages)
    {
        // Initialize counters.
        foreach ($folders as $id => $data) {
            $folders[$id]["total"] = $folders[$id]["new"] = 0;
        }

        // Collect count information.
        $sql = "SELECT pm_folder_id, special_folder, " .
               "count(*) as total, (count(*) - sum(read_flag)) as new " .
               "FROM {$PHORUM['pm_xref_table']}  " .
               "WHERE user_id = $user_id " .
               "GROUP BY pm_folder_id, special_folder";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

        // Add counters to the folderlist.
        if (mysqli_num_rows($res) > 0){
            while (($row = mysqli_fetch_assoc($res))) {
                $folder_id = $row["pm_folder_id"] ? $row["pm_folder_id"] : $row["special_folder"];
                // If there are stale messages, we do not want them
                // to create non-existant mailboxes in the list.
                if (isset($folders[$folder_id])) {
                    $folders[$folder_id]["total"] = $row["total"];
                    $folders[$folder_id]["new"] = $row["new"];
                }
            }
        }
    }

    return $folders;
}

/**
 * This function computes the number of private messages a user has
 * and returns both the total and the number unread.
 * @param folder - The folder to use. Either a special folder
 *                 (PHORUM_PM_INBOX or PHORUM_PM_OUTBOX), the
 *                 id of a user's custom folder or
 *                 PHORUM_PM_ALLFOLDERS for all folders.
 * @param user_id - The user to retrieve messages for or NULL
 *                 to use the current user (default).
 */

function phorum_db_pm_messagecount($folder, $user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");

    if (is_numeric($folder)) {
        $folder_sql = "pm_folder_id=$folder AND";
    } elseif ($folder == PHORUM_PM_INBOX || $folder == PHORUM_PM_OUTBOX) {
        $folder_sql = "pm_folder_id=0 AND special_folder='$folder' AND";
    } elseif ($folder == PHORUM_PM_ALLFOLDERS) {
        $folder_sql = '';
    } else {
        die ("Illegal folder '$folder' requested for user id '$user_id'");
    }

    $sql = "SELECT count(*) as total, (count(*) - sum(read_flag)) as new " .
           "FROM {$PHORUM['pm_xref_table']}  " .
           "WHERE $folder_sql user_id = $user_id";

    $messagecount=array("total" => 0, "new" => 0);

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        $messagecount["total"] = $row["total"];
        $messagecount["new"] = ($row["new"] >= 1) ? $row["new"] : 0;
    }

    return $messagecount;
}

/**
 * This function does a quick check if the user has new private messages.
 * This is useful in case you only want to know whether the user has
 * new messages or not and when you are not interested in the exact amount
 * of new messages.
 *
 * @param user_id - The user to retrieve messages for or NULL
 *                 to use the current user (default).
 * @return A true value, in case there are new messages available.
 */
function phorum_db_pm_checknew($user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");

    $sql = "SELECT user_id " .
           "FROM {$PHORUM['pm_xref_table']} " .
           "WHERE user_id = $user_id AND read_flag = 0 LIMIT 1";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    return mysqli_num_rows($res);
}

/**
 * This function inserts a private message in the database. The return value
 * is the pm_message_id of the created message.
 * @param subject - The subject for the private message.
 * @param message - The message text for the private message.
 * @param to - A single user_id or an array of user_ids for the recipients.
 * @param from - The user_id of the sender. The current user is used in case
 *               the parameter is set to NULL (default).
 * @param keepcopy - If set to a true value, a copy of the mail will be put in
 *                   the outbox of the user. Default value is false.
 */
function phorum_db_pm_send($subject, $message, $to, $from=NULL, $keepcopy=false)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    // Prepare the sender.
    if ($from == NULL) $from = $PHORUM['user']['user_id'];
    settype($from, "int");
    $fromuser = phorum_db_user_get($from, false);
    if (! $fromuser) die("Unknown sender user_id '$from'");

    // This array will be filled with xref database entries.
    $xref_entries = array();

    // Prepare the list of recipients.
    $rcpts = array();
    if (! is_array($to)) $to = array($to);
    foreach ($to as $user_id) {
        settype($user_id, "int");
        $user = phorum_db_user_get($user_id, false);
        if (! $user) die("Unknown recipient user_id '$user_id'");
        $rcpts[$user_id] = array(
            'user_id' => $user_id,
            'username' => $user["username"],
            'read_flag' => 0,
        );
        $xref_entries[] = array(
            'user_id' => $user_id,
            'pm_folder_id' => 0,
            'special_folder' => PHORUM_PM_INBOX,
            'read_flag' => 0,
        );
    }

    // Keep copy of this message in outbox?
    if ($keepcopy) {
        $xref_entries[] = array(
            'user_id' => $from,
            'pm_folder_id' => 0,
            'special_folder' => PHORUM_PM_OUTBOX,
            'read_flag' => 1,
        );
    }

    // Prepare message meta data.
    $meta = mysqli_real_escape_string($conn, serialize(array(
        'recipients' => $rcpts
    )));

    // Create the message.
    $sql = "INSERT INTO {$PHORUM["pm_messages_table"]} SET " .
           "from_user_id = $from, " .
           "from_username = '".mysqli_real_escape_string($conn, $fromuser["username"])."', " .
           "subject = '".mysqli_real_escape_string($conn, $subject)."', " .
           "message = '".mysqli_real_escape_string($conn, $message)."', " .
           "datestamp = '".time()."', " .
           "meta = '$meta'";
    mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) {
        phorum_db_mysqli_error("$err: $sql");
        return;
    }

    // Get the message id.
    $pm_message_id = mysqli_insert_id($conn);

    // Put the message in the recipient inboxes.
    foreach ($xref_entries as $xref) {
        $sql = "INSERT INTO {$PHORUM["pm_xref_table"]} SET " .
               "user_id = {$xref["user_id"]}, " .
               "pm_folder_id={$xref["pm_folder_id"]}, " .
               "special_folder='{$xref["special_folder"]}', " .
               "pm_message_id=$pm_message_id, " .
               "read_flag = {$xref["read_flag"]}, " .
               "reply_flag = 0";
        mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) {
            phorum_db_mysqli_error("$err: $sql");
            return;
        }

    }

    return $pm_message_id;
}

/**
 * This function updates a flag for a private message.
 * @param pm_id - The id of the message to update.
 * @param flag - The flag to update. Options are PHORUM_PM_READ_FLAG
 *               and PHORUM_PM_REPLY_FLAG.
 * @param value - The value for the flag (true or false).
 * @param user_id - The user to set a flag for or NULL
 *                 to use the current user (default).
 */
function phorum_db_pm_setflag($pm_id, $flag, $value, $user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($pm_id, "int");

    if ($flag != PHORUM_PM_READ_FLAG && $flag != PHORUM_PM_REPLY_FLAG) {
        trigger_error("Invalid value for \$flag in function phorum_db_pm_setflag(): $flag", E_USER_WARNING);
        return 0;
    }

    $value = $value ? 1 : 0;

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");

    // Update the flag in the database.
    $sql = "UPDATE {$PHORUM["pm_xref_table"]} " .
           "SET $flag = $value " .
           "WHERE pm_message_id = $pm_id AND user_id = $user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // Update message counters.
    if ($flag == PHORUM_PM_READ_FLAG) {
        phorum_db_pm_update_message_info($pm_id);
    }

    return $res;
}

/**
 * This function deletes a private message from a folder.
 * @param folder - The folder from which to delete the message
 * @param pm_id - The id of the private message to delete
 * @param user_id - The user to delete the message for or NULL
 *                 to use the current user (default).
 */
function phorum_db_pm_delete($pm_id, $folder, $user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($pm_id, "int");

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");

    if (is_numeric($folder)) {
        $folder_sql = "pm_folder_id=$folder AND";
    } elseif ($folder == PHORUM_PM_INBOX || $folder == PHORUM_PM_OUTBOX) {
        $folder_sql = "pm_folder_id=0 AND special_folder='$folder' AND";
    } else {
        die ("Illegal folder '$folder' requested for user id '$user_id'");
    }

    $sql = "DELETE FROM {$PHORUM["pm_xref_table"]} " .
           "WHERE $folder_sql " .
           "user_id = $user_id AND pm_message_id = $pm_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // Update message counters.
    phorum_db_pm_update_message_info($pm_id);

    return $res;
}

/**
 * This function moves a private message to a different folder.
 * @param pm_id - The id of the private message to move.
 * @param from - The folder to move the message from.
 * @param to - The folder to move the message to.
 * @param user_id - The user to move the message for or NULL
 *                 to use the current user (default).
 */
function phorum_db_pm_move($pm_id, $from, $to, $user_id = NULL)
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    settype($pm_id, "int");

    if ($user_id == NULL) $user_id = $PHORUM['user']['user_id'];
    settype($user_id, "int");

    if (is_numeric($from)) {
        $folder_sql = "pm_folder_id=$from AND";
    } elseif ($from == PHORUM_PM_INBOX || $from == PHORUM_PM_OUTBOX) {
        $folder_sql = "pm_folder_id=0 AND special_folder='$from' AND";
    } else {
        die ("Illegal source folder '$from' specified");
    }

    if (is_numeric($to)) {
        $pm_folder_id = $to;
        $special_folder = 'NULL';
    } elseif ($to == PHORUM_PM_INBOX || $to == PHORUM_PM_OUTBOX) {
        $pm_folder_id = 0;
        $special_folder = "'$to'";
    } else {
        die ("Illegal target folder '$to' specified");
    }

    $sql = "UPDATE {$PHORUM["pm_xref_table"]} SET " .
           "pm_folder_id = $pm_folder_id, " .
           "special_folder = $special_folder " .
           "WHERE $folder_sql user_id = $user_id AND pm_message_id = $pm_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    return $res;
}

/**
 * This function updates the meta information for a message. If it
 * detects that no xrefs are available for the message anymore,
 * the message will be deleted from the database. So this function
 * has to be called after setting the read_flag and after deleting
 * a message.
 * PMTODO maybe we need some locking here to prevent concurrent
 * updates of the message info.
 */
function phorum_db_pm_update_message_info($pm_id)
{
    $PHORUM = $GLOBALS['PHORUM'];

    $conn = phorum_db_mysqli_connect();

    settype($pm_id, "int");

    // Find the message record. Return immediately if no message is found.
    $sql = "SELECT * " .
           "FROM {$PHORUM['pm_messages_table']} " .
           "WHERE pm_message_id = $pm_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    if (mysqli_num_rows($res) == 0) return $res;
    $pm = mysqli_fetch_assoc($res);

    // Find the xrefs for this message.
    $sql = "SELECT * " .
           "FROM {$PHORUM["pm_xref_table"]} " .
           "WHERE pm_message_id = $pm_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    // No xrefs left? Then the message can be fully deleted.
    if (mysqli_num_rows($res) == 0) {
        $sql = "DELETE FROM {$PHORUM['pm_messages_table']} " .
               "WHERE pm_message_id = $pm_id";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
        return $res;
    }

    // Update the read flags for the recipients in the meta data.
    $meta = unserialize($pm["meta"]);
    $rcpts = $meta["recipients"];
    while ($row = mysqli_fetch_assoc($res)) {
        // Only update if available. A kept copy in the outbox will
        // not be in the meta list, so if the copy is read, the
        // meta data does not have to be updated here.
        if (isset($rcpts[$row["user_id"]])) {
            $rcpts[$row["user_id"]]["read_flag"] = $row["read_flag"];
        }
    }
    $meta["recipients"] = $rcpts;

    // Store the new meta data.
    $meta = mysqli_real_escape_string($conn, serialize($meta));
    $sql = "UPDATE {$PHORUM['pm_messages_table']} " .
           "SET meta = '$meta' " .
           "WHERE pm_message_id = $pm_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    return $res;
}

/* Take care of warning about deprecation of the old PM API functions. */
function phorum_db_get_private_messages($arg1, $arg2) {
    phorum_db_pm_deprecated('phorum_db_get_private_messages'); }
function phorum_db_get_private_message($arg1) {
    phorum_db_pm_deprecated('phorum_db_get_private_message'); }
function phorum_db_get_private_message_count($arg1) {
    phorum_db_pm_deprecated('phorum_db_get_private_message_count'); }
function phorum_db_put_private_messages($arg1, $arg2, $arg3, $arg4, $arg5) {
    phorum_db_pm_deprecated('phorum_db_put_private_messages'); }
function phorum_db_update_private_message($arg1, $arg2, $arg3){
    phorum_db_pm_deprecated('phorum_db_update_private_message'); }
function phorum_db_pm_deprecated($func) {
    die("${func}() has been deprecated. Please use the new private message API.");
}

/**
 * This function checks if a certain user is buddy of another user.
 * The function return the pm_buddy_id in case the user is a buddy
 * or NULL in case the user isn't.
 * @param buddy_user_id - The user_id to check for if it's a buddy.
 * @param user_id - The user_id for which the buddy list must be
 *                  checked or NULL to use the current user (default).
 */
function phorum_db_pm_is_buddy($buddy_user_id, $user_id = NULL)
{
    $PHORUM = $GLOBALS['PHORUM'];
    $conn = phorum_db_mysqli_connect();
    settype($buddy_user_id, "int");
    if (is_null($user_id)) $user_id = $PHORUM["user"]["user_id"];
    settype($user_id, "int");

    $sql = "SELECT pm_buddy_id FROM {$PHORUM["pm_buddies_table"]} " .
           "WHERE user_id = $user_id AND buddy_user_id = $buddy_user_id";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    if (mysqli_num_rows($res)) {
        $row = mysqli_fetch_array($res);
        return $row[0];
    } else {
        return NULL;
    }
}

/**
 * This function adds a buddy for a user. It will return the
 * pm_buddy_id for the new buddy. If the buddy already exists,
 * it will return the existing pm_buddy_id. If a non existant
 * user_id is used for the buddy_user_id, the function will
 * return NULL.
 * @param buddy_user_id - The user_id that has to be added as a buddy.
 * @param user_id - The user_id the buddy has to be added for or
 *                  NULL to use the current user (default).
 */
function phorum_db_pm_buddy_add($buddy_user_id, $user_id = NULL)
{
    $PHORUM = $GLOBALS['PHORUM'];
    $conn = phorum_db_mysqli_connect();
    settype($buddy_user_id, "int");
    if (is_null($user_id)) $user_id = $PHORUM["user"]["user_id"];
    settype($user_id, "int");

    // Check if the buddy_user_id is a valid user_id.
    $valid = phorum_db_user_get($buddy_user_id, false);
    if (! $valid) return NULL;

    $pm_buddy_id = phorum_db_pm_is_buddy($buddy_user_id);
    if (is_null($pm_buddy_id)) {
        $sql = "INSERT INTO {$PHORUM["pm_buddies_table"]} SET " .
               "user_id = $user_id, " .
               "buddy_user_id = $buddy_user_id";
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
        $pm_buddy_id = mysqli_insert_id($conn);
    }

    return $pm_buddy_id;
}

/**
 * This function deletes a buddy for a user.
 * @param buddy_user_id - The user_id that has to be deleted as a buddy.
 * @param user_id - The user_id the buddy has to be delete for or
 *                  NULL to use the current user (default).
 */
function phorum_db_pm_buddy_delete($buddy_user_id, $user_id = NULL)
{
    $PHORUM = $GLOBALS['PHORUM'];
    $conn = phorum_db_mysqli_connect();
    settype($buddy_user_id, "int");
    if (is_null($user_id)) $user_id = $PHORUM["user"]["user_id"];
    settype($user_id, "int");

    $sql = "DELETE FROM {$PHORUM["pm_buddies_table"]} WHERE " .
           "buddy_user_id = $buddy_user_id AND user_id = $user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    return $res;
}

/**
 * This function retrieves a list of buddies for a user.
 * @param user_id - The user_id for which to retrieve the buddies
 *                  or NULL to user the current user (default).
 * @param find_mutual - Wheter to find mutual buddies or not (default not).
 */
function phorum_db_pm_buddy_list($user_id = NULL, $find_mutual = false)
{
    $PHORUM = $GLOBALS['PHORUM'];
    $conn = phorum_db_mysqli_connect();
    if (is_null($user_id)) $user_id = $PHORUM["user"]["user_id"];
    settype($user_id, "int");

    // Get all buddies for this user.
    $sql = "SELECT buddy_user_id FROM {$PHORUM["pm_buddies_table"]} " .
           "WHERE user_id = $user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $buddies = array();
    if (mysqli_num_rows($res)) {
        while ($row = mysqli_fetch_array($res)) {
            $buddies[$row[0]] = array (
                'user_id' => $row[0]
            );
        }
    }

    // If we do not have to lookup mutual buddies, we're done.
    if (! $find_mutual) return $buddies;

    // Initialize mutual buddy value.
    foreach ($buddies as $id => $data) {
        $buddies[$id]["mutual"] = false;
    }

    // Get all mutual buddies.
    $sql = "SELECT DISTINCT a.buddy_user_id " .
           "FROM {$PHORUM["pm_buddies_table"]} as a, {$PHORUM["pm_buddies_table"]} as b " .
           "WHERE a.user_id=$user_id " .
           "AND b.user_id=a.buddy_user_id " .
           "AND b.buddy_user_id=$user_id";
    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res)) {
        while ($row = mysqli_fetch_array($res)) {
            $buddies[$row[0]]["mutual"] = true;
        }
    }

    return $buddies;
}

/**
* This function returns messages or threads which are newer or older
* than the given timestamp
*
* $time  - holds the timestamp the comparison is done against
* $forum - get Threads from this forum
* $mode  - should we compare against datestamp (1) or modifystamp (2)
*
*/
function phorum_db_prune_oldThreads($time,$forum=0,$mode=1) {

    $PHORUM = $GLOBALS['PHORUM'];

    $conn = phorum_db_mysqli_connect();
    $numdeleted=0;

    settype($time, "int");
    settype($forum, "int");
    settype($mode, "int");

    $compare_field = "datestamp";
    if($mode == 2) {
      $compare_field = "modifystamp";
    }

    $forummode="";
    if($forum > 0) {
      $forummode=" AND forum_id = $forum";
    }

    // retrieving which threads to delete
    $sql = "select thread from {$PHORUM['message_table']} where $compare_field < $time AND parent_id=0 $forummode";

    $res = mysqli_query( $conn, $sql);
    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    $ret=array();
    while($row=mysqli_fetch_row($res)) {
        $ret[]=$row[0];
    }

    $thread_ids=implode(",",$ret);

    if(count($ret)) {
      // deleting the messages/threads
      $sql="delete from {$PHORUM['message_table']} where thread IN ($thread_ids)";
      $res = mysqli_query( $conn, $sql);
      if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

      $numdeleted = mysqli_affected_rows($conn);
      if($numdeleted < 0) {
        $numdeleted=0;
      }

      // deleting the associated notification-entries
      $sql="delete from {$PHORUM['subscribers_table']} where thread IN ($thread_ids)";
      $res = mysqli_query( $conn, $sql);
      if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");


      // optimizing the message-table
      $sql="optimize table {$PHORUM['message_table']}";
      $res = mysqli_query( $conn, $sql);
      if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");
    }

    return $numdeleted;
}

/**
 * split thread
 */
function phorum_db_split_thread($message, $forum_id)
{
    settype($message, "int");
    settype($forum_id, "int");

    if($message > 0 && $forum_id > 0){
        // get message tree for update thread id
        $tree =phorum_db_get_messagetree($message, $forum_id);
        $queries =array();
        $queries[0]="UPDATE {$GLOBALS['PHORUM']['message_table']} SET thread='$message', parent_id='0' WHERE message_id ='$message'";
        $queries[1]="UPDATE {$GLOBALS['PHORUM']['message_table']} SET thread='$message' WHERE message_id IN ($tree)";
        phorum_db_run_queries($queries);
    }
}

/**
 * This function returns the maximum message-id in the database
 */
function phorum_db_get_max_messageid() {
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();
    $maxid = 0;

    $sql="SELECT max(message_id) from ".$PHORUM["message_table"];
    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if (mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_row($res);
        $maxid = $row[0];
    }

    return $maxid;
}

/**
 * This function increments the viewcount for a post
 */

function phorum_db_viewcount_inc($message_id) {
    if($message_id < 1 || !is_numeric($message_id)) {
        return false;
    }

    $conn = phorum_db_mysqli_connect();
    $sql="UPDATE ".$GLOBALS['PHORUM']['message_table']." SET viewcount=viewcount+1 WHERE message_id=$message_id";
    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");


    return true;

}


function phorum_db_get_custom_field_users($field_id,$field_content,$match) {

    $conn = phorum_db_mysqli_connect();

    $field_id=(int)$field_id;
    $field_content=mysqli_real_escape_string($conn, $field_content);

    if($match) {
        $compval="LIKE";
    } else {
        $compval="=";
    }

    $sql = "select user_id from {$GLOBALS['PHORUM']['user_custom_fields_table']} where type=$field_id and data $compval '$field_content'";
    $res = mysqli_query( $conn, $sql);

    if ($err = mysqli_error($conn)) phorum_db_mysqli_error("$err: $sql");

    if(mysqli_num_rows($res)) {
        $retval=array();
        while ($row = mysqli_fetch_row($res)){
            $retval[$row[0]]=$row[0];
        }
    } else {
        $retval=NULL;
    }

    return $retval;

}


/**
 * Translates a message searching meta query into a real SQL WHERE
 * statement for this database backend. The meta query can be used to
 * define extended SQL queries, based on a meta description of the
 * search that has to be performed on the database.
 *
 * The meta query is an array, containing:
 * - query conditions
 * - grouping using "(" and ")"
 * - AND/OR specifications using "AND" and "OR".
 *
 * The query conditions are arrays, containing the following elements:
 *
 * - condition
 *
 *   A description of a condition. The syntax for this is:
 *   <field name to query> <operator> <match specification>
 *
 *   The <field name to query> is a field in the message query that
 *   we are running in this function.
 *
 *   The <operator> can be one of "=", "!=", "<", "<=", ">", ">=".
 *   Note that there is nothing like "LIKE" or "NOT LIKE". If a "LIKE"
 *   query has to be done, then that is setup through the
 *   <match specification> (see below).
 *
 *   The <match specification> tells us with what the field should be
 *   matched. The string "QUERY" inside the specification is preserved to
 *   specify at which spot in the query the "query" element from the
 *   condition array should be inserted. If "QUERY" is not available in
 *   the specification, then a match is made on the exact value in the
 *   specification. To perform "LIKE" searches (case insensitive wildcard
 *   searches), you can use the "*" wildcard character in the specification
 *   to do so.
 *
 * - query
 *
 *   The data to use in the query, in case the condition element has a
 *   <match specification> that uses "QUERY" in it.
 *
 * Example:
 *
 * $metaquery = array(
 *     array(
 *         "condition"  =>  "field1 = *QUERY*",
 *         "query"      =>  "test data"
 *     ),
 *     "AND",
 *     "(",
 *     array("condition"  => "field2 = whatever"),
 *     "OR",
 *     array("condition"  => "field2 = something else"),
 *     ")"
 * );
 *
 * For MySQL, this would be turned into the MySQL WHERE statement:
 * ... WHERE field1 LIKE '%test data%'
 *     AND (field2 = 'whatever' OR field2 = 'something else')
 *
 * @param $metaquery - A meta query description array.
 * @return $return - An array containing two elements. The first element
 *                   is either true or false, based on the success state
 *                   of the function call (false means that there was an
 *                   error). The second argument contains either a
 *                   WHERE statement or an error message.
 */
function phorum_db_metaquery_compile($metaquery)
{
    $where = '';

    $expect_condition  = true;
    $expect_groupstart = true;
    $expect_groupend   = false;
    $expect_combine    = false;
    $in_group          = 0;

    $conn = phorum_db_mysqli_connect();

    foreach ($metaquery as $part)
    {
        // Found a new condition.
        if ($expect_condition && is_array($part))
        {
            $cond = trim($part["condition"]);
            if (preg_match('/^([\w_\.]+)\s+(!?=|<=?|>=?)\s+(\S*)$/', $cond, $m))
            {
                $field = $m[1];
                $comp  = $m[2];
                $match = $m[3];

                $matchtokens = preg_split(
                    '/(\*|QUERY|NULL)/',
                    $match, -1,
                    PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY
                );

                $matchsql = "'";
                $is_like_query = false;
                foreach ($matchtokens as $m) {
                    if ($m == '*') {
                        $is_like_query = true;
                        $matchsql .= '%';
                    } elseif ($m == 'QUERY') {
                        $matchsql .= mysqli_real_escape_string($conn, $part["query"]);
                    } else {
                        $matchsql .= mysqli_real_escape_string($conn, $m);
                    }
                }
                $matchsql .= "'";

                if ($is_like_query)
                {
                    if ($comp == '=') { $comp = ' LIKE '; }
                    elseif ($comp == '!=') { $comp = ' NOT LIKE '; }
                    else return array(
                        false,
                        "Illegal metaquery token " . htmlspecialchars($cond) .
                        ": wildcard match does not combine with $comp operator"
                    );
                }

                $where .= "$field $comp $matchsql ";
            } else {
                return array(
                    false,
                    "Illegal metaquery token " . htmlspecialchars($cond) .
                    ": condition does not match the required format"
                );
            }

            $expect_condition   = false;
            $expect_groupstart  = false;
            $expect_groupend    = $in_group;
            $expect_combine     = true;
        }
        // Found a new group start.
        elseif ($expect_groupstart && $part == '(')
        {
            $where .= "(";
            $in_group ++;

            $expect_condition   = true;
            $expect_groupstart  = false;
            $expect_groupend    = false;
            $expect_combine     = false;
        }
        // Found a new group end.
        elseif ($expect_groupend && $part == ')')
        {
            $where .= ") ";
            $in_group --;

            $expect_condition   = false;
            $expect_groupstart  = false;
            $expect_groupend    = $in_group;
            $expect_combine     = true;
        }
        // Found a combine token (AND or OR).
        elseif ($expect_combine && preg_match('/^(OR|AND)$/i', $part, $m))
        {
            $where .= strtoupper($m[1]) . " ";

            $expect_condition   = true;
            $expect_groupstart  = true;
            $expect_groupend    = false;
            $expect_combine     = false;
        }
        // Unexpected or illegal token.
        else
        {
            die("Internal error: unexpected token in metaquery description: " .
                (is_array($part) ? "condition" : htmlspecialchars($part)));
        }
    }

    if ($expect_groupend) die ("Internal error: unclosed group in metaquery");

    // If the metaquery is empty, then provide a safe true WHERE statement.
    if ($where == '') { $where = "1 = 1"; }

    return array(true, $where);
}

/**
 * Run a search on the messages, using a metaquery. See the documentation
 * for the phorum_db_metaquery_compile() function for more info on the
 * metaquery syntax.
 *
 * The query that is run here, does create a view on the messages, which
 * includes some thread and user info. This is used so these can also
 * be taken into account when selecting messages. For the condition elements
 * in the meta query, you can use fully qualified field names for the
 * <field name to query>. You can use message.*, user.* and thread.* for this.
 *
 * The primary goal for this function is to provide a backend for the
 * message pruning interface.
 *
 * @param $metaquery - A metaquery array.
 * @return $messages - An array of message records.
 */
function phorum_db_metaquery_messagesearch($metaquery)
{
    $PHORUM = $GLOBALS["PHORUM"];

    // Compile the metaquery into a where statement.
    list($success, $where) = phorum_db_metaquery_compile($metaquery);
    if (!$success) die($where);

    // Build the SQL query.
    $sql = "
      SELECT message.message_id,
             message.thread,
             message.parent_id,
             message.forum_id,
             message.subject,
             message.author,
             message.datestamp,
             message.body,
             message.ip,
             message.status,
             message.user_id,
             user.username       user_username,
             thread.closed       thread_closed,
             thread.modifystamp  thread_modifystamp,
             thread.thread_count thread_count
      FROM   {$PHORUM["message_table"]} as thread,
             {$PHORUM["message_table"]} as message
                 LEFT JOIN {$PHORUM["user_table"]} user
                 ON message.user_id = user.user_id
      WHERE  message.thread  = thread.message_id AND
             ($where)
      ORDER BY message_id ASC
    ";

    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query($conn, $sql);
    if ($err = mysqli_error()) {
        phorum_db_mysqli_error("$err: $sql");
        return NULL;
    } else {
        $messages = array();
        if(mysqli_num_rows($res)) {
            while ($row = mysqli_fetch_assoc($res)) {
                $messages[$row["message_id"]] = $row;
            }
        }
        return $messages;
    }
}


/**
 * This function creates the tables needed in the database.
 */

function phorum_db_create_tables()
{
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    $retmsg = "";

    $queries = array(

        // create tables
        "CREATE TABLE {$PHORUM['forums_table']} ( forum_id int(10) unsigned NOT NULL auto_increment, name varchar(50) NOT NULL default '', active smallint(6) NOT NULL default '0', description text NOT NULL default '', template varchar(50) NOT NULL default '', folder_flag tinyint(1) NOT NULL default '0', parent_id int(10) unsigned NOT NULL default '0', list_length_flat int(10) unsigned NOT NULL default '0', list_length_threaded int(10) unsigned NOT NULL default '0', moderation int(10) unsigned NOT NULL default '0', threaded_list tinyint(4) NOT NULL default '0', threaded_read tinyint(4) NOT NULL default '0', float_to_top tinyint(4) NOT NULL default '0', check_duplicate tinyint(4) NOT NULL default '0', allow_attachment_types varchar(100) NOT NULL default '', max_attachment_size int(10) unsigned NOT NULL default '0', max_totalattachment_size int(10) unsigned NOT NULL default '0', max_attachments int(10) unsigned NOT NULL default '0', pub_perms int(10) unsigned NOT NULL default '0', reg_perms int(10) unsigned NOT NULL default '0', display_ip_address smallint(5) unsigned NOT NULL default '1', allow_email_notify smallint(5) unsigned NOT NULL default '1', language varchar(100) NOT NULL default 'english', email_moderators tinyint(1) NOT NULL default '0', message_count int(10) unsigned NOT NULL default '0', sticky_count int(10) unsigned NOT NULL default '0', thread_count int(10) unsigned NOT NULL default '0', last_post_time int(10) unsigned NOT NULL default '0', display_order int(10) unsigned NOT NULL default '0', read_length int(10) unsigned NOT NULL default '0', vroot int(10) unsigned NOT NULL default '0', edit_post tinyint(1) NOT NULL default '1',template_settings text NOT NULL default '', count_views tinyint(1) unsigned NOT NULL default '0', display_fixed tinyint(1) unsigned NOT NULL default '0', reverse_threading tinyint(1) NOT NULL default '0',inherit_id int(10) unsigned NULL default NULL, PRIMARY KEY (forum_id), KEY name (name), KEY active (active,parent_id), KEY group_id (parent_id)) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['message_table']} ( message_id int(10) unsigned NOT NULL auto_increment, forum_id int(10) unsigned NOT NULL default '0', thread int(10) unsigned NOT NULL default '0', parent_id int(10) unsigned NOT NULL default '0', author varchar(37) NOT NULL default '', subject varchar(255) NOT NULL default '', body text NOT NULL, email varchar(100) NOT NULL default '', ip varchar(255) NOT NULL default '', status tinyint(4) NOT NULL default '2', msgid varchar(100) NOT NULL default '', modifystamp int(10) unsigned NOT NULL default '0', user_id int(10) unsigned NOT NULL default '0', thread_count int(10) unsigned NOT NULL default '0', moderator_post tinyint(3) unsigned NOT NULL default '0', sort tinyint(4) NOT NULL default '2', datestamp int(10) unsigned NOT NULL default '0', meta mediumtext NOT NULL, viewcount int(10) unsigned NOT NULL default '0', closed tinyint(4) NOT NULL default '0', PRIMARY KEY (message_id), KEY thread_message (thread,message_id), KEY thread_forum (thread,forum_id), KEY special_threads (sort,forum_id), KEY status_forum (status,forum_id), KEY list_page_float (forum_id,parent_id,modifystamp), KEY list_page_flat (forum_id,parent_id,thread), KEY post_count (forum_id,status,parent_id), KEY dup_check (forum_id,author,subject,datestamp), KEY forum_max_message (forum_id,message_id,status,parent_id), KEY last_post_time (forum_id,status,modifystamp), KEY next_prev_thread (forum_id,status,thread), KEY user_id (user_id)  ) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['settings_table']} ( name varchar(255) NOT NULL default '', type enum('V','S') NOT NULL default 'V', data text NOT NULL, PRIMARY KEY (name)) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['subscribers_table']} ( user_id int(10) unsigned NOT NULL default '0', forum_id int(10) unsigned NOT NULL default '0', sub_type int(10) unsigned NOT NULL default '0', thread int(10) unsigned NOT NULL default '0', PRIMARY KEY (user_id,forum_id,thread), KEY forum_id (forum_id,thread,sub_type)) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['user_permissions_table']} ( user_id int(10) unsigned NOT NULL default '0', forum_id int(10) unsigned NOT NULL default '0', permission int(10) unsigned NOT NULL default '0', PRIMARY KEY  (user_id,forum_id), KEY forum_id (forum_id,permission) ) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['user_table']} ( user_id int(10) unsigned NOT NULL auto_increment, username varchar(50) NOT NULL default '', password varchar(50) NOT NULL default '',cookie_sessid_lt varchar(50) NOT NULL default '', sessid_st varchar(50) NOT NULL default '', sessid_st_timeout int(10) unsigned NOT NULL default 0, password_temp varchar(50) NOT NULL default '', email varchar(100) NOT NULL default '',  email_temp varchar(110) NOT NULL default '', hide_email tinyint(1) NOT NULL default '0', active tinyint(1) NOT NULL default '0', user_data text NOT NULL default '', signature text NOT NULL default '', threaded_list tinyint(4) NOT NULL default '0', posts int(10) NOT NULL default '0', admin tinyint(1) NOT NULL default '0', threaded_read tinyint(4) NOT NULL default '0', date_added int(10) unsigned NOT NULL default '0', date_last_active int(10) unsigned NOT NULL default '0', last_active_forum int(10) unsigned NOT NULL default '0', hide_activity tinyint(1) NOT NULL default '0',show_signature TINYINT( 1 ) DEFAULT '0' NOT NULL, email_notify TINYINT( 1 ) DEFAULT '0' NOT NULL, pm_email_notify TINYINT ( 1 ) DEFAULT '1' NOT NULL, tz_offset TINYINT( 2 ) DEFAULT '-99' NOT NULL,is_dst TINYINT( 1 ) DEFAULT '0' NOT NULL ,user_language VARCHAR( 100 ) NOT NULL default '',user_template VARCHAR( 100 ) NOT NULL default '', moderator_data text NOT NULL default '', moderation_email tinyint(2) unsigned not null default 1, PRIMARY KEY (user_id), UNIQUE KEY username (username), KEY active (active), KEY userpass (username,password), KEY sessid_st (sessid_st), KEY cookie_sessid_lt (cookie_sessid_lt), KEY activity (date_last_active,hide_activity,last_active_forum), KEY date_added (date_added), KEY email_temp (email_temp) ) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['user_newflags_table']} ( user_id int(11) NOT NULL default '0', forum_id int(11) NOT NULL default '0', message_id int(11) NOT NULL default '0', PRIMARY KEY  (user_id,forum_id,message_id) ) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['groups_table']} ( group_id int(11) NOT NULL auto_increment, name varchar(255) NOT NULL default '0', open tinyint(3) NOT NULL default '0', PRIMARY KEY  (group_id) ) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['forum_group_xref_table']} ( forum_id int(11) NOT NULL default '0', group_id int(11) NOT NULL default '0', permission int(10) unsigned NOT NULL default '0', PRIMARY KEY  (forum_id,group_id), KEY group_id (group_id) ) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['user_group_xref_table']} ( user_id int(11) NOT NULL default '0', group_id int(11) NOT NULL default '0', status tinyint(3) NOT NULL default '1', PRIMARY KEY  (user_id,group_id) ) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['files_table']} ( file_id int(11) NOT NULL auto_increment, user_id int(11) NOT NULL default '0', filename varchar(255) NOT NULL default '', filesize int(11) NOT NULL default '0', file_data mediumtext NOT NULL default '', add_datetime int(10) unsigned NOT NULL default '0', message_id int(10) unsigned NOT NULL default '0', link varchar(10) NOT NULL default '', PRIMARY KEY (file_id), KEY add_datetime (add_datetime), KEY message_id_link (message_id,link)) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['banlist_table']} ( id int(11) NOT NULL auto_increment, forum_id int(11) NOT NULL default '0', type tinyint(4) NOT NULL default '0', pcre tinyint(4) NOT NULL default '0', string varchar(255) NOT NULL default '', PRIMARY KEY  (id), KEY forum_id (forum_id)) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['search_table']} ( message_id int(10) unsigned NOT NULL default '0', forum_id int(10) unsigned NOT NULL default '0',search_text mediumtext NOT NULL default '', PRIMARY KEY  (message_id), KEY forum_id (forum_id), FULLTEXT KEY search_text (search_text) ) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['user_custom_fields_table']} ( user_id INT DEFAULT '0' NOT NULL , type INT DEFAULT '0' NOT NULL , data TEXT NOT NULL default '', PRIMARY KEY ( user_id , type )) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['pm_messages_table']} ( pm_message_id int(10) unsigned NOT NULL auto_increment, from_user_id int(10) unsigned NOT NULL default '0', from_username varchar(50) NOT NULL default '', subject varchar(100) NOT NULL default '', message text NOT NULL default '', datestamp int(10) unsigned NOT NULL default '0', meta mediumtext NOT NULL default '', PRIMARY KEY(pm_message_id)) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['pm_folders_table']} ( pm_folder_id int(10) unsigned NOT NULL auto_increment, user_id int(10) unsigned NOT NULL default '0', foldername varchar(20) NOT NULL default '', PRIMARY KEY (pm_folder_id)) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['pm_xref_table']} ( pm_xref_id int(10) unsigned NOT NULL auto_increment, user_id int(10) unsigned NOT NULL default '0', pm_folder_id int(10) unsigned NOT NULL default '0', special_folder varchar(10), pm_message_id int(10) unsigned NOT NULL default '0', read_flag tinyint(1) NOT NULL default '0', reply_flag tinyint(1) NOT NULL default '0', PRIMARY KEY (pm_xref_id), KEY xref (user_id,pm_folder_id,pm_message_id), KEY read_flag (read_flag)) TYPE=MyISAM",
        "CREATE TABLE {$PHORUM['pm_buddies_table']} ( pm_buddy_id int(10) unsigned NOT NULL auto_increment, user_id int(10) unsigned NOT NULL default '0', buddy_user_id int(10) unsigned NOT NULL default '0', PRIMARY KEY pm_buddy_id (pm_buddy_id), UNIQUE KEY userids (user_id, buddy_user_id), KEY buddy_user_id (buddy_user_id)) TYPE=MyISAM",

    );
    foreach($queries as $sql){
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)){
            $retmsg = "$err<br />";
            phorum_db_mysqli_error("$err: $sql");
            break;
        }
    }

    return $retmsg;
}

// uses the database-dependant functions to escape a string
function phorum_db_escape_string($str) {

    $conn = phorum_db_mysqli_connect();

    $str_tmp=mysqli_real_escape_string($conn, $str);

    return $str_tmp;
}

/**
 * This function goes through an array of queries and executes them
 */

function phorum_db_run_queries($queries){
    $PHORUM = $GLOBALS["PHORUM"];

    $conn = phorum_db_mysqli_connect();

    $retmsg = "";

    foreach($queries as $sql){
        $res = mysqli_query( $conn, $sql);
        if ($err = mysqli_error($conn)){
            // skip duplicate column name errors
            if(!stristr($err, "duplicate column")){
                $retmsg.= "$err<br />";
                phorum_db_mysqli_error("$err: $sql");
            }
        }
    }

    return $retmsg;
}

/**
 * This function checks that a database connection can be made.
 */

function phorum_db_check_connection(){
    $conn = @phorum_db_mysqli_connect();

    return ($conn) ? true : false;
}

/**
 * handy little connection function.  This allows us to not connect to the
 * server until a query is actually run.
 * NOTE: This is not a required part of abstraction
 */

function phorum_db_mysqli_connect(){
    $PHORUM = $GLOBALS["PHORUM"];

    static $conn;
    if (empty($conn)){
        $conn = mysqli_connect($PHORUM["DBCONFIG"]["server"], $PHORUM["DBCONFIG"]["user"], $PHORUM["DBCONFIG"]["password"], $PHORUM["DBCONFIG"]["name"]);
    }
    return $conn;
}

/**
 * error handling function
 * NOTE: This is not a required part of abstraction
 */

function phorum_db_mysqli_error($err){

    if(isset($GLOBALS['PHORUM']['error_logging'])) {
        $logsetting = $GLOBALS['PHORUM']['error_logging'];
    } else {
        $logsetting = "";
    }
    $adminemail = $GLOBALS['PHORUM']['system_email_from_address'];
    $cache_dir  = $GLOBALS['PHORUM']['cache'];

    if (!defined("PHORUM_ADMIN")){
        if($logsetting == 'mail') {
            include_once("include/email_functions.php");

            $data=array('mailmessage'=>"An SQL-error occured in your phorum-installation.\n\nThe error-message was:\n$err\n\n",
                        'mailsubject'=>'Phorum: an SQL-error occured');
            phorum_email_user(array($adminemail),$data);

        } elseif($logsetting == 'file') {
            $fp = fopen($cache_dir."/phorum-sql-errors.log",'a');
            fputs($fp,time().": $err\n");
            fclose($fp);

        } else {
            echo htmlspecialchars($err);
        }
        exit();
    }else{
        echo "<!-- $err -->";
    }
}

/**
 * This function will sanitize a mixed variable of data based on type
 *
 * @param   $var    The variable to be sanitized.  Passed by reference.
 * @param   $type   Either int or not int.
 * @return  null
 *
 */
function phorum_db_sanitize_mixed(&$var, $type){

    $conn = phorum_db_mysqli_connect();

    if(is_array($var)){
        foreach($var as $id => $val){
            if($type=="int"){
                $var[$id] = (int)$val;
            } else {
                $var[$id] = mysqli_real_escape_string($conn, $val);
            }
        }
    } else {
        if($type=="int"){
            $var = (int)$var;
        } else {
            $var = mysqli_real_escape_string($conn, $var);
        }
    }
}

/**
 * Checks that a value to be used as a field name contains only characters
 * that would appear in a field name.
 *
 * @param   $field_name     string to be checked
 * @return  bool
 *
 */
function phorum_db_validate_field($field_name){
    return (bool)preg_match('!^[a-zA-Z0-9_]+$!', $field_name);
}


/**
 * This function is used by the sanity checking system in the
 * admin interface to determine how much data can be transferred
 * in one query. This is used to detect problems with uploads that
 * are larger than the database server can handle.
 * The function returns the size in bytes. For database implementations
 * which do not have this kind of limit, NULL can be returned.
 */
function phorum_db_maxpacketsize ()
{
    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query($conn, "SELECT @@global.max_allowed_packet");
    if (! $res) return NULL;
    if (mysqli_num_rows($res)) {
        $row = mysqli_fetch_array($res);
        return $row[0];
    }
    return NULL;
}

/**
 * This function is used by the sanity checking system to let the
 * database layer do sanity checks of its own. This function can
 * be used by every database layer to implement specific checks.
 *
 * The return value for this function should be exactly the same
 * as the return value expected for regular sanity checking
 * function (see include/admin/sanity_checks.php for information).
 *
 * There's no need to load the sanity_check.php file for the needed
 * constants, because this function should only be called from the
 * sanity checking system.
 */
function phorum_db_sanitychecks()
{
    $PHORUM = $GLOBALS["PHORUM"];

    // Retrieve the MySQL server version.
    $conn = phorum_db_mysqli_connect();
    $res = mysqli_query($conn, "SELECT @@global.version");
    if (!$res) return array(
        PHORUM_SANITY_WARN,
        "The database layer could not retrieve the version of the
         running MySQL server",
        "This probably means that you are running a really old MySQL
         server, which does not support \"SELECT @@global.version\"
         as an SQL command. If you are not running a MySQL server
         with version 4.0.18 or higher, then please upgrade your
         MySQL server. Else, contact the Phorum developers to see
         where this warning is coming from"
    );

    if (mysqli_num_rows($res))
    {
        $row = mysqli_fetch_array($res);
        $verstr = preg_replace('/-.*$/', '', $row[0]);
        $ver = explode(".", $verstr);

        // Version numbering format which is not recognized.
        if (count($ver) != 3) return array(
            PHORUM_SANITY_WARN,
            "The database layer was unable to recognize the MySQL server's
             version number \"" . htmlspecialchars($row[0]) . "\". Therefore,
             checking if the right version of MySQL is used is not possible.",
            "Contact the Phorum developers and report this specific
             version number, so the checking scripts can be updated."
        );

        settype($ver[0], 'int');
        settype($ver[1], 'int');
        settype($ver[2], 'int');

        // MySQL before version 4.
        if ($ver[0] < 4) return array(
            PHORUM_SANITY_CRIT,
            "The MySQL database server that is used is too old. The
             running version is \"" . htmlspecialchars($row[0]) . "\",
             while MySQL version 4.0.18 or higher is recommended.",
            "Upgrade your MySQL server to a newer version. If your
             website is hosted with a service provider, please contact
             the service provider to upgrade your MySQL database."
        );

        // MySQL before version 4.0.18, with full text search enabled.
        if (isset($PHORUM["DBCONFIG"]["mysql_use_ft"]) && $PHORUM["DBCONFIG"]["mysql_use_ft"] &&
            $ver[0] == 4 && $ver[1] == 0 && $ver[2] < 18) return array(
            PHORUM_SANITY_WARN,
            "The MySQL database server that is used does not
             support all Phorum features. The running version is
             \"" . htmlspecialchars($row[0]) . "\", while MySQL version
             4.0.18 or higher is recommended.",
            "Upgrade your MySQL server to a newer version. If your
             website is hosted with a service provider, please contact
             the service provider to upgrade your MySQL database."
        );

        // All checks are okay.
        return array (PHORUM_SANITY_OK, NULL);
    }

    return array(
        PHORUM_SANITY_CRIT,
        "An unexpected problem was found in running the sanity
         check function phorum_db_sanitychecks().",
        "Contact the Phorum developers to find out what the problem is."
    );
}

?>
