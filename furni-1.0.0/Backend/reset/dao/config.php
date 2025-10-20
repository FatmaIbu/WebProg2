<?php
class Config {
    public static function DB_HOST() { return "ecommerce-db"; } // Change if MySQL is on a different server
    public static function DB_NAME() { return "mydb"; }      // Your database name
    public static function DB_PORT() { return "3306"; }      // Default MySQL port (change if using a different one)
    public static function DB_USER() { return "root"; }      // MySQL username
    public static function DB_PASSWORD() { return ""; }      // MySQL password (empty by default in XAMPP/WAMP)
}