<?php

function healthcheckSuccess(): void
{
    http_response_code(200);
    exit;
}

function healthcheckFail(): void
{
    http_response_code(500);
    exit;
}

function executeHealthcheck(): void
{
    $dbName = $_ENV['DB_NAME'] ?? null;
    $dbUser = $_ENV['DB_USER'] ?? null;
    $dbPassword = $_ENV['DB_PASSWORD'] ?? null;
    $dbHost = $_ENV['DB_HOST'] ?? null;

    $connection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    if ($connection->connect_error) {
        healthcheckFail();
    }

    $result = $connection->query('SELECT ID FROM wp_posts LIMIT 1');
    $connection->close();

    if ($result->num_rows === 1) {
        healthcheckSuccess();
    }

    healthcheckFail();
}

executeHealthcheck();
