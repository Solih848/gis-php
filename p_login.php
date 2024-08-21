<?php
include 'koneksi.php';
// Ambil nilai dari form login
$email = $_POST['email'];
$password = $_POST['password'];

// Lindungi dari SQL injection
$email = mysqli_real_escape_string($koneksi, $email);
$password = mysqli_real_escape_string($koneksi, $password);

// Query untuk memeriksa apakah email dan password cocok
$sql = "SELECT id FROM users WHERE email = '$email' AND password = '$password'";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    // Login berhasil
    session_start();
    $_SESSION['email'] = $email;
    header("Location: tabel_siswa.php"); // Redirect ke halaman users
} else {
    // Login gagal
    echo "Email atau password salah.";
}

$koneksi->close();
