<?php
session_start();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .section-header {
            background-color: #4a6ef1;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            display: flex;
            align-items: center;
        }

        .section-header i {
            margin-right: 10px;
        }

        .box {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .btn-reset {
            background-color: #f44336;
            color: white;
        }

        .btn-bayar {
            background-color: #28a745;
            color: white;
        }

        .btn-print {
            background-color: #6c757d;
            color: white;
        }

        .btn-dashboard {
            background-color: #007bff;
            color: white;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="p-4 bg-light">
    <div class="container">
        <h3 class="mb-4">Keranjang Penjualan</h3>

        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="section-header">
                        <a href="add-transaksi.php" class="text-white text-decoration-none">
                            <i class="bi bi-search"></i> Cari Barang
                        </a>
                    </div>
                    <div class="p-3 d-flex">
                        <form method="post" class="d-flex w-100">
                            <input type="text" name="search_id" class="form-control me-2" placeholder="Masukan : Kode / Nama Barang [ENTER]">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="section-header">
                        <i class="bi bi-list"></i> Hasil Pencarian
                    </div>
                    <div class="p-3">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search_id'])) {
                            $conn = new mysqli("localhost", "root", "", "kaisr");

                            if ($conn->connect_error) {
                                die("Koneksi gagal: " . $conn->connect_error);
                            }

                            $searchId = $_POST['search_id'];
                            $stmt = $conn->prepare("SELECT * FROM tbl_produk WHERE nama_produk LIKE ? OR barcode = ?");
                            $likeSearch = "%" . $searchId . "%";
                            $stmt->bind_param("ss", $likeSearch, $searchId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='mb-2 d-flex justify-content-between align-items-center'>";
                                    echo "<span>" . htmlspecialchars($row['nama_produk']) . "</span>";
                                    echo "<form method='post' action='add-transaksi.php' class='d-inline'>";
                                    echo "<input type='hidden' name='produk_id' value='" . htmlspecialchars($row['produk_id']) . "'>";
                                    echo "<input type='hidden' name='nama_produk' value='" . htmlspecialchars($row['nama_produk']) . "'>";
                                    echo "<input type='hidden' name='harga_jual' value='" . htmlspecialchars($row['harga_jual']) . "'>";
                                    echo "<button type='submit' class='btn btn-success btn-sm'>Tambah</button>";
                                    echo "</form>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p class='text-danger'>Produk tidak ditemukan.</p>";
                            }

                            $stmt->close();
                            $conn->close();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="section-header justify-content-between">
                <div><i class="bi bi-cart"></i> KASIR</div>
                <form method="post" action="">
                    <button type="submit" name="reset_keranjang" class="btn btn-reset">RESET KERANJANG</button>
                </form>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_keranjang'])) {
                    unset($_SESSION['keranjang']); // Menghapus semua item di keranjang
                    header("Location: keranjang.php"); // Redirect untuk mencegah form resubmission
                    exit;
                }
                ?>
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal</label>
                    <?php
                    date_default_timezone_set('Asia/Jakarta');
                    ?>
                    <input type="text" class="form-control" value="<?php echo date('d F Y, H:i'); ?>" readonly>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Isi keranjang belanja -->
                        <?php
                        if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
                            $no = 1;
                            $mergedItems = [];

                            // Gabungkan item dengan nama yang sama
                            foreach ($_SESSION['keranjang'] as $item) {
                                $nama = $item['nama'];
                                if (isset($mergedItems[$nama])) {
                                    $mergedItems[$nama]['jumlah'] += $item['jumlah'];
                                } else {
                                    $mergedItems[$nama] = $item;
                                }
                            }

                            // Tampilkan item yang sudah digabung
                            foreach ($mergedItems as $item) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($item['nama']) . "</td>";
                                echo "<td>" . htmlspecialchars($item['jumlah'] ?? 0) . "</td>";
                                echo "<td>" . htmlspecialchars($item['harga'] * ($item['jumlah'] ?? 0)) . "</td>";
                                echo "<td><a href='hapus-item.php?id=" . urlencode($item['id']) . "' class='btn btn-danger btn-sm'>Hapus</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Keranjang kosong</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <form action="buat-transaksi.php" id="buat-transaksi" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nomor Member (Opsional)</label>
                        <input type="text" name="nomor_member" class="form-control" placeholder="Masukkan nomor member jika ada">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Total Semua</label>
                            <?php
                            $totalSemua = 0;
                            if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
                                foreach ($_SESSION['keranjang'] as $item) {
                                    $totalSemua += $item['harga'] * ($item['jumlah'] ?? 0);
                                }
                            }
                            ?>
                            <input type="text" id="totalBelanja" class="form-control" value="<?php echo $totalSemua; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bayar</label>
                            <div class="input-group">
                                <input type="text" name="bayar" class="form-control">
                                <button type="button" class="btn btn-bayar">Bayar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kembali</label>
                            <input type="text" class="form-control" placeholder="Kembali" readonly>
                        </div>
                        <div class="col-md-6 d-flex align-items-end justify-content-end">
                            <button class="btn btn-print" id="scan-barcode-btn"><i class="bi bi-upc-scan"></i> Scan Barcode</button>
                        </div>
                    </div>

                </form>


            </div>
        </div>
        <a href="../transaksi/daftar-transaksi.php" class="btn btn-dashboard"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        document.querySelector('.btn-bayar').addEventListener('click', function() {
            // Ambil total belanja dari input (readonly)
            // const totalBelanja = parseFloat(document.querySelector('input[readonly]').value.replace(/[^0-9]/g, '')) || 0;
            let totalBelanja = document.getElementById('totalBelanja').value;
            // Ambil jumlah uang yang dimasukkan user
            const uangDibayar = parseFloat(document.querySelector('.input-group input').value.replace(/[^0-9]/g, '')) || 0;

            // Validasi input
            if (isNaN(uangDibayar) || uangDibayar <= 0) {
                alert('Masukkan jumlah uang yang valid!');
                return;
            }

            console.log('Total Belanja:', totalBelanja);
            console.log('Uang Dibayar:', uangDibayar);

            // Cek apakah uang cukup
            if (uangDibayar < totalBelanja) {
                alert('Uang yang Anda masukkan kurang!');
            } else {
                const kembalian = uangDibayar - totalBelanja;

                // Tampilkan kembalian di input "Kembali"
                document.querySelector('input[readonly][placeholder="Kembali"]').value = `Rp ${kembalian.toLocaleString('id-ID')}`;

                if (kembalian === 0) {
                    alert('Pembayaran berhasil! Terima kasih.');
                } else {
                    alert(`Pembayaran berhasil! Kembalian Anda: Rp ${kembalian.toLocaleString('id-ID')}`);
                }

                document.getElementById('buat-transaksi').submit(); // Kirim form
                // Reset keranjang (opsional)
                // localStorage.removeItem('keranjang'); // Jika menggunakan LocalStorage
                // window.location.reload(); // Refresh halaman
            }
        });
    </script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        document.getElementById('scan-barcode-btn').addEventListener('click', function(e) {
            e.preventDefault();
            // Modal atau div untuk scanner
            let scannerDiv = document.createElement('div');
            scannerDiv.id = 'barcode-scanner-modal';
            scannerDiv.style.position = 'fixed';
            scannerDiv.style.top = '0';
            scannerDiv.style.left = '0';
            scannerDiv.style.width = '100vw';
            scannerDiv.style.height = '100vh';
            scannerDiv.style.background = 'rgba(0,0,0,0.7)';
            scannerDiv.style.display = 'flex';
            scannerDiv.style.alignItems = 'center';
            scannerDiv.style.justifyContent = 'center';
            scannerDiv.style.zIndex = '9999';
            scannerDiv.innerHTML = `
                <div style="background:#fff;padding:20px;border-radius:8px;position:relative;">
                    <div id="reader" style="width:320px;height:320px;"></div>
                    <button id="close-scanner" style="position:absolute;top:10px;right:10px;" class="btn btn-danger btn-sm">Tutup</button>
                </div>
            `;
            document.body.appendChild(scannerDiv);
            const html5QrCode = new Html5Qrcode("reader");
            let scannerRunning = false;
            html5QrCode.start(
                { facingMode: "user" },
                { fps: 10, qrbox: 300, formatsToSupport: [
                    { format: Html5QrcodeSupportedFormats.QR_CODE, weightage: 0 },
                    { format: Html5QrcodeSupportedFormats.EAN_13, weightage: 1 },
                    { format: Html5QrcodeSupportedFormats.EAN_8, weightage: 2 },
                    { format: Html5QrcodeSupportedFormats.UPC_A, weightage: 3 },
                    { format: Html5QrcodeSupportedFormats.UPC_E, weightage: 4 }
                    , { format: Html5QrcodeSupportedFormats.CODE_128, weightage: 5 }
                ] },
                (decodedText, decodedResult) => {
                    if (scannerRunning) {
                        html5QrCode.stop().then(() => {
                            scannerRunning = false;
                            if (document.body.contains(scannerDiv)) {
                                document.body.removeChild(scannerDiv);
                            }
                            let input = document.querySelector('input[name="search_id"]');
                            input.value = decodedText;
                            input.form.submit();
                        });
                    }
                },
                (errorMessage) => {
                    // ignore scan errors
                }
            ).then(() => {
                scannerRunning = true;
            }).catch(function(err) {
                alert('Tidak dapat mengakses kamera: ' + err);
                if (document.body.contains(scannerDiv)) {
                    document.body.removeChild(scannerDiv);
                }
            });
            document.getElementById('close-scanner').onclick = function() {
                if (scannerRunning) {
                    html5QrCode.stop().then(() => {
                        scannerRunning = false;
                        if (document.body.contains(scannerDiv)) {
                            document.body.removeChild(scannerDiv);
                        }
                    });
                } else {
                    if (document.body.contains(scannerDiv)) {
                        document.body.removeChild(scannerDiv);
                    }
                }
            };
        });
    </script>
</body>

</html>