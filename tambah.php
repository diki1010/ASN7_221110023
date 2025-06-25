<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html,
        body {
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
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-box-seam"></i> Inventaris Barang</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-box"></i> Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="stok/index.php"><i class="bi bi-layers"></i> Stok</a></li>
                    <li class="nav-item"><a class="nav-link" href="kategori/index.php"><i class="bi bi-tags"></i> Kategori</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <main class="container-fluid px-4 px-md-5 mb-4 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 text-dark fw-semibold">Tambah Barang</h5>
            <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <?php
        if (isset($_POST['simpan'])) {
            $kode = $_POST['kode'];
            $nama = $_POST['nama'];
            $kategori = $_POST['kategori'];
            $harga = $_POST['harga'];
            $jumlah = $_POST['jumlah'];

            $query_barang = "INSERT INTO barang (kode_barang, nama_barang, id_kategori, harga) 
                         VALUES ('$kode', '$nama', $kategori, $harga)";
            $sukses_barang = mysqli_query($conn, $query_barang);

            if ($sukses_barang && $jumlah > 0) {
                $tanggal = date('Y-m-d');
                $query_stok = "INSERT INTO stok (kode_barang, tanggal, jumlah, tipe) 
                           VALUES ('$kode', '$tanggal', $jumlah, 'masuk')";
                mysqli_query($conn, $query_stok);
            }

            if ($sukses_barang) {
                header("Location: index.php?msg=Barang berhasil ditambahkan.");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Gagal menambahkan barang: " . mysqli_error($conn) . "</div>";
            }
        }
        ?>

        <div class="card shadow-sm border">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-2">
                        <label for="kode" class="form-label">Kode Barang</label>
                        <input type="text" class="form-control" name="kode" id="kode" required>
                    </div>
                    <div class="mb-2">
                        <label for="nama" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>
                    <div class="mb-2">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select name="kategori" id="kategori" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM kategori");
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="harga" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" id="harga" required>
                    </div>
                    <div class="mb-2">
                        <label for="jumlah" class="form-label">Stok Awal</label>
                        <input type="number" class="form-control" name="jumlah" id="jumlah" value="0" required>
                        <div class="form-text">Jika 0, maka stok awal tidak dicatat.</div>
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

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="mb-0">&copy; <?= date('Y') ?> Sistem Inventaris Barang</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>