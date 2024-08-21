<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $zoom = $_POST['zoom'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $image = $_FILES['image']['name'];

    if ($image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "Gambar " . basename($image) . " telah diupload.";
        } else {
            echo "Maaf, terjadi kesalahan saat mengupload gambar.";
        }
    }

    // Query untuk menyimpan lokasi ke database
    $query = "INSERT INTO locations (name, latitude, longitude, zoom, description, image, category_id) VALUES ('$name', '$latitude', '$longitude', '$zoom', '$description', '$image', '$category_id')";

    if (mysqli_query($koneksi, $query)) {
        echo "Lokasi berhasil ditambahkan";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);

    header("Location: admin");
}
