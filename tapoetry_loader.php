<?php

class TapoetryLoader extends MvcPluginLoader {

    var $db_version = '1.0';
    
    function init() {
    
        // Include any code here that needs to be called when this class is instantiated
    
        global $wpdb;
    
        $this->tables = array(
            'readings' => $wpdb->prefix.'readings',
            'readings_poets' => $wpdb->prefix.'readings_poets',
            'poets' => $wpdb->prefix.'poets',
            'partners' => $wpdb->prefix.'partners',
            'hosts' => $wpdb->prefix.'hosts'
        );

    }


    function activate() {
    
        // This call needs to be made to activate this app within WP MVC
        
        $this->activate_app(__FILE__);
        
        // Perform any databases modifications related to plugin activation here, if necessary

        require_once ABSPATH.'wp-admin/includes/upgrade.php';
    
        add_option('tapoetry_db_version', $this->db_version);
    
        $sql = '
            CREATE TABLE IF NOT EXISTS '.$this->tables['readings'].' (
              id int(11) NOT NULL auto_increment,
              partner_id int(9) default NULL,
              host_id int(9) default NULL,
              title varchar(255) default NULL,
              date date default NULL,
              time time default NULL,
              description text,
              url varchar(255) default NULL,
              banner_url varchar(255) default NULL,
              video_url varchar(255) default NULL,
              audio_url varchar(255) default NULL,
              archive_url varchar(255) default NULL,
              is_public tinyint(1) NOT NULL default 0,
              post_id bigint(20) DEFAULT NULL,
              PRIMARY KEY  (id),
              KEY `post_id` (`post_id`),
              KEY partner_id (partner_id),
              KEY host_id (host_id)
            )';
        dbDelta($sql);
    
        $sql = '
            CREATE TABLE IF NOT EXISTS '.$this->tables['readings_poets'].' (
              id int(7) NOT NULL auto_increment,
              reading_id int(11) default NULL,
              poet_id int(11) default NULL,
              PRIMARY KEY  (id),
              KEY reading_id (reading_id),
              KEY poet_id (poet_id)
            )';
        dbDelta($sql);
    
        $sql = '
            CREATE TABLE IF NOT EXISTS '.$this->tables['poets'].' (
              id int(8) NOT NULL auto_increment,
              first_name varchar(255) default NULL,
              last_name varchar(255) default NULL,
              location varchar(255) default NULL,
              `lon` FLOAT default NULL,
              `lat` FLOAT default NULL,
              url varchar(255) default NULL,
              image_url varchar(255) default NULL,
              description text,
              post_id bigint(20) DEFAULT NULL,
              PRIMARY KEY  (id),
              KEY `post_id` (`post_id`),
            )';
        dbDelta($sql);
    
        $sql = '
            CREATE TABLE IF NOT EXISTS '.$this->tables['partners'].' (
              id int(11) NOT NULL auto_increment,
              name varchar(255) NOT NULL,
              location varchar(255) NOT NULL,
              `lon` FLOAT default NULL,
              `lat` FLOAT default NULL,
              url varchar(255) default NULL,
              image_url varchar(255) default NULL,
              description text,
              post_id bigint(20) DEFAULT NULL,
              PRIMARY KEY  (id),
              KEY `post_id` (`post_id`),
            )';
        dbDelta($sql);
        
        $sql = '
            CREATE TABLE IF NOT EXISTS '.$this->tables['hosts'].' (
              id int(11) NOT NULL auto_increment,
              first_name varchar(255) default NULL,
              last_name varchar(255) default NULL,
              location varchar(255) default NULL,
              `lon` FLOAT default NULL,
              `lat` FLOAT default NULL,
              url varchar(255) default NULL,
              image_url varchar(255) default NULL,
              description text,
              post_id bigint(20) DEFAULT NULL,
              PRIMARY KEY  (id),
              KEY `post_id` (`post_id`),
            )';
        dbDelta($sql);
        
    }

    function deactivate() {
    
        // This call needs to be made to deactivate this app within WP MVC
        
        $this->deactivate_app(__FILE__);
        
        // Perform any databases modifications related to plugin deactivation here, if necessary
    
    }
    
}
