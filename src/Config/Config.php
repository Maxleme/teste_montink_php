<?php
namespace App\Config;

class Config {
    public static function db() {
        return [
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'dbname' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
        ];
    }
    public static function mail() {
        return [
            'host' => getenv('MAIL_HOST'),
            'username' => getenv('MAIL_USERNAME'),
            'password' => getenv('MAIL_PASSWORD'),
            'port' => getenv('MAIL_PORT'),
            'from' => getenv('MAIL_FROM'),
            'from_name' => getenv('MAIL_FROM_NAME'),
            'secure' => getenv('MAIL_SECURE'),
        ];
    }
} 