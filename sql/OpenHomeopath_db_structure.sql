/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ip`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `active_users` (
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__main_rubrics` (
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `rubric_de` varchar(255) NOT NULL,
  `rubric_en` varchar(255) NOT NULL,
  `syn_rubric_id` tinyint(3) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__materia` (
  `materia_id` smallint(5) unsigned NOT NULL,
  `rem_id` smallint(5) unsigned NOT NULL,
  `rem_note` text NOT NULL,
  `rem_description` text NOT NULL,
  `rem_related` text NOT NULL,
  `rem_incomp` text NOT NULL,
  `rem_antidot` text NOT NULL,
  `src_id` varchar(12) NOT NULL,
  `rem_leadsym_general` text NOT NULL,
  `rem_leadsym_mind` text NOT NULL,
  `rem_leadsym_body` text NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__rem_alias` (
  `alias_short` varchar(255) NOT NULL,
  `rem_id` smallint(5) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__remedies` (
  `rem_id` smallint(5) unsigned NOT NULL,
  `rem_short` varchar(255) NOT NULL,
  `rem_name` varchar(255) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__sources` (
  `src_no` smallint(5) unsigned NOT NULL,
  `src_id` varchar(12) NOT NULL,
  `src_title` varchar(200) NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `src_type` varchar(30) NOT NULL,
  `grade_max` tinyint(1) unsigned NOT NULL,
  `src_author` tinytext NOT NULL,
  `src_year` varchar(9) NOT NULL,
  `src_edition_version` tinytext NOT NULL,
  `src_copyright` tinytext NOT NULL,
  `src_license` text NOT NULL,
  `src_url` tinytext NOT NULL,
  `src_isbn` tinytext NOT NULL,
  `src_proving` text NOT NULL,
  `src_note` text NOT NULL,
  `src_contact` text NOT NULL,
  `primary_src` tinyint(1) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__sym_rem` (
  `rel_id` mediumint(8) unsigned NOT NULL,
  `sym_id` mediumint(8) unsigned NOT NULL,
  `rem_id` smallint(5) unsigned NOT NULL,
  `grade` tinyint(1) unsigned NOT NULL,
  `src_id` varchar(12) NOT NULL,
  `status_id` tinyint(1) unsigned NOT NULL,
  `kuenzli` tinyint(1) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__sym_rem_refs` (
  `rel_id` mediumint(8) unsigned NOT NULL,
  `src_id` varchar(12) NOT NULL,
  `nonclassic` tinyint(1) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__sym_src` (
  `sym_id` mediumint(8) unsigned NOT NULL,
  `src_id` varchar(12) NOT NULL,
  `src_page` smallint(5) unsigned NOT NULL,
  `extra` text NOT NULL,
  `kuenzli` tinyint(1) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive__symptoms` (
  `sym_id` mediumint(8) unsigned NOT NULL,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL,
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL,
  `syn_id` mediumint(8) unsigned NOT NULL,
  `xref_id` mediumint(8) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archive_type` varchar(24) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banned_users` (
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_materia` (
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `src_id` varchar(12) NOT NULL,
  UNIQUE KEY `user_src` (`username`,`src_id`),
  KEY `username` (`username`),
  KEY `src_id` (`src_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_rep` (
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `src_id` varchar(12) NOT NULL,
  UNIQUE KEY `user_src` (`username`,`src_id`),
  KEY `username` (`username`),
  KEY `src_id` (`src_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datadmin__main_rubrics` (
  `id_field` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name_field` varchar(50) DEFAULT NULL,
  `label_de_field` varchar(255) NOT NULL DEFAULT '',
  `label_en_field` varchar(255) NOT NULL DEFAULT '',
  `type_field` varchar(50) NOT NULL DEFAULT 'text',
  `content_field` varchar(50) NOT NULL DEFAULT 'alphanumeric',
  `present_search_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_results_search_field` varchar(1) NOT NULL DEFAULT '1',
  `present_details_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_insert_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_ext_update_form_field` varchar(1) NOT NULL DEFAULT '1',
  `required_field` varchar(1) NOT NULL DEFAULT '0',
  `check_duplicated_insert_field` varchar(1) NOT NULL DEFAULT '0',
  `other_choices_field` varchar(1) NOT NULL DEFAULT '0',
  `select_options_field` text,
  `primary_key_field_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_table_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_db_field` varchar(50) NOT NULL DEFAULT '',
  `linked_fields_field` text,
  `linked_fields_order_by_field` text,
  `linked_fields_order_type_field` text,
  `select_type_field` varchar(100) NOT NULL DEFAULT 'is_equal/contains/starts_with/ends_with/greater_than/less_then/is_null/is_empty',
  `prefix_field` text,
  `default_value_field` text,
  `width_field` varchar(5) NOT NULL DEFAULT '',
  `height_field` varchar(5) NOT NULL DEFAULT '',
  `maxlength_field` varchar(5) NOT NULL DEFAULT '100',
  `hint_insert_de_field` varchar(255) NOT NULL DEFAULT '',
  `hint_insert_en_field` varchar(255) NOT NULL DEFAULT '',
  `order_form_field` tinyint(3) unsigned NOT NULL,
  `separator_field` char(2) NOT NULL DEFAULT '~',
  PRIMARY KEY (`id_field`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datadmin__materia` (
  `id_field` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name_field` varchar(50) DEFAULT NULL,
  `label_de_field` varchar(255) NOT NULL DEFAULT '',
  `label_en_field` varchar(255) NOT NULL DEFAULT '',
  `type_field` varchar(50) NOT NULL DEFAULT 'text',
  `content_field` varchar(50) NOT NULL DEFAULT 'alphanumeric',
  `present_search_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_results_search_field` varchar(1) NOT NULL DEFAULT '1',
  `present_details_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_insert_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_ext_update_form_field` varchar(1) NOT NULL DEFAULT '1',
  `required_field` varchar(1) NOT NULL DEFAULT '0',
  `check_duplicated_insert_field` varchar(1) NOT NULL DEFAULT '0',
  `other_choices_field` varchar(1) NOT NULL DEFAULT '0',
  `select_options_field` text,
  `primary_key_field_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_table_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_db_field` varchar(50) NOT NULL DEFAULT '',
  `linked_fields_field` text,
  `linked_fields_order_by_field` text,
  `linked_fields_order_type_field` text,
  `select_type_field` varchar(100) NOT NULL DEFAULT 'is_equal/contains/starts_with/ends_with/greater_than/less_then/is_null/is_empty',
  `prefix_field` text,
  `default_value_field` text,
  `width_field` varchar(5) NOT NULL DEFAULT '',
  `height_field` varchar(5) NOT NULL DEFAULT '',
  `maxlength_field` varchar(5) NOT NULL DEFAULT '100',
  `hint_insert_de_field` varchar(255) NOT NULL DEFAULT '',
  `hint_insert_en_field` varchar(255) NOT NULL DEFAULT '',
  `order_form_field` int(11) NOT NULL,
  `separator_field` varchar(2) NOT NULL DEFAULT '~',
  PRIMARY KEY (`id_field`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datadmin__rem_alias` (
  `id_field` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name_field` varchar(50) NOT NULL,
  `label_de_field` varchar(255) NOT NULL DEFAULT '',
  `label_en_field` varchar(255) NOT NULL DEFAULT '',
  `type_field` varchar(50) NOT NULL DEFAULT 'text',
  `content_field` varchar(50) NOT NULL DEFAULT 'alphanumeric',
  `present_search_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_results_search_field` varchar(1) NOT NULL DEFAULT '1',
  `present_details_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_insert_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_ext_update_form_field` varchar(1) NOT NULL DEFAULT '1',
  `required_field` varchar(1) NOT NULL DEFAULT '0',
  `check_duplicated_insert_field` varchar(1) NOT NULL DEFAULT '0',
  `other_choices_field` varchar(1) NOT NULL DEFAULT '0',
  `select_options_field` text NOT NULL,
  `primary_key_field_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_table_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_db_field` varchar(50) NOT NULL DEFAULT '',
  `linked_fields_field` text NOT NULL,
  `linked_fields_order_by_field` text NOT NULL,
  `linked_fields_order_type_field` text NOT NULL,
  `select_type_field` varchar(100) NOT NULL DEFAULT 'is_equal/contains/starts_with/ends_with/greater_than/less_then/is_empty',
  `prefix_field` text NOT NULL,
  `default_value_field` text NOT NULL,
  `width_field` varchar(5) NOT NULL DEFAULT '',
  `height_field` varchar(5) NOT NULL DEFAULT '',
  `maxlength_field` varchar(5) NOT NULL DEFAULT '100',
  `hint_insert_de_field` varchar(255) NOT NULL DEFAULT '',
  `hint_insert_en_field` varchar(255) NOT NULL DEFAULT '',
  `order_form_field` tinyint(3) unsigned NOT NULL,
  `separator_field` char(2) NOT NULL DEFAULT '~',
  PRIMARY KEY (`id_field`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datadmin__remedies` (
  `id_field` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name_field` varchar(50) DEFAULT NULL,
  `label_de_field` varchar(255) NOT NULL DEFAULT '',
  `label_en_field` varchar(255) NOT NULL DEFAULT '',
  `type_field` varchar(50) NOT NULL DEFAULT 'text',
  `content_field` varchar(50) NOT NULL DEFAULT 'alphanumeric',
  `present_search_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_results_search_field` varchar(1) NOT NULL DEFAULT '1',
  `present_details_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_insert_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_ext_update_form_field` varchar(1) NOT NULL DEFAULT '1',
  `required_field` varchar(1) NOT NULL DEFAULT '0',
  `check_duplicated_insert_field` varchar(1) NOT NULL DEFAULT '0',
  `other_choices_field` varchar(1) NOT NULL DEFAULT '0',
  `select_options_field` text,
  `primary_key_field_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_table_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_db_field` varchar(50) NOT NULL DEFAULT '',
  `linked_fields_field` text,
  `linked_fields_order_by_field` text,
  `linked_fields_order_type_field` text,
  `select_type_field` varchar(100) NOT NULL DEFAULT 'is_equal/contains/starts_with/ends_with/greater_than/less_then/is_null/is_empty',
  `prefix_field` text,
  `default_value_field` text,
  `width_field` varchar(5) NOT NULL DEFAULT '',
  `height_field` varchar(5) NOT NULL DEFAULT '',
  `maxlength_field` varchar(5) NOT NULL DEFAULT '100',
  `hint_insert_de_field` varchar(255) NOT NULL DEFAULT '',
  `hint_insert_en_field` varchar(255) NOT NULL DEFAULT '',
  `order_form_field` tinyint(3) unsigned NOT NULL,
  `separator_field` char(2) NOT NULL DEFAULT '~',
  PRIMARY KEY (`id_field`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datadmin__sources` (
  `id_field` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name_field` varchar(50) DEFAULT NULL,
  `label_de_field` varchar(255) NOT NULL DEFAULT '',
  `label_en_field` varchar(255) NOT NULL DEFAULT '',
  `type_field` varchar(50) NOT NULL DEFAULT 'text',
  `content_field` varchar(50) NOT NULL DEFAULT 'alphanumeric',
  `present_search_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_results_search_field` varchar(1) NOT NULL DEFAULT '1',
  `present_details_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_insert_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_ext_update_form_field` varchar(1) NOT NULL DEFAULT '1',
  `required_field` varchar(1) NOT NULL DEFAULT '0',
  `check_duplicated_insert_field` varchar(1) NOT NULL DEFAULT '0',
  `other_choices_field` varchar(1) NOT NULL DEFAULT '0',
  `select_options_field` text,
  `primary_key_field_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_table_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_db_field` varchar(50) NOT NULL DEFAULT '',
  `linked_fields_field` text,
  `linked_fields_order_by_field` text,
  `linked_fields_order_type_field` text,
  `select_type_field` varchar(100) NOT NULL DEFAULT 'is_equal/contains/starts_with/ends_with/greater_than/less_then/is_empty',
  `prefix_field` text,
  `default_value_field` text,
  `width_field` varchar(5) NOT NULL DEFAULT '',
  `height_field` varchar(5) NOT NULL DEFAULT '',
  `maxlength_field` varchar(5) NOT NULL DEFAULT '100',
  `hint_insert_de_field` varchar(255) NOT NULL DEFAULT '',
  `hint_insert_en_field` varchar(255) NOT NULL DEFAULT '',
  `order_form_field` int(11) NOT NULL,
  `separator_field` varchar(2) NOT NULL DEFAULT '~',
  PRIMARY KEY (`id_field`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datadmin__symptoms` (
  `id_field` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name_field` varchar(50) DEFAULT NULL,
  `label_de_field` varchar(255) NOT NULL DEFAULT '',
  `label_en_field` varchar(255) NOT NULL DEFAULT '',
  `type_field` varchar(50) NOT NULL DEFAULT 'text',
  `content_field` varchar(50) NOT NULL DEFAULT 'alphanumeric',
  `present_search_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_results_search_field` varchar(1) NOT NULL DEFAULT '1',
  `present_details_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_insert_form_field` varchar(1) NOT NULL DEFAULT '1',
  `present_ext_update_form_field` varchar(1) NOT NULL DEFAULT '1',
  `required_field` varchar(1) NOT NULL DEFAULT '0',
  `check_duplicated_insert_field` varchar(1) NOT NULL DEFAULT '0',
  `other_choices_field` varchar(1) NOT NULL DEFAULT '0',
  `select_options_field` text,
  `primary_key_field_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_table_field` varchar(255) NOT NULL DEFAULT '',
  `primary_key_db_field` varchar(50) NOT NULL DEFAULT '',
  `linked_fields_field` text,
  `linked_fields_order_by_field` text,
  `linked_fields_order_type_field` text,
  `select_type_field` varchar(100) NOT NULL DEFAULT 'is_equal/contains/starts_with/ends_with/greater_than/less_then/is_empty',
  `prefix_field` text,
  `default_value_field` text,
  `width_field` varchar(5) NOT NULL DEFAULT '',
  `height_field` varchar(5) NOT NULL DEFAULT '',
  `maxlength_field` varchar(5) NOT NULL DEFAULT '100',
  `hint_insert_de_field` varchar(255) NOT NULL DEFAULT '',
  `hint_insert_en_field` varchar(255) NOT NULL DEFAULT '',
  `order_form_field` tinyint(3) unsigned NOT NULL,
  `separator_field` char(2) NOT NULL DEFAULT '~',
  PRIMARY KEY (`id_field`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datadmin__tables` (
  `name_table` varchar(255) NOT NULL DEFAULT '',
  `allowed_table` varchar(1) NOT NULL DEFAULT '',
  `enable_insert_table` varchar(1) NOT NULL DEFAULT '',
  `enable_edit_table` varchar(1) NOT NULL DEFAULT '',
  `enable_delete_table` varchar(1) NOT NULL DEFAULT '',
  `enable_details_table` varchar(1) NOT NULL DEFAULT '',
  `alias_table_de` varchar(255) NOT NULL DEFAULT '',
  `alias_table_en` varchar(255) NOT NULL DEFAULT '',
  `position` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`name_table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__banlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `pcre` tinyint(4) NOT NULL DEFAULT '0',
  `string` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT '0',
  `file_data` mediumtext NOT NULL,
  `add_datetime` int(10) unsigned NOT NULL DEFAULT '0',
  `message_id` int(10) unsigned NOT NULL DEFAULT '0',
  `link` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`file_id`),
  KEY `add_datetime` (`add_datetime`),
  KEY `message_id_link` (`message_id`,`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__forum_group_xref` (
  `forum_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `permission` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`forum_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__forums` (
  `forum_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `active` smallint(6) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `template` varchar(50) NOT NULL DEFAULT '',
  `folder_flag` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `list_length_flat` int(10) unsigned NOT NULL DEFAULT '0',
  `list_length_threaded` int(10) unsigned NOT NULL DEFAULT '0',
  `moderation` int(10) unsigned NOT NULL DEFAULT '0',
  `threaded_list` tinyint(4) NOT NULL DEFAULT '0',
  `threaded_read` tinyint(4) NOT NULL DEFAULT '0',
  `float_to_top` tinyint(4) NOT NULL DEFAULT '0',
  `check_duplicate` tinyint(4) NOT NULL DEFAULT '0',
  `allow_attachment_types` varchar(100) NOT NULL DEFAULT '',
  `max_attachment_size` int(10) unsigned NOT NULL DEFAULT '0',
  `max_totalattachment_size` int(10) unsigned NOT NULL DEFAULT '0',
  `max_attachments` int(10) unsigned NOT NULL DEFAULT '0',
  `pub_perms` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_perms` int(10) unsigned NOT NULL DEFAULT '0',
  `display_ip_address` smallint(5) unsigned NOT NULL DEFAULT '1',
  `allow_email_notify` smallint(5) unsigned NOT NULL DEFAULT '1',
  `language` varchar(100) NOT NULL DEFAULT 'english',
  `email_moderators` tinyint(1) NOT NULL DEFAULT '0',
  `message_count` int(10) unsigned NOT NULL DEFAULT '0',
  `sticky_count` int(10) unsigned NOT NULL DEFAULT '0',
  `thread_count` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post_time` int(10) unsigned NOT NULL DEFAULT '0',
  `display_order` int(10) unsigned NOT NULL DEFAULT '0',
  `read_length` int(10) unsigned NOT NULL DEFAULT '0',
  `vroot` int(10) unsigned NOT NULL DEFAULT '0',
  `edit_post` tinyint(1) NOT NULL DEFAULT '1',
  `template_settings` text NOT NULL,
  `count_views` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_fixed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reverse_threading` tinyint(1) NOT NULL DEFAULT '0',
  `inherit_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`forum_id`),
  KEY `name` (`name`),
  KEY `active` (`active`,`parent_id`),
  KEY `group_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '0',
  `open` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `thread` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `author` varchar(37) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '2',
  `msgid` varchar(100) NOT NULL DEFAULT '',
  `modifystamp` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `thread_count` int(10) unsigned NOT NULL DEFAULT '0',
  `moderator_post` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(4) NOT NULL DEFAULT '2',
  `datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `meta` mediumtext NOT NULL,
  `viewcount` int(10) unsigned NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`),
  KEY `thread_message` (`thread`,`message_id`),
  KEY `thread_forum` (`thread`,`forum_id`),
  KEY `special_threads` (`sort`,`forum_id`),
  KEY `status_forum` (`status`,`forum_id`),
  KEY `list_page_float` (`forum_id`,`parent_id`,`modifystamp`),
  KEY `list_page_flat` (`forum_id`,`parent_id`,`thread`),
  KEY `post_count` (`forum_id`,`status`,`parent_id`),
  KEY `dup_check` (`forum_id`,`author`,`subject`,`datestamp`),
  KEY `forum_max_message` (`forum_id`,`message_id`,`status`,`parent_id`),
  KEY `last_post_time` (`forum_id`,`status`,`modifystamp`),
  KEY `next_prev_thread` (`forum_id`,`status`,`thread`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__pm_buddies` (
  `pm_buddy_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `buddy_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pm_buddy_id`),
  UNIQUE KEY `userids` (`user_id`,`buddy_user_id`),
  KEY `buddy_user_id` (`buddy_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__pm_folders` (
  `pm_folder_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `foldername` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`pm_folder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__pm_messages` (
  `pm_message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `from_username` varchar(50) NOT NULL DEFAULT '',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `meta` mediumtext NOT NULL,
  PRIMARY KEY (`pm_message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__pm_xref` (
  `pm_xref_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `pm_folder_id` int(10) unsigned NOT NULL DEFAULT '0',
  `special_folder` varchar(10) DEFAULT NULL,
  `pm_message_id` int(10) unsigned NOT NULL DEFAULT '0',
  `read_flag` tinyint(1) NOT NULL DEFAULT '0',
  `reply_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pm_xref_id`),
  KEY `xref` (`user_id`,`pm_folder_id`,`pm_message_id`),
  KEY `read_flag` (`read_flag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__search` (
  `message_id` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `search_text` mediumtext NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `forum_id` (`forum_id`),
  FULLTEXT KEY `search_text` (`search_text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__settings` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` enum('V','S') NOT NULL DEFAULT 'V',
  `data` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__subscribers` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sub_type` int(10) unsigned NOT NULL DEFAULT '0',
  `thread` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`forum_id`,`thread`),
  KEY `forum_id` (`forum_id`,`thread`,`sub_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__user_custom_fields` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`user_id`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__user_group_xref` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__user_newflags` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `forum_id` int(11) NOT NULL DEFAULT '0',
  `message_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`forum_id`,`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__user_permissions` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_id` int(10) unsigned NOT NULL DEFAULT '0',
  `permission` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`forum_id`),
  KEY `forum_id` (`forum_id`,`permission`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `homeophorum__users` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(50) NOT NULL DEFAULT '',
  `cookie_sessid_lt` varchar(50) NOT NULL DEFAULT '',
  `sessid_st` varchar(50) NOT NULL DEFAULT '',
  `sessid_st_timeout` int(10) unsigned NOT NULL DEFAULT '0',
  `password_temp` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `email_temp` varchar(110) NOT NULL DEFAULT '',
  `hide_email` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  `signature` text NOT NULL,
  `threaded_list` tinyint(4) NOT NULL DEFAULT '0',
  `posts` int(10) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `threaded_read` tinyint(4) NOT NULL DEFAULT '0',
  `date_added` int(10) unsigned NOT NULL DEFAULT '0',
  `date_last_active` int(10) unsigned NOT NULL DEFAULT '0',
  `last_active_forum` int(10) unsigned NOT NULL DEFAULT '0',
  `hide_activity` tinyint(1) NOT NULL DEFAULT '0',
  `show_signature` tinyint(1) NOT NULL DEFAULT '0',
  `email_notify` tinyint(1) NOT NULL DEFAULT '0',
  `pm_email_notify` tinyint(1) NOT NULL DEFAULT '1',
  `tz_offset` tinyint(2) NOT NULL DEFAULT '-99',
  `is_dst` tinyint(1) NOT NULL DEFAULT '0',
  `user_language` varchar(100) NOT NULL DEFAULT '',
  `user_template` varchar(100) NOT NULL DEFAULT '',
  `moderator_data` text NOT NULL,
  `moderation_email` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `active` (`active`),
  KEY `userpass` (`username`,`password`),
  KEY `sessid_st` (`sessid_st`),
  KEY `cookie_sessid_lt` (`cookie_sessid_lt`),
  KEY `activity` (`date_last_active`,`hide_activity`,`last_active_forum`),
  KEY `date_added` (`date_added`),
  KEY `email_temp` (`email_temp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itis__kingdoms` (
  `kingdom_id` int(11) NOT NULL,
  `kingdom_name` char(10) NOT NULL,
  `update_date` date NOT NULL,
  PRIMARY KEY (`kingdom_id`),
  KEY `kingdoms_index` (`kingdom_id`,`kingdom_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itis__longnames` (
  `tsn` int(11) NOT NULL,
  `completename` varchar(164) NOT NULL,
  PRIMARY KEY (`tsn`),
  KEY `tsn` (`tsn`,`completename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itis__synonym_links` (
  `tsn` int(11) NOT NULL,
  `tsn_accepted` int(11) NOT NULL,
  `update_date` date NOT NULL,
  PRIMARY KEY (`tsn`,`tsn_accepted`),
  KEY `tsn_accepted` (`tsn_accepted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itis__taxon_unit_types` (
  `kingdom_id` int(11) NOT NULL,
  `rank_id` smallint(6) NOT NULL,
  `rank_name` char(15) NOT NULL,
  `dir_parent_rank_id` smallint(6) NOT NULL,
  `req_parent_rank_id` smallint(6) NOT NULL,
  `update_date` date NOT NULL,
  PRIMARY KEY (`kingdom_id`,`rank_id`),
  KEY `taxon_ut_index` (`kingdom_id`,`rank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itis__taxonomic_units` (
  `tsn` int(11) NOT NULL,
  `unit_ind1` char(1) DEFAULT NULL,
  `unit_name1` char(35) NOT NULL,
  `unit_ind2` char(1) DEFAULT NULL,
  `unit_name2` varchar(35) DEFAULT NULL,
  `unit_ind3` varchar(7) DEFAULT NULL,
  `unit_name3` varchar(35) DEFAULT NULL,
  `unit_ind4` varchar(7) DEFAULT NULL,
  `unit_name4` varchar(35) DEFAULT NULL,
  `unnamed_taxon_ind` char(1) DEFAULT NULL,
  `name_usage` varchar(12) NOT NULL,
  `unaccept_reason` varchar(50) DEFAULT NULL,
  `credibility_rtng` varchar(40) NOT NULL,
  `completeness_rtng` char(10) DEFAULT NULL,
  `currency_rating` char(7) DEFAULT NULL,
  `phylo_sort_seq` smallint(6) DEFAULT NULL,
  `initial_time_stamp` datetime NOT NULL,
  `parent_tsn` int(11) DEFAULT NULL,
  `taxon_author_id` int(11) DEFAULT NULL,
  `hybrid_author_id` int(11) DEFAULT NULL,
  `kingdom_id` smallint(6) NOT NULL,
  `rank_id` smallint(6) NOT NULL,
  `update_date` date NOT NULL,
  `uncertain_prnt_ind` char(3) DEFAULT NULL,
  PRIMARY KEY (`tsn`),
  KEY `taxon_unit_index1` (`tsn`,`parent_tsn`),
  KEY `taxon_unit_index2` (`tsn`,`unit_name1`,`name_usage`),
  KEY `taxon_unit_index3` (`kingdom_id`,`rank_id`),
  KEY `taxon_unit_index4` (`tsn`,`taxon_author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itis__vernaculars` (
  `tsn` int(11) NOT NULL,
  `vernacular_name` varchar(80) NOT NULL,
  `language` varchar(15) NOT NULL,
  `approved_ind` char(1) DEFAULT NULL,
  `update_date` date NOT NULL,
  `vern_id` int(11) NOT NULL,
  PRIMARY KEY (`tsn`,`vern_id`),
  KEY `vernaculars_index1` (`tsn`,`vernacular_name`,`language`),
  KEY `vernaculars_index2` (`tsn`,`vern_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kraque__context_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orig_id` int(11) unsigned NOT NULL DEFAULT '0',
  `language_id` varchar(6) NOT NULL,
  `context_title` varchar(16) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kraque__hamse` (
  `hamse_id` mediumint(10) NOT NULL AUTO_INCREMENT,
  `quelle_id` varchar(12) NOT NULL,
  `hamse_title` varchar(200) NOT NULL,
  `lang` varchar(6) NOT NULL,
  `hamse_type` varchar(30) NOT NULL,
  `hamse_method` varchar(30) NOT NULL,
  `wertigkeit_max` tinyint(1) unsigned NOT NULL,
  `quelle_autor` tinytext NOT NULL,
  `hamse_date` date NOT NULL DEFAULT '0000-00-00',
  `quelle_auflage_version` tinytext NOT NULL,
  `quelle_copyright` tinytext NOT NULL,
  `quelle_lizenz` text NOT NULL,
  `quelle_url` tinytext NOT NULL,
  `quelle_isbn` tinytext NOT NULL,
  `quelle_proving` text NOT NULL,
  `quelle_bemerkung` text NOT NULL,
  `quelle_kontakt` text NOT NULL,
  `hauptquelle` tinyint(1) NOT NULL DEFAULT '1',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`hamse_id`),
  KEY `username` (`username`),
  KEY `sprache_id` (`lang`),
  KEY `quelle_art` (`hamse_type`),
  KEY `wertigkeit_max` (`wertigkeit_max`),
  KEY `hauptquelle` (`hauptquelle`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kraque__proving` (
  `id` mediumint(6) NOT NULL AUTO_INCREMENT,
  `mittel_id` smallint(5) NOT NULL,
  `title` varchar(200) NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `proving_remedy_contact_id` tinyint(3) NOT NULL,
  `proving_type_id` tinyint(3) NOT NULL,
  `method` text NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `description` text NOT NULL,
  `contact` text NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `sprache_id` (`lang_id`),
  KEY `mittel_id` (`mittel_id`),
  KEY `title` (`title`),
  KEY `proving_type_id` (`proving_type_id`),
  KEY `date` (`date`),
  KEY `proving_remedy_contact_id` (`proving_remedy_contact_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kraque__proving_rubric` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `proving_id` mediumint(6) NOT NULL,
  `rubrik_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `title` varchar(255) NOT NULL,
  `prover_numbers` mediumtext NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Rubrik` (`rubrik_id`),
  KEY `username` (`username`),
  KEY `sprache_id` (`lang_id`),
  KEY `proving_id` (`proving_id`),
  KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=338 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kraque__publications` (
  `pub_id` mediumint(10) NOT NULL AUTO_INCREMENT,
  `hamse_id` mediumint(10) NOT NULL,
  `pub_title` varchar(200) NOT NULL,
  `lang` varchar(6) NOT NULL,
  `pub_type` varchar(30) NOT NULL,
  `pub_autor` tinytext NOT NULL,
  `pub_date` date NOT NULL DEFAULT '0000-00-00',
  `pub_auflage_version` tinytext NOT NULL,
  `pub_copyright` tinytext NOT NULL,
  `pub_lizenz` text NOT NULL,
  `pub_url` tinytext NOT NULL,
  `pub_isbn` tinytext NOT NULL,
  `pub_publisher` text NOT NULL,
  `pub_description` text NOT NULL,
  `pub_contact` text NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pub_id`),
  KEY `username` (`username`),
  KEY `sprache_id` (`lang`),
  KEY `quelle_art` (`pub_type`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kraque__quellen_proving` (
  `quelle_id` varchar(12) NOT NULL,
  `proving_id` mediumint(6) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `username` (`username`),
  KEY `quelle_id` (`quelle_id`),
  KEY `proving_id` (`proving_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kraque__relations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `src_id` int(11) unsigned NOT NULL DEFAULT '0',
  `src_table` varchar(50) NOT NULL,
  `target_id` int(11) unsigned NOT NULL,
  `target_table` varchar(50) NOT NULL,
  `relation_type_id` int(11) unsigned NOT NULL,
  `relations_system_id` int(11) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `src_id` (`src_id`),
  KEY `target_id` (`target_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2197 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kraque__symptome_mittel_proving_rubric` (
  `beziehungs_id` mediumint(8) unsigned NOT NULL,
  `proving_rubric_id` mediumint(8) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  UNIQUE KEY `beziehungs_id_2` (`beziehungs_id`,`proving_rubric_id`),
  KEY `beziehungs_id` (`beziehungs_id`),
  KEY `username` (`username`),
  KEY `proving_rubric_id` (`proving_rubric_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `lang_id` varchar(6) NOT NULL,
  `lang_de` varchar(16) NOT NULL,
  `lang_en` varchar(16) NOT NULL,
  `lang_phorum` varchar(16) NOT NULL,
  `sys_lang` tinyint(1) NOT NULL DEFAULT '0',
  `sym_lang` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`lang_id`),
  KEY `sym_lang` (`sym_lang`),
  KEY `sys_lang` (`sys_lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `magic_hat` (
  `magic_hat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(50) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `type` varchar(23) NOT NULL DEFAULT 'paypal',
  `currency` char(3) NOT NULL DEFAULT 'EUR',
  `amount` decimal(6,2) unsigned NOT NULL,
  `date` date NOT NULL,
  `txn_id` varchar(19) NOT NULL,
  `txn_type` varchar(19) NOT NULL,
  PRIMARY KEY (`magic_hat_id`),
  KEY `username` (`username`),
  KEY `date` (`date`),
  KEY `email` (`email`),
  KEY `txn_id` (`txn_id`),
  KEY `currency` (`currency`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `main_rubrics` (
  `rubric_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rubric_de` varchar(255) NOT NULL,
  `rubric_en` varchar(255) NOT NULL,
  `syn_rubric_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rubric_id`),
  UNIQUE KEY `rubric_de` (`rubric_de`),
  UNIQUE KEY `rubric_en` (`rubric_en`),
  KEY `username` (`username`),
  KEY `syn_rubric_id` (`syn_rubric_id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materia` (
  `materia_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `rem_id` smallint(5) unsigned NOT NULL,
  `rem_note` text NOT NULL,
  `rem_description` text NOT NULL,
  `rem_related` text NOT NULL,
  `rem_incomp` text NOT NULL,
  `rem_antidot` text NOT NULL,
  `src_id` varchar(12) NOT NULL,
  `rem_leadsym_general` text NOT NULL,
  `rem_leadsym_mind` text NOT NULL,
  `rem_leadsym_body` text NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`materia_id`),
  KEY `rem_src` (`rem_id`,`src_id`),
  KEY `src_id` (`src_id`),
  KEY `rem_id` (`rem_id`)
) ENGINE=MyISAM AUTO_INCREMENT=346 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persons` (
  `person_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(250) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `forename` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `date_of_death` date NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`person_id`),
  KEY `full_name` (`fullname`),
  KEY `surname` (`surname`),
  KEY `forename` (`forename`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=542 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_alias` (
  `alias_short` varchar(15) NOT NULL,
  `rem_id` smallint(5) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`alias_short`),
  KEY `username` (`username`),
  KEY `rem_id` (`rem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orig_id` int(11) unsigned NOT NULL DEFAULT '0',
  `lang_id` varchar(6) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` mediumtext NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=395 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_itis` (
  `rem_id` smallint(5) unsigned NOT NULL,
  `tsn` int(11) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rem_id`),
  KEY `username` (`username`),
  KEY `tsn` (`tsn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remedies` (
  `rem_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `rem_short` varchar(15) NOT NULL,
  `rem_name` varchar(63) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rem_id`),
  UNIQUE KEY `rem_name` (`rem_name`),
  UNIQUE KEY `rem_short` (`rem_short`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3816 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rep_sym` (
  `rep_id` mediumint(8) unsigned NOT NULL,
  `sym_id` mediumint(8) unsigned NOT NULL,
  `degree` tinyint(1) unsigned NOT NULL DEFAULT '1',
  KEY `rep_id` (`rep_id`),
  KEY `sym_id` (`sym_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repertorizations` (
  `patient_id` char(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `rep_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rep_note` text NOT NULL,
  `rep_prescription` text NOT NULL,
  `rep_public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sym_table` varchar(255) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `rep_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rep_id`),
  KEY `patient_id` (`patient_id`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2036 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skins` (
  `skin_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `skin_name` varchar(30) NOT NULL,
  `phorum_template` varchar(30) NOT NULL,
  `skin_author` tinytext NOT NULL,
  `skin_year` varchar(9) NOT NULL,
  `skin_version` varchar(30) NOT NULL,
  `skin_copyright` tinytext NOT NULL,
  `skin_license` text NOT NULL,
  `skin_description` text NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`skin_id`),
  UNIQUE KEY `skin_name` (`skin_name`),
  KEY `username` (`username`),
  KEY `phorum_template` (`phorum_template`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sources` (
  `src_no` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `src_id` varchar(12) NOT NULL,
  `src_title` varchar(200) NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `src_type` varchar(30) NOT NULL DEFAULT 'Repertorium',
  `grade_max` tinyint(1) unsigned NOT NULL,
  `src_author` tinytext NOT NULL,
  `src_year` varchar(9) NOT NULL DEFAULT '0',
  `src_edition_version` tinytext NOT NULL,
  `src_copyright` tinytext NOT NULL,
  `src_license` text NOT NULL,
  `src_url` tinytext NOT NULL,
  `src_isbn` tinytext NOT NULL,
  `src_proving` text NOT NULL,
  `src_note` text NOT NULL,
  `src_contact` text NOT NULL,
  `primary_src` tinyint(1) NOT NULL DEFAULT '1',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`src_id`),
  UNIQUE KEY `src_no` (`src_no`),
  KEY `username` (`username`),
  KEY `lang_id` (`lang_id`),
  KEY `src_type` (`src_type`),
  KEY `grade_max` (`grade_max`),
  KEY `primary_src` (`primary_src`)
) ENGINE=MyISAM AUTO_INCREMENT=3709 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `src_persons` (
  `src_id` varchar(12) NOT NULL,
  `person_id` mediumint(8) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `username` (`username`),
  KEY `person_id` (`person_id`),
  KEY `src_id` (`src_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `src_translations` (
  `src_native` varchar(12) NOT NULL,
  `src_translated` varchar(12) NOT NULL,
  KEY `src_native` (`src_native`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=6349 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__10` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=120993 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__11` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=120993 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__12` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121058 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__14_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121635 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__14_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121642 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__15_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121666 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__15_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121662 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__16_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121371 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__16_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121374 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__17_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121744 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__17_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121744 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_4_5_7_12_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122675 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_4_5_7_8_9_10_11_12_14_15_16_17_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122734 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_4_5_7_8_9_10_11_16_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122704 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_4_5_7_8_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122690 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_4_5_7_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122675 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_5_7_10_11_12_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121988 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_5_7_10_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121988 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_5_7_12_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121988 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_5_7_8_9_10_11_12_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122014 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_5_7_8_9_11_12_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122014 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_2_5_7_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121988 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_4_5_7_10_11_12_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122693 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_5_10` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122069 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_5_7` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122073 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_5_7_10_11_12` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122073 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__1_5_7_12` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122073 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__2_5_7_10_11_12_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121769 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__2_5_7_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121769 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__2_5_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121765 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__2_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=75109 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__2_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=75098 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__3707_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122060 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__3707_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122051 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__4_5_7_10_11_12_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122463 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__4_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121700 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__4_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=120988 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__5` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121752 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__5_7` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121758 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__7` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=121119 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__8_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=108321 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__8_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=108297 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__9_de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=115509 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__9_en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=115507 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__de` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122796 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym__de_only` (
  `sym_id` tinyint NOT NULL,
  `symptom` tinyint NOT NULL,
  `pid` tinyint NOT NULL,
  `rubric_id` tinyint NOT NULL,
  `lang_id` tinyint NOT NULL,
  `translation` tinyint NOT NULL,
  `syn_id` tinyint NOT NULL,
  `xref_id` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym__en` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122039 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym__en_only` (
  `sym_id` tinyint NOT NULL,
  `symptom` tinyint NOT NULL,
  `pid` tinyint NOT NULL,
  `rubric_id` tinyint NOT NULL,
  `lang_id` tinyint NOT NULL,
  `translation` tinyint NOT NULL,
  `syn_id` tinyint NOT NULL,
  `xref_id` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_count_rem` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `count_rem` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  KEY `count_rem` (`count_rem`)
) ENGINE=MyISAM AUTO_INCREMENT=122039 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_rem` (
  `rel_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sym_id` mediumint(8) unsigned NOT NULL,
  `rem_id` smallint(5) unsigned NOT NULL,
  `grade` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `src_id` varchar(12) NOT NULL,
  `status_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `kuenzli` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rel_id`),
  UNIQUE KEY `sym_rem_src` (`sym_id`,`rem_id`,`src_id`),
  KEY `rem_id` (`rem_id`),
  KEY `src_id` (`src_id`),
  KEY `sym_rem` (`sym_id`,`rem_id`),
  KEY `sym_id` (`sym_id`),
  KEY `rem_src` (`rem_id`,`src_id`),
  KEY `sym_src` (`sym_id`,`src_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1956371 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__10` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__11` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__14` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__15` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__16` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__17` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_4_5_7` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_4_5_7_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_4_5_7_8` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_4_5_7_8_9_10_11_12_14_15_16_17` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_4_5_7_8_9_10_11_16` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_5_7` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_5_7_10` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_5_7_10_11_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_5_7_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_5_7_8_9_10_11_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_2_5_7_8_9_11_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_4_5_7_10_11_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_5_10` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_5_7` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_5_7_10_11_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__1_5_7_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__2` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__2_5` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__2_5_7` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__2_5_7_10_11_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__3707` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__4` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__4_5_7_10_11_12` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__5` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__5_7` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__7` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__8` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `sym_rem__9` (
  `rel_id` tinyint NOT NULL,
  `sym_id` tinyint NOT NULL,
  `rem_id` tinyint NOT NULL,
  `grade` tinyint NOT NULL,
  `src_id` tinyint NOT NULL,
  `status_id` tinyint NOT NULL,
  `kuenzli` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `timestamp` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_rem_refs` (
  `rel_id` mediumint(8) unsigned NOT NULL,
  `src_id` varchar(12) NOT NULL,
  `nonclassic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `rel_src_nonclassic` (`rel_id`,`src_id`,`nonclassic`),
  KEY `username` (`username`),
  KEY `rel_id` (`rel_id`),
  KEY `src_id` (`src_id`),
  KEY `nonclassic` (`nonclassic`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_src` (
  `sym_id` mediumint(8) unsigned NOT NULL,
  `src_id` varchar(12) NOT NULL,
  `src_page` smallint(5) unsigned NOT NULL,
  `extra` text NOT NULL,
  `kuenzli` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `src_id` (`src_id`),
  KEY `kuenzli` (`kuenzli`),
  KEY `username` (`username`),
  KEY `sym_src` (`sym_id`,`src_id`),
  KEY `sym_id` (`sym_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_stats` (
  `sym_table` varchar(255) NOT NULL,
  `sym_base_table` varchar(15) NOT NULL,
  `sym_count` mediumint(8) unsigned NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_status` (
  `status_id` tinyint(1) unsigned NOT NULL,
  `status_de` varchar(64) NOT NULL,
  `status_en` varchar(64) NOT NULL,
  `status_symbol` char(2) NOT NULL,
  `status_grade` tinyint(1) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_synonyms` (
  `syn_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`syn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_translations` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`,`lang_id`),
  KEY `lang_id` (`lang_id`),
  KEY `sym_id` (`sym_id`),
  KEY `symptom_2` (`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=75090 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_upgrade` (
  `symptom_id_old` mediumint(8) unsigned NOT NULL,
  `sym_id_new` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`symptom_id_old`),
  KEY `sym_id_new` (`sym_id_new`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sym_xrefs` (
  `xref_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`xref_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1652 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `symptoms` (
  `sym_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `symptom` text NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rubric_id` tinyint(3) unsigned NOT NULL,
  `lang_id` varchar(6) NOT NULL,
  `translation` tinyint(1) NOT NULL DEFAULT '0',
  `syn_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `xref_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sym_id`),
  UNIQUE KEY `sym_rubric_lang` (`symptom`(326),`rubric_id`,`lang_id`,`translation`),
  KEY `parents_id` (`pid`),
  KEY `rubric_id` (`rubric_id`),
  KEY `lang_id` (`lang_id`),
  KEY `syn_id` (`syn_id`),
  KEY `xref_id` (`xref_id`),
  KEY `symptom_rubric` (`rubric_id`,`symptom`(333)),
  FULLTEXT KEY `symptom` (`symptom`)
) ENGINE=MyISAM AUTO_INCREMENT=122039 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` char(32) DEFAULT NULL,
  `userid` char(32) DEFAULT NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `email_registered` varchar(50) DEFAULT NULL,
  `hide_email` tinyint(1) NOT NULL DEFAULT '0',
  `lang_id` varchar(6) NOT NULL,
  `sym_lang_id` varchar(6) NOT NULL,
  `skin_name` varchar(30) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_real_name` tinytext NOT NULL,
  `user_extra` text NOT NULL,
  `user_signatur` tinytext NOT NULL,
  `src_rep` varchar(8) NOT NULL DEFAULT 'all',
  `src_materia` varchar(8) NOT NULL DEFAULT 'all',
  `registration` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `email_registered` (`email_registered`)
) ENGINE=InnoDB AUTO_INCREMENT=1342 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50001 DROP TABLE IF EXISTS `sym__de_only`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym__de_only` AS select `sym__de`.`sym_id` AS `sym_id`,`sym__de`.`symptom` AS `symptom`,`sym__de`.`pid` AS `pid`,`sym__de`.`rubric_id` AS `rubric_id`,`sym__de`.`lang_id` AS `lang_id`,`sym__de`.`translation` AS `translation`,`sym__de`.`syn_id` AS `syn_id`,`sym__de`.`xref_id` AS `xref_id`,`sym__de`.`username` AS `username`,`sym__de`.`timestamp` AS `timestamp` from `sym__de` where (`sym__de`.`lang_id` = 'de') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym__en_only`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym__en_only` AS select `sym__en`.`sym_id` AS `sym_id`,`sym__en`.`symptom` AS `symptom`,`sym__en`.`pid` AS `pid`,`sym__en`.`rubric_id` AS `rubric_id`,`sym__en`.`lang_id` AS `lang_id`,`sym__en`.`translation` AS `translation`,`sym__en`.`syn_id` AS `syn_id`,`sym__en`.`xref_id` AS `xref_id`,`sym__en`.`username` AS `username`,`sym__en`.`timestamp` AS `timestamp` from `sym__en` where (`sym__en`.`lang_id` = 'en') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'BZH') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__10`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__10` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'bl3.de') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__11`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__11` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'pers1') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'fd5') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__14`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__14` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'szs1') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__15`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__15` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'szs-oh1') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__16`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__16` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'huen-1') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__17`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__17` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'huen-2') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_4_5_7`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_4_5_7` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'openrep_pub') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_4_5_7_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_4_5_7_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'openrep_pub') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_4_5_7_8`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_4_5_7_8` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'boenn_allen') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'openrep_pub') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_4_5_7_8_9_10_11_12_14_15_16_17`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_4_5_7_8_9_10_11_12_14_15_16_17` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'boenn_allen') or (`sym_rem`.`src_id` = 'boenn_bogner') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'huen-1') or (`sym_rem`.`src_id` = 'huen-2') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'openrep_pub') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de') or (`sym_rem`.`src_id` = 'szs-oh1') or (`sym_rem`.`src_id` = 'szs1')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_4_5_7_8_9_10_11_16`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_4_5_7_8_9_10_11_16` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'boenn_allen') or (`sym_rem`.`src_id` = 'boenn_bogner') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'huen-1') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'openrep_pub') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_5_7`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_5_7` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_5_7_10`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_5_7_10` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_5_7_10_11_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_5_7_10_11_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_5_7_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_5_7_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_5_7_8_9_10_11_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_5_7_8_9_10_11_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'boenn_allen') or (`sym_rem`.`src_id` = 'boenn_bogner') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_2_5_7_8_9_11_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_2_5_7_8_9_11_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'boenn_allen') or (`sym_rem`.`src_id` = 'boenn_bogner') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_4_5_7_10_11_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_4_5_7_10_11_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'openrep_pub') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_5_10`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_5_10` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_5_7`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_5_7` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_5_7_10_11_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_5_7_10_11_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__1_5_7_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__1_5_7_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'BZH') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__2`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__2` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'kent.en') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__2_5`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__2_5` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__2_5_7`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__2_5_7` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__2_5_7_10_11_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__2_5_7_10_11_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'kent.en') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__3707`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__3707` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'sdn-oh1') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__4`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__4` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'openrep_pub') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__4_5_7_10_11_12`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__4_5_7_10_11_12` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'bl3.de') or (`sym_rem`.`src_id` = 'fd5') or (`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'openrep_pub') or (`sym_rem`.`src_id` = 'pers1') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__5`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__5` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'synt91.de') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__5_7`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__5_7` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where ((`sym_rem`.`src_id` = 'murphy') or (`sym_rem`.`src_id` = 'synt91.de')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__7`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__7` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'murphy') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__8`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__8` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'boenn_allen') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP TABLE IF EXISTS `sym_rem__9`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`OpenHomeopath`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `sym_rem__9` AS select `sym_rem`.`rel_id` AS `rel_id`,`sym_rem`.`sym_id` AS `sym_id`,`sym_rem`.`rem_id` AS `rem_id`,`sym_rem`.`grade` AS `grade`,`sym_rem`.`src_id` AS `src_id`,`sym_rem`.`status_id` AS `status_id`,`sym_rem`.`kuenzli` AS `kuenzli`,`sym_rem`.`username` AS `username`,`sym_rem`.`timestamp` AS `timestamp` from `sym_rem` where (`sym_rem`.`src_id` = 'boenn_bogner') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
