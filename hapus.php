<?php
include 'koneksi.php';

// Pastikan parameter kode_barang ada
if (!isset($_GET['kode'])) {
    header("Location: index.php");
    exit;
}

$kode = $_GET['kode'];

// Hapus stok terkait terlebih dahulu (jika ada)
mysqli_query($conn, "DELETE FROM stok WHERE kode_barang = '$kode'");

// Hapus barang
$hapus = mysqli_query($conn, "DELETE FROM barang WHERE kode_barang = '$kode'");

if ($hapus) {
    // Redirect ke index dengan sukses
    header("Location: index.php");
    exit;
} else {
    echo "<script>
        alert('Gagal menghapus barang: " . mysqli_error($conn) . "');
        window.location.href='index.php';
    </script>";
}
?>
