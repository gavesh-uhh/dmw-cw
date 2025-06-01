<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'fried_frenzy';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("[PHP::MySQL] Connection failed: " . $conn->connect_error);
}

?> 