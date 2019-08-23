<?php

namespace HgQuiz;

class Main {

    private static $instance;
    private $HgQuizPostType;
//    private $VikCalAdminSettings;
    private $isAdmin;

    public static $HgQuizEntriesDb = 'hgquiz_entries';

    public function __construct()
    {
        $this->isAdmin = is_admin();
        $this->HgQuizPostType = new HgQuizPostType( $this->isAdmin );

//        if( $this->isAdmin ) {
//            $this->VikCalAdminSettings = new VikCalAdminSettings();
//        }
    }

    /**
     * Singleton
     *
     * @return Main
     */
    public static function init()
    {
        if( !isset( self::$instance ) && !( self::$instance instanceof Main) ) {
            self::$instance = new Main();
        }

        return self::$instance;
    }

    /**
     * Add new database table for quiz entries
     */
    public function HgInstall()
    {
        global $wpdb;

        $tableName = $wpdb->prefix . self::$HgQuizEntriesDb;
        $charset = $wpdb->get_charset_collate();

        if( $wpdb->get_var( "show tables like '$tableName'") != $tableName )
        {
            $sqlQry = "
                CREATE TABLE $tableName ( 
                    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, 
                    `postId` INT(11) UNSIGNED NOT NULL, 
                    `entryQuestion` TEXT NOT NULL, 
                    `entryAnswers` TEXT NOT NULL, 
                    `entryEmail` VARCHAR(200), 
                    PRIMARY KEY (`id`)
                ) $charset;
            ";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sqlQry);
        }
    }

}