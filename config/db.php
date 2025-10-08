<?php
// Database connection for Survey
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'survey_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
