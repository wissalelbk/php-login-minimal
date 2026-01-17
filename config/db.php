<?php

/**
 * Configuration de la base de données
 * Compatible WAMP (Apache + MySQL + PHP)
 */

define("DB_HOST", "127.0.0.1");   // ou localhost
define("DB_NAME", "login");       // NOM DE LA BASE (celle que tu as créée)
define("DB_USER", "root");        // utilisateur MySQL (par défaut sur WAMP)
define("DB_PASS", "");            // mot de passe MySQL (vide sur WAMP)
define("DB_PORT", "3306");         // port MySQL

/**
 * Paramètres généraux
 */

define("COOKIE_RUNTIME", 1209600); // 2 semaines
define("COOKIE_DOMAIN", "");       // laisser vide en local
define("COOKIE_PATH", "/");

define("EMAIL_USE_SMTP", false);
define("EMAIL_SMTP_HOST", "");
define("EMAIL_SMTP_AUTH", false);
define("EMAIL_SMTP_USERNAME", "");
define("EMAIL_SMTP_PASSWORD", "");
define("EMAIL_SMTP_PORT", 25);
define("EMAIL_SMTP_ENCRYPTION", "");

