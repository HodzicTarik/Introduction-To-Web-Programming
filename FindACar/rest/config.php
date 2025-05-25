<?php

// Aktiviraj prikaz grešaka tokom razvoja
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config
{
    // 📦 Database konekcija
    public static function DB_HOST()      { return 'localhost'; }
    public static function DB_PORT()      { return 3307; } // prilagodi po XAMPP-u
    public static function DB_NAME()      { return 'findacar'; }
    public static function DB_USER()      { return 'root'; }
    public static function DB_PASSWORD()  { return 'novasifra'; }
    public static function DB_CHARSET()   { return 'utf8'; }

    // 🔐 JWT Secret Key za tokene
    public static function JWT_SECRET()
    {
        // Preporučeno: barem 32 karaktera, random string
        return 'gF$#7SdfgJkL!28Asd@LpE91nM@#xZq9';
    }
}
