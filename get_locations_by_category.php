<?php
include 'koneksi.php';

$category_id = $_GET['category_id'];

$query = "SELECT * FROM locations WHERE category_id = $category_id";
$result = mysqli_query($koneksi, $query);

$locations = array();

while ($row = mysqli_fetch_assoc($result)) {
    $locations[] = $row;
}

echo json_encode($locations);

mysqli_close($koneksi);
