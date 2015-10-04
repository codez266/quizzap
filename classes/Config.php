<?php
class Config {
	protected static $host = 'localhost';
	protected static $dbuser = 'njath';
	protected static $db = 'njath';
	protected static $dbpass = 'njath';
	public static function getHost() {
		return self::$host;
	}
	public static function getUser() {
		return self::$dbuser;
	}
	public static function getDB() {
		return self::$db;
	}
	public static function getDBPass() {
		return self::$dbpass;
	}
}
