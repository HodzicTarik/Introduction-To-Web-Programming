<?php

// Aktiviraj prikaz grešaka tokom razvoja
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config
{
    // 📦 Database konekcija
    public static function DB_NAME() {
        return Config::get_env("DB_NAME", "findacar");
    }

    public static function DB_PORT() {
        return Config::get_env("DB_PORT", 3307);
    }

    public static function DB_USER() {
        return Config::get_env("DB_USER", 'root');
    }

    public static function DB_PASSWORD() {
        return Config::get_env("DB_PASSWORD", 'novasifra');
    }

    public static function DB_HOST() {
        return Config::get_env("DB_HOST", 'localhost');
    }

    public static function DB_CHARSET() {
        return Config::get_env("DB_CHARSET", "utf8mb4");
    }

    // 🔐 JWT Secret Key za tokene
    public static function JWT_SECRET() {
        return Config::get_env("JWT_SECRET", ',dpPL,Se%fM-UVQBwf/X0T&B!DF6%}');
    }

    // ⚙️ Helper funkcija za čitanje iz env varijabli, koristi default ako nije postavljeno
    public static function get_env($name, $default) {
        return isset($_ENV[$name]) && trim($_ENV[$name]) != "" ? $_ENV[$name] : $default;
    }

    /*
    // Primjer: lokalne konstante bez ENV varijabli
    public static function DB_HOST()      { return 'localhost'; }
    public static function DB_PORT()      { return 3307; } // prilagodi po XAMPP-u
    public static function DB_USER()      { return 'root'; }
    public static function DB_PASSWORD()  { return 'novasifra'; }
    public static function DB_CHARSET()   { return 'utf8'; }

    // 🔐 JWT Secret Key za tokene
    public static function JWT_SECRET()
    {
        // Preporučeno: barem 32 karaktera, random string
        return 'gF$#7SdfgJkL!28Asd@LpE91nM@#xZq9';
    }
*/
}