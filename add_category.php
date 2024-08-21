<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login");
    exit();
}

$email = $_SESSION['email'];
?>

<?php include 'layout/header.php' ?>
<div class="container mt-4 pb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h2>Tambah Kategori</h2>
                </div>
                <div class="card-body">
                    <form action="save_category.php" method="POST">
                        <div class="mb-3">
                            <label for="category" class="form-label">Nama Kategori:</label><br>
                            <input type="text" class="form-control" id="category" name="category" required>
                        </div>
                        <input type="submit" value="Tambah Kategori" class="btn btn-outline-primary w-100">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/footer.php' ?>