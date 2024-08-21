<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];

    // Query untuk menyimpan kategori ke database
    $query = "INSERT INTO categories (name) VALUES ('$category')";

    if (mysqli_query($koneksi, $query)) {
        echo "Kategori berhasil ditambahkan";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);

    // Redirect ke halaman tambah kategori
    header("Location: tabel-kategori");
}
