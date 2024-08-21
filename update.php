<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $zoom = $_POST['zoom'];
    $description = $_POST['description'];
    $category_id = $_POST['category'];
    $image = $_FILES['image']['name'];

    // Jika ada gambar baru yang diupload
    if ($image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "Gambar " . basename($image) . " telah diupload.";

            // Query untuk update data lokasi termasuk gambar baru
            $query = "UPDATE locations SET name = '$name', latitude = '$latitude', longitude = '$longitude', zoom = '$zoom', description = '$description', image = '$image', category_id = '$category_id' WHERE id = $id";
        } else {
            echo "Maaf, terjadi kesalahan saat mengupload gambar.";
        }
    } else {
        // Query untuk update data lokasi tanpa mengganti gambar
        $query = "UPDATE locations SET name = '$name', latitude = '$latitude', longitude = '$longitude', zoom = '$zoom', description = '$description', category_id = '$category_id' WHERE id = $id";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "Data lokasi berhasil diperbarui";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);

    header("Location: admin");
}
