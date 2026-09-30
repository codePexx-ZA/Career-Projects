<?php
function getPdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $httpHost = $_SERVER["HTTP_HOST"] ?? "";
    $isLocal = strpos($httpHost, "localhost") !== false
        || strpos($httpHost, "127.0.0.1") !== false;

    if ($isLocal) {
        $host = getenv("DB_HOST") ?: "127.0.0.1";
        $dbName = getenv("DB_NAME") ?: "skilllink";
        $dbUser = getenv("DB_USER") ?: "root";
        $dbPass = getenv("DB_PASS") ?: "";
    } else {
        $host = getenv("DB_HOST") ?: "";
        $dbName = getenv("DB_NAME") ?: "";
        $dbUser = getenv("DB_USER") ?: "";
        $dbPass = getenv("DB_PASS") ?: "";
    }
    $charset = "utf8mb4";

    if (!$isLocal && ($host === "" || $dbName === "" || $dbUser === "" || $dbPass === "")) {
        throw new RuntimeException("Set DB_HOST, DB_NAME, DB_USER and DB_PASS for the live database.");
    }

    $dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

    return $pdo;
}
