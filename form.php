<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
?>

<?php include 'layout/header.php' ?>
<div class="container mt-4 pb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white text-center">
                    <h2>Form Tambah Lokasi</h2>
                </div>
                <div class="card-body">
                    <form action="process" method="POST" enctype="multipart/form-data" id="locationForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lokasi:</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude:</label>
                            <input type="text" id="latitude" name="latitude" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude:</label>
                            <input type="text" id="longitude" name="longitude" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="zoom" class="form-label">Zoom:</label>
                            <input type="number" id="zoom" name="zoom" step="any" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Alamat:</label>
                            <textarea id="description" name="description" rows="4" class="form-control" readonly></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Lokasi:</label>
                            <input type="file" id="image" name="image" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori:</label><br>
                            <select id="category" name="category" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                <?php
                                include 'koneksi.php';
                                $query = "SELECT * FROM categories";
                                $result = mysqli_query($koneksi, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                }

                                mysqli_close($koneksi);
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-outline-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Function untuk update deskripsi berdasarkan koordinat
    function updateDescription() {
        var latitude = document.getElementById('latitude').value;
        var longitude = document.getElementById('longitude').value;

        if (latitude && longitude) {
            fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + latitude + '&lon=' + longitude)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('description').value = data.display_name;
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Panggil fungsi updateDescription saat nilai latitude atau longitude berubah
    document.getElementById('latitude').addEventListener('change', updateDescription);
    document.getElementById('longitude').addEventListener('change', updateDescription);
</script>


<?php include 'layout/footer.php' ?>