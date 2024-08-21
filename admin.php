<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login");
    exit();
}

$email = $_SESSION['email'];
?>

<?php include 'layout/header.php' ?>
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script>
    function confirmDelete(locationId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "delete.php?id=" + locationId;
            }
        })
    }
</script>

<?php
include 'koneksi.php';

// Menghitung total lokasi
$queryTotalLokasi = "SELECT COUNT(*) as total_lokasi FROM locations";
$resultTotalLokasi = mysqli_query($koneksi, $queryTotalLokasi);
$totalLokasi = mysqli_fetch_assoc($resultTotalLokasi)['total_lokasi'];

// Menghitung total kategori
$queryTotalKategori = "SELECT COUNT(*) as total_kategori FROM categories";
$resultTotalKategori = mysqli_query($koneksi, $queryTotalKategori);
$totalKategori = mysqli_fetch_assoc($resultTotalKategori)['total_kategori'];

mysqli_close($koneksi);
?>

<div class="row mb-4 mt-4">
    <div class="col-md-6">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Lokasi</h5>
                <p class="card-text"><?php echo $totalLokasi; ?> Lokasi Terdaftar</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Kategori</h5>
                <p class="card-text"><?php echo $totalKategori; ?> Kategori Tersedia</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h2>Daftar Lokasi</h2>
    </div>
    <div class="card-body">
        <a href="form" class="btn btn-outline-primary btn-sm mb-2"><i class="fa-solid fa-plus"></i> Tambah Data</a>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center" id="coba">
                <thead>
                    <tr class="table-secondary">
                        <th>No</th>
                        <th>Nama Lokasi</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Zoom</th>
                        <th>Alamat</th>
                        <th>Gambar</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $query = "SELECT locations.*, categories.name as category_name FROM locations LEFT JOIN categories ON locations.category_id = categories.id";
                    $result = mysqli_query($koneksi, $query);

                    if (mysqli_num_rows($result) > 0) {
                        $no = 1; // Inisialisasi variabel penghitung
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr class='align-middle'>";
                            echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urut
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['latitude'] . "</td>";
                            echo "<td>" . $row['longitude'] . "</td>";
                            echo "<td>" . $row['zoom'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                            echo "<td><img src='uploads/" . $row['image'] . "' width='100'></td>";
                            echo "<td>" . $row['category_name'] . "</td>";
                            echo "<td>
                                <a href='view_map?id=" . $row['id'] . "' class='btn btn-outline-success btn-sm m-1'>
                                    <i class='fas fa-map'></i> Lihat Peta
                                </a>  
                                <a href='edit?id=" . $row['id'] . "' class='btn btn-outline-warning btn-sm m-1'>
                                    <i class='fas fa-edit'></i> Edit
                                </a>  
                                <a href='#' class='btn btn-outline-danger btn-sm m-1' onclick='confirmDelete(" . $row['id'] . ")'>
                                    <i class='fas fa-trash-alt'></i> Hapus
                                </a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>Tidak ada data ditemukan</td></tr>";
                    }

                    mysqli_close($koneksi);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"></script>

<script>
    $('#coba').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
</script>

<?php include 'layout/footer.php' ?>