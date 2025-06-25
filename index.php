<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Inventaris Barang</title>
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

        .table th,
        .table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        @media (max-width: 576px) {
            h5 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php
    $uri = $_SERVER['REQUEST_URI'];

    $isBarang   = strpos($uri, '/index.php') !== false && strpos($uri, '/stok') === false && strpos($uri, '/kategori') === false;
    $isStok     = strpos($uri, '/stok') !== false;
    $isKategori = strpos($uri, '/kategori') !== false;
    ?>
    <nav class="navbar navbar-expand-lg bg-light text-dark shadow-sm border-bottom px-2 mb-3 px-md-4">
        <div class="container-fluid px-3">
            <a class="navbar-brand fw-bold" href="/index.php">
                <i class="bi bi-box-seam"></i> Inventaris Barang
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link <?= $isBarang ? 'text-success fw-semibold' : 'text-dark' ?>" href="/index.php">
                            <i class="bi bi-box"></i> Barang
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $isStok ? 'text-success fw-semibold' : 'text-dark' ?>" href="./stok/index.php">
                            <i class="bi bi-layers"></i> Stok
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $isKategori ? 'text-success fw-semibold' : 'text-dark' ?>" href="./kategori/index.php">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>



    <main class="container-fluid px-4 px-md-5 mb-4 mt-3">

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h5 class="mb-0 text-dark fw-semibold">Data Barang</h5>
                <small class="text-muted">Menampilkan semua barang yang terdaftar dalam sistem.</small>
            </div>
            <a href="tambah.php" class="btn btn-sm btn-outline-success">
                <i class="bi bi-plus-lg"></i> Tambah
            </a>
        </div>



        <!-- Card Container -->
        <div class="card shadow-sm border">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-sm align-middle text-center mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $page = $page < 1 ? 1 : $page;
                            $offset = ($page - 1) * $limit;

                            // hitung total data
                            $total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM barang"));
                            $total_pages = ceil($total / $limit);

                            $query = "
                            SELECT b.*, k.nama_kategori,
                                COALESCE(SUM(CASE WHEN s.tipe = 'masuk' THEN s.jumlah ELSE 0 END), 0) 
                                - COALESCE(SUM(CASE WHEN s.tipe = 'keluar' THEN s.jumlah ELSE 0 END), 0) AS stok_saat_ini
                            FROM barang b
                            JOIN kategori k ON b.id_kategori = k.id_kategori
                            LEFT JOIN stok s ON b.kode_barang = s.kode_barang
                            GROUP BY b.kode_barang
                            ORDER BY b.kode_barang ASC
                            LIMIT $limit OFFSET $offset
                        ";

                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                <td>{$row['kode_barang']}</td>
                                <td class='text-center'>{$row['nama_barang']}</td>
                                <td>{$row['nama_kategori']}</td>
                                <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                                <td><span class='badge bg-info'>{$row['stok_saat_ini']}</span></td>
                                <td>
                                    <a href='edit.php?kode={$row['kode_barang']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i></a>
                                    <a href='hapus.php?kode={$row['kode_barang']}' onclick=\"return confirm('Yakin ingin menghapus?')\" class='btn btn-sm btn-danger'><i class='bi bi-trash'></i></a>
                                </td>
                            </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <form method="GET" class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0 small">Tampilkan</label>
                            <select name="limit" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                                <?php foreach ([5, 10, 25, 50] as $opt): ?>
                                    <option value="<?= $opt ?>" <?= $limit == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="small">data</span>
                            <?php if (isset($_GET['page'])): ?>
                                <input type="hidden" name="page" value="<?= $page ?>">
                            <?php endif; ?>
                        </form>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?limit=<?= $limit ?>&page=<?= $page - 1 ?>"></a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?limit=<?= $limit ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?limit=<?= $limit ?>&page=<?= $page + 1 ?>"></a>
                                </li>
                            </ul>
                        </nav>

                    </div>

                </div>
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