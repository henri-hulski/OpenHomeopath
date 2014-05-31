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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 PACK_KEYS=0;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
CREATE TABLE `src_translations` (
  `src_native` varchar(12) NOT NULL,
  `src_translated` varchar(12) NOT NULL,
  KEY `src_native` (`src_native`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
  `src_rep` varchar(8) NOT NULL DEFAULT 'all',
  `src_materia` varchar(8) NOT NULL DEFAULT 'all',
  `registration` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `email_registered` (`email_registered`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
