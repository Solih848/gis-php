<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $query = "UPDATE categories SET name='$name' WHERE id=$id";
    if (mysqli_query($koneksi, $query)) {
        header("Location: tabel-kategori");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($koneksi);
    }
}

$query = "SELECT * FROM categories WHERE id=$id";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "Kategori tidak ditemukan";
    exit();
}

mysqli_close($koneksi);
?>

<?php include 'layout/header.php' ?>
<div class="container mt-4 pb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white text-center">
                    <h2>Edit Kategori</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group mb-2">
                            <label for="name" class="form-label">Nama Kategori:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm">Simpan Perubahan</button>
                        <a href="tabel-kategori" class="btn btn-outline-danger btn-sm">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'layout/footer.php' ?>