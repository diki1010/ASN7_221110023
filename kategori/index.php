<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Kategori</title>
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
                        <a class="nav-link <?= $isStok ? 'text-success fw-semibold' : 'text-dark' ?>" href="../stok/index.php">
                            <i class="bi bi-layers"></i> Stok
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $isKategori ? 'text-success fw-semibold' : 'text-dark' ?>" href="./index.php">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <main class="container px-4 px-md-5 mb-4 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-0 text-dark fw-semibold">Data Kategori</h5>
                <small class="text-muted ">Menampilkan dan Manage Kategori Barang.</small>
            </div>
            <a href="tambah.php" class="btn btn-sm btn-outline-success"><i class="bi bi-plus-lg"></i> Tambah Kategori</a>
        </div>

        <?php
        // Flash message
        if (isset($_GET['msg'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                . htmlspecialchars($_GET['msg']) .
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }

        // Proses hapus
        if (isset($_GET['hapus'])) {
            $id = (int)$_GET['hapus'];
            $hapus = mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori = $id");
            if ($hapus) {
                header("Location: index.php?msg=Kategori berhasil dihapus.");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Gagal menghapus kategori (mungkin sedang digunakan).</div>";
            }
        }
        ?>

        <div class="card shadow-sm border">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center mb-0">
                        <thead class="table-success">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Nama Kategori</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>
                                <td>{$no}</td>
                                <td class='text-start'>{$row['nama_kategori']}</td>
                                <td>
                                    <a href='edit.php?id={$row['id_kategori']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i></a>
                                    <a href='index.php?hapus={$row['id_kategori']}' onclick=\"return confirm('Yakin ingin menghapus?')\" class='btn btn-sm btn-danger'><i class='bi bi-trash'></i></a>
                                </td>
                            </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="mb-0">&copy; <?= date('Y') ?> Sistem Inventaris Barang</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>