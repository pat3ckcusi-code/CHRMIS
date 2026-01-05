<?php

$host = "localhost";
$db   = "hrds";
$user = "root";
$pass = "";
$charset = "utf8";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => true,
];
//$pdo = new PDO("mysql:host=localhost;dbname=hchecklist", "imsd", "Janus@09179474815");
$pdo = new PDO($dsn, $user, $pass, $opt);
if (!$pdo) {
  die("cannot connect to the database");
}
