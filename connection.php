<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hris_db";

// Membuat koneksi
$conn = new mysqli("localhost","root","","hris");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
