<?php
// config.php - update with your DB credentials
session_start();


$DB_HOST = '127.0.0.1';
$DB_NAME = 'mini_blog';
$DB_USER = 'root';
$DB_PASS = '';



try {
$pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
} catch (Exception $e) {
exit('Database connection failed: ' . $e->getMessage());
}


// helper: check if user logged in
function is_logged_in() {
return !empty($_SESSION['user_id']);
}


function current_user_id() {
return $_SESSION['user_id'] ?? null;
}


function current_username() {
return $_SESSION['username'] ?? null;
}