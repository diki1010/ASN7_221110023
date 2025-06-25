<?php include '../koneksi.php'; ?>
<?php
// Jumlah baris per halaman
$limit_options = [5, 10, 25, 50, 'semua'];
$limit = isset($_GET['limit']) && in_array($_GET['limit'], array_map('strval', $limit_options)) ? $_GET['limit'] : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($limit === 'semua') ? 0 : ($page - 1) * $limit;

// Query total data
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM stok");
$totalRow = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ($limit === 'semua') ? 1 : ceil($totalRow / $limit);

// Ambil data stok
$stokQuery = "
    SELECT s.*, b.nama_barang 
    FROM stok s 
    JOIN barang b ON s.kode_barang = b.kode_barang 
    ORDER BY s.tanggal DESC
";
if ($limit !== 'semua') {
    $stokQuery .= " LIMIT $limit OFFSET $offset";
}
$result = mysqli_query($conn, $stokQuery);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Stok Barang</title>
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
                        <a class="nav-link <?= $isBarang ? 'text-success fw-semibold' : 'text-dark' ?>" href="../index.php">
                            <i class="bi bi-box"></i> Barang
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $isStok ? 'text-success fw-semibold' : 'text-dark' ?>" href="./index.php">
                            <i class="bi bi-layers"></i> Stok
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $isKategori ? 'text-success fw-semibold' : 'text-dark' ?>" href="../kategori/index.php">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-4 px-md-5 mb-4 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-0 text-dark fw-semibold">Stok Barang</h5>
                <small class="text-muted ">Menampilkan dan Manage Stok barang.</small>
            </div>

            <a href="tambah.php" class="btn btn-sm btn-outline-succes"><i class="bi bi-plus-lg"></i> Tambah Stok</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) :
                                $badge = $row['tipe'] === 'masuk' ? 'success' : 'danger'; ?>
                                <tr>
                                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                    <td><?= $row['kode_barang'] ?></td>
                                    <td class="text-start"><?= $row['nama_barang'] ?></td>
                                    <td><?= $row['jumlah'] ?></td>
                                    <td><span class="badge bg-<?= $badge ?> text-uppercase"><?= $row['tipe'] ?></span></td>
                                    <td>
                                        <a href="edit.php?id=<?= $row['id_stok'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="hapus.php?id=<?= $row['id_stok'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Filter jumlah row -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <form method="get" class="d-flex align-items-center">
                <label class="me-2">Tampilkan</label>
                <select name="limit" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                    <?php foreach ($limit_options as $opt) :
                        $selected = ($limit == $opt) ? 'selected' : '';
                        $label = $opt === 'semua' ? 'Semua' : $opt;
                        echo "<option value='$opt' $selected>$label</option>";
                    endforeach; ?>
                </select>
                <label class="ms-2">baris</label>
            </form>

            <!-- Pagination -->
            <?php if ($limit !== 'semua' && $totalPages > 1): ?>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?limit=<?= $limit ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="mb-0">&copy; <?= date('Y') ?> Sistem Inventaris Barang</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>