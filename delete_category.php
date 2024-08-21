<?php
include 'koneksi.php';

$id = $_GET['id'];

$query = "DELETE FROM categories WHERE id = $id";

if (mysqli_query($koneksi, $query)) {
    echo "Kategori berhasil dihapus";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
}

mysqli_close($koneksi);
header("Location: tabel-kategori");
