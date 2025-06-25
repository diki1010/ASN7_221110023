<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Stok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-light text-dark shadow-sm border-bottom px-2 mb-3 px-md-4">
    <div class="container-fluid px-3">
        <a class="navbar-brand fw-bold" href="../index.php"><i class="bi bi-box-seam"></i> Inventaris Barang</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php"><i class="bi bi-box"></i> Barang</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php"><i class="bi bi-layers"></i> Stok</a></li>
                <li class="nav-item"><a class="nav-link" href="../kategori/index.php"><i class="bi bi-tags"></i> Kategori</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container-fluid px-4 px-md-5 mb-4 mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-dark fw-semibold">Tambah Data Stok</h5>
        <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php
    if (isset($_POST['simpan'])) {
        $kode = $_POST['kode_barang'];
        $tanggal = $_POST['tanggal'];
        $jumlah = $_POST['jumlah'];
        $tipe = $_POST['tipe'];

        $simpan = mysqli_query($conn, "INSERT INTO stok (kode_barang, tanggal, jumlah, tipe) VALUES ('$kode', '$tanggal', $jumlah, '$tipe')");
        if ($simpan) {
            header("Location: index.php?msg=Data stok berhasil ditambahkan.");
            exit;
        } else {
            echo "<div class='alert alert-danger mt-3'>Gagal menyimpan data.</div>";
        }
    }
    ?>

    <div class="card shadow-sm border">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Kode Barang</label>
                    <select name="kode_barang" class="form-select" required>
                        <option value="">Pilih Barang</option>
                        <?php
                        $barang = mysqli_query($conn, "SELECT * FROM barang");
                        while ($b = mysqli_fetch_assoc($barang)) {
                            echo "<option value='{$b['kode_barang']}'>{$b['kode_barang']} - {$b['nama_barang']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe</label>
                    <select name="tipe" class="form-select" required>
                        <option value="masuk">Masuk</option>
                        <option value="keluar">Keluar</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" name="simpan" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-0">&copy; <?= date('Y') ?> Sistem Inventaris Barang</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
