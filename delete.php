<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = "DELETE FROM locations WHERE id = $id";

if (mysqli_query($koneksi, $query)) {
    header("Location: admin");
    exit();
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
}

// Tutup koneksi database
mysqli_close($koneksi);
