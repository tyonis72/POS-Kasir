<?php
session_start();
// Koneksi ke database
require_once '../config/config.php';

// Query untuk mendapatkan data produk
$sql = "SELECT produk_id, nama_produk, harga_jual, gambar_produk FROM tbl_produk";
$result = $connection->query($sql);

if (isset($_POST['produk_id'])) {
    $produk_id = $_POST['produk_id'];

    // get from database
    $query = "SELECT * FROM tbl_produk WHERE produk_id = '$produk_id'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    // Cek dan ambil diskon aktif jika ada
    require_once '../modul/model-produk.php';
    $diskon = getActiveDiskonByProduk($produk_id);
    $harga_jual = $row['harga_jual'];
    $harga_setelah_diskon = $harga_jual;
    $diskon_id = null;
    if ($diskon) {
        if ($diskon['tipe_diskon'] == 'persen') {
            $harga_setelah_diskon = $harga_jual - ($harga_jual * $diskon['nilai'] / 100);
        } else {
            $harga_setelah_diskon = $harga_jual - $diskon['nilai'];
        }
        if ($harga_setelah_diskon < 0) $harga_setelah_diskon = 0;
        $diskon_id = $diskon['diskon_id'];
    }

    $produk = [
        'id' => $row['produk_id'],
        'nama' => $row['nama_produk'],
        'harga' => $harga_setelah_diskon,
        'jumlah' => 1, // Jumlah default 1
        'gambar' => $row['gambar_produk'],
        'diskon_id' => $diskon_id
    ];

    // Simpan produk ke dalam session keranjang
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    $_SESSION['keranjang'][] = $produk;

    echo "<script>alert('Produk berhasil ditambahkan ke keranjang!');</script>";
    echo "<script>window.location.href = 'keranjang.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Produk</h1>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            <img src="<?= $main_url ?>asset/image/<?= htmlspecialchars($row['gambar_produk']); ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($row['gambar_produk']); ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($row['nama_produk']); ?></h5>
                                <?php
                                // Cek diskon aktif
                                require_once '../modul/model-produk.php';
                                $diskon = getActiveDiskonByProduk($row['produk_id']);
                                $harga_jual = $row['harga_jual'];
                                $harga_setelah_diskon = $harga_jual;
                                if ($diskon) {
                                    if ($diskon['tipe_diskon'] == 'persen') {
                                        $harga_setelah_diskon = $harga_jual - ($harga_jual * $diskon['nilai'] / 100);
                                    } else {
                                        $harga_setelah_diskon = $harga_jual - $diskon['nilai'];
                                    }
                                    if ($harga_setelah_diskon < 0) $harga_setelah_diskon = 0;
                                }
                                ?>
                                <?php if ($diskon && $harga_setelah_diskon < $harga_jual): ?>
                                    <p class="card-text mb-1">
                                        <span class="text-danger fw-bold">Rp <?= htmlspecialchars(number_format($harga_setelah_diskon, 2)); ?></span>
                                        <span class="text-muted text-decoration-line-through ms-2">Rp <?= htmlspecialchars(number_format($harga_jual, 2)); ?></span>
                                    </p>
                                    <span class="badge bg-success mb-2">
                                        Diskon <?= $diskon['tipe_diskon'] == 'persen' ? $diskon['nilai'].'%' : 'Rp '.number_format($diskon['nilai'], 2) ?>
                                    </span>
                                <?php else: ?>
                                    <p class="card-text">Rp <?= htmlspecialchars(number_format($harga_jual, 2)); ?></p>
                                <?php endif; ?>
                                <form method="post" class="mt-auto">
                                    <input type="hidden" name="produk_id" value="<?= htmlspecialchars($row['produk_id']); ?>">
                                    <button id="btnTambahKeranjang" type="submit" class="btn btn-primary w-100">Tambah ke Keranjang</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Tidak ada produk tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>