<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login");
    exit();
}

$email = $_SESSION['email'];
?>

<?php include 'layout/header.php' ?>
<div class="card mb-4 mt-4">
    <div class="card-header">
        <h2>Kelola Kategori</h2>
    </div>
    <div class="card-body">
        <a href="add_category" class="btn btn-outline-success btn-sm mb-2"><i class="fa-solid fa-plus"></i> Tambah Kategori</a>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <tr class="table-secondary">
                    <th>NO</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
                <?php
                include 'koneksi.php';
                $query = "SELECT * FROM categories";
                $result = mysqli_query($koneksi, $query);

                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urut
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>";
                        echo "<a href='edit_category?id=" . $row['id'] . "' class='btn btn-outline-warning btn-sm'><i class='fas fa-edit'></i> Edit</a> ";
                        echo "<a href='delete_category?id=" . $row['id'] . "' class='btn btn-outline-danger btn-sm' onclick='return confirmDelete()'><i class='fas fa-trash-alt'></i> Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Tidak ada kategori ditemukan</td></tr>";
                }

                mysqli_close($koneksi);
                ?>
            </table>
        </div>
    </div>
</div>
<script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus kategori ini?");
    }
</script>
<?php include 'layout/footer.php' ?>