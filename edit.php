<?php include 'layout/header.php' ?>
<div class="container mt-4 pb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white text-center">
                    <h2>Edit Lokasi</h2>
                </div>
                <?php
                include 'koneksi.php';

                $id = $_GET['id'];

                $query = "SELECT * FROM locations WHERE id = $id";
                $result = mysqli_query($koneksi, $query);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $name = $row['name'];
                    $latitude = $row['latitude'];
                    $longitude = $row['longitude'];
                    $zoom = $row['zoom'];
                    $description = $row['description'];
                    $category_id = $row['category_id'];
                    $image = $row['image'];
                ?>
                    <div class="card-body">
                        <form action="update" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lokasi:</label><br>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude:</label><br>
                                <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo $latitude; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude:</label><br>
                                <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo $longitude; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="zoom" class="form-label">Zoom:</label><br>
                                <input type="number" class="form-control" id="zoom" name="zoom" step="any" value="<?php echo $zoom; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Alamat:</label><br>
                                <textarea id="description" class="form-control" name="description" rows="4" readonly><?php echo $description; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Lokasi:</label><br>
                                <input type="file" class="form-control" id="image" name="image"><br>
                                <img src="uploads/<?php echo $image; ?>" width="100">
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori:</label><br>
                                <select id="category" class="form-control" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php
                                    $query = "SELECT * FROM categories";
                                    $result_categories = mysqli_query($koneksi, $query);

                                    if (mysqli_num_rows($result_categories) > 0) {
                                        while ($row_category = mysqli_fetch_assoc($result_categories)) {
                                            $selected = ($row_category['id'] == $category_id) ? 'selected' : '';
                                            echo "<option value='" . $row_category['id'] . "' $selected>" . $row_category['name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <input type="submit" class="btn btn-outline-primary btn-sm w-100" value="Update Lokasi">
                        </form>
                    <?php
                } else {
                    echo "Data lokasi tidak ditemukan.";
                }

                mysqli_close($koneksi);
                    ?>
                    </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah form untuk submit langsung

            Swal.fire({
                title: 'Apakah anda yakin dengan perubahan ini?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                icon: 'question'

            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form jika user konfirmasi
                }
            });
        });
    });
</script>
<?php include 'layout/footer.php' ?>