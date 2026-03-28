<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define("SITE_CONFIG", [
    "database" => [
        "host" => "sql100.infinityfree.com",
        "schema" => "if0_41253070_avalanche", 
        "username" => "if0_41253070",
        "password" => "5YhpSCxhgyk"
    ],
    "site" => [
        "name" => "Avalanche",
        "name_secondary" => "avalanche",
        "currencyName" => "AvaBux"
    ],
    "captcha" => [
        "siteKey" => "6LeMPZssAAAAAKsSRpmbnH1MCz0Xq_hPgoSsDep1",
        "privateKey" => "6LeMPZssAAAAAJ90JrV0SC65dm6pmbScMGANxb4h"
    ]
]);