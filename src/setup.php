<?php

namespace App;

use Composer\Script\Event;
use SQLite3;

class setup {

    public static function postInstall(Event $event) {
        $db = new SQLite3('mysqlitedb.db');

        $db->exec('CREATE TABLE tbl_A (some TEXT)');
        $db->exec("INSERT INTO tbl_A (some) VALUES ('This is some valued text')");

        $db->exec('CREATE TABLE tbl_B (more TEXT)');
        $db->exec("INSERT INTO tbl_B (more) VALUES ('This is more valued text')");
    }
}
