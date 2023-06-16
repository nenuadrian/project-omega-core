<?php declare (strict_types = 1);

if (Configs::get('db_host')) {
    DB::$host = Configs::get('db_host');
    DB::$user = Configs::get('db_user');
    DB::$password = Configs::get('db_pass');
    DB::$dbName = Configs::get('db_name');
}
