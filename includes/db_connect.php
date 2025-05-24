<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'fried-frenzy';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("[PHP::MySQL] Connection failed: " . $conn->connect_error);
}
?> 