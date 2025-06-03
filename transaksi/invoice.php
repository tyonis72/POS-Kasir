<?php
// filepath: c:\xampp\htdocs\usk-kasir\transaksi\invoice.php
include '../config/config.php'; // Pastikan file konfigurasi database sudah di-include

// Ambil kode transaksi dari URL
$kode_transaksi = $_GET['kode'] ?? null;

if (!$kode_transaksi) {
    echo "<script>alert('Kode transaksi tidak ditemukan!'); window.location.href='transaksi.php';</script>";
    exit;
}

// Ambil data transaksi berdasarkan kode transaksi
$query_transaksi = "
    SELECT t.*, u.fullname AS kasir, m.nama_member, m.no_hp 
    FROM tbl_transaksi t
    LEFT JOIN tbl_user u ON t.user_id = u.userid
    LEFT JOIN tbl_member m ON t.member_id = m.member_id
    WHERE t.kode_transaksi = ?";
$stmt = $connection->prepare($query_transaksi);
$stmt->bind_param("s", $kode_transaksi);
$stmt->execute();
$result_transaksi = $stmt->get_result();
$transaksi = $result_transaksi->fetch_assoc();

if (!$transaksi) {
    echo "<script>alert('Transaksi tidak ditemukan!'); window.location.href='transaksi.php';</script>";
    exit;
}

// Ambil detail transaksi
$query_detail = "
    SELECT d.*, p.nama_produk 
    FROM tbl_detail_transaksi d
    JOIN tbl_produk p ON d.produk_id = p.produk_id
    WHERE d.transaksi_id = ?";
$stmt_detail = $connection->prepare($query_detail);
$stmt_detail->bind_param("i", $transaksi['transaksi_id']);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result();
$details = $result_detail->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 5px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .invoice-header p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <h1>Struk Transaksi</h1>
            <p>Kode Transaksi: <?= htmlspecialchars($transaksi['kode_transaksi']); ?></p>
            <p>Tanggal: <?= date('d F Y, H:i', strtotime($transaksi['tanggal'])); ?></p>
        </div>

        <div class="mb-3">
            <strong>Kasir:</strong> <?= htmlspecialchars($transaksi['kasir']); ?><br>
            <?php if (!empty($transaksi['nama_member'])): ?>
                <strong>Member:</strong> <?= htmlspecialchars($transaksi['nama_member']); ?><br>
                <strong>No HP:</strong> <?= htmlspecialchars($transaksi['no_hp']); ?><br>
            <?php endif; ?>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($details as $detail): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($detail['nama_produk']); ?></td>
                        <td>Rp <?= number_format($detail['harga'], 0, ',', '.'); ?></td>
                        <td><?= $detail['jumlah']; ?></td>
                        <td>Rp <?= number_format($detail['subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end">
            <p><strong>Total Harga:</strong> Rp <?= number_format($transaksi['total_harga'], 0, ',', '.'); ?></p>
            <p><strong>Bayar:</strong> Rp <?= number_format($transaksi['bayar'], 0, ',', '.'); ?></p>
            <p><strong>Kembalian:</strong> Rp <?= number_format($transaksi['kembalian'], 0, ',', '.'); ?></p>
            <?php if (!empty($transaksi['member_id'])): ?>
                <?php
                // Ambil poin member terbaru
                $q_poin = mysqli_query($connection, "SELECT poin FROM tbl_member WHERE member_id = " . intval($transaksi['member_id']));
                $row_poin = mysqli_fetch_assoc($q_poin);
                ?>
                <p><strong>Poin Member:</strong> <?= isset($row_poin['poin']) ? $row_poin['poin'] : 0; ?></p>
            <?php endif; ?>
        </div>

        <div class="text-center mt-4">
            <a
                <?php
                // Format nomor HP ke format internasional (62xxx)
                $wa_number = '';
                if (!empty($transaksi['no_hp'])) {
                    $no_hp = preg_replace('/[^0-9]/', '', $transaksi['no_hp']);
                    if (substr($no_hp, 0, 1) === '0') {
                        $wa_number = '62' . substr($no_hp, 1);
                    } elseif (substr($no_hp, 0, 2) === '62') {
                        $wa_number = $no_hp;
                    }
                }
                $wa_message = "Struk Transaksi\n";
                $wa_message .= "Kode: " . $transaksi['kode_transaksi'] . "\n";
                $wa_message .= "Tanggal: " . date('d F Y, H:i', strtotime($transaksi['tanggal'])) . "\n";
                $wa_message .= "Kasir: " . $transaksi['kasir'] . "\n";
                if (!empty($transaksi['nama_member'])) {
                    $wa_message .= "Member: " . $transaksi['nama_member'] . "\n";
                    $wa_message .= "No HP: " . $transaksi['no_hp'] . "\n";
                }
                $wa_message .= "Total: Rp " . number_format($transaksi['total_harga'], 0, ',', '.') . "\n";
                $wa_message .= "Bayar: Rp " . number_format($transaksi['bayar'], 0, ',', '.') . "\n";
                $wa_message .= "Kembalian: Rp " . number_format($transaksi['kembalian'], 0, ',', '.');
                ?>
                href="https://wa.me/<?= $wa_number ?>?text=<?= urlencode($wa_message) ?>"
                target="_blank"
                class="btn btn-success mb-2"
                style="margin-right:8px;"
            >
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" style="width:20px; height:20px; margin-right:6px; vertical-align:middle;">
                Kirim ke WhatsApp
            </a>
            <button class="btn btn-primary" onclick="window.print()">Print</button>
            <a href="daftar-transaksi.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</body>

</html>