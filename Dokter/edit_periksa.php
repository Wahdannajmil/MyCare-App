<?php
// Lakukan pengecekan koneksi ke database dan set session jika belum ada
if (!isset($_SESSION)) {
    session_start();
}

// Sisipkan file koneksi.php
include_once("../koneksi.php");

// Mengambil informasi dari query string
if (isset($_GET['id']) && isset($_GET['nama_pasien']) && isset($_GET['keluhan']) && isset($_GET['no_antrian'])) {
    $id = $_GET['id'];
    $nama_pasien = $_GET['nama_pasien'];
    $keluhan = $_GET['keluhan'];
    $no_antrian = $_GET['no_antrian'];
} else {
    echo "Informasi pasien tidak ditemukan";
    exit();
}

// Query untuk mendapatkan data periksa
$query_periksa = "SELECT * FROM periksa WHERE id_daftar_poli = '$id'";
$result_periksa = $mysqli->query($query_periksa);

// Mendapatkan data periksa jika ada
$data_periksa = $result_periksa->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $catatan = $_POST['catatan'];
    $total_harga = $_POST['total_harga'];
    $status_periksa = isset($_POST['status_periksa']) ? $_POST['status_periksa'] : 'Belum diperiksa';

    // Periksa apakah data periksa dengan id_daftar_poli tertentu sudah ada
    $query_check_periksa = "SELECT * FROM periksa WHERE id_daftar_poli = '$id'";
    $result_check_periksa = $mysqli->query($query_check_periksa);

    if ($result_check_periksa->num_rows > 0) {
        // Jika data sudah ada, lakukan operasi UPDATE
        $query_update_periksa = "UPDATE periksa SET catatan='$catatan', biaya_periksa='$total_harga', status_periksa='$status_periksa' WHERE id_daftar_poli='$id'";

        if ($mysqli->query($query_update_periksa)) {
            echo "Data berhasil diperbarui di tabel 'periksa'";
        } else {
            echo "Error: " . $query_update_periksa . "<br>" . $mysqli->error;
        }
    } else {
        // Jika data tidak ada, tampilkan pesan bahwa data tidak ditemukan
        echo "Data dengan ID tersebut tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Periksa Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .mycare-sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 15px;
            background-color: #0171f9;
            color: #fff;
            transition: all 0.3s;
            z-index: 1;
            overflow-x: hidden;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .mycare-sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 1.2rem;
            color: #fff;
            display: block;
            transition: padding 0.3s;
        }

        .mycare-sidebar a:hover {
            padding-left: 20px;
            background-color: #0c8ff7;
        }

        .mycare-sidebar .navbar-brand {
            font-size: 1.8rem;
            color: #fff;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .mycare-dropdown-content {
            display: none;
            background-color: #3a5795;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            position: absolute;
        }

        .mycare-dropdown-content a {
            padding: 12px 16px;
            display: block;
            color: #fff;
            text-decoration: none;
        }

        .mycare-dropdown-content a:hover {
            background-color: #3a5795;
        }

        .mycare-dropdown:hover .mycare-dropdown-content {
            display: block;
        }

        .mycare-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        @media (max-width: 768px) {
            .mycare-sidebar {
                left: -250px;
            }

            .mycare-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="mycare-sidebar">
        <a class="navbar-brand" href="../index.php">My Care</a>
        <a href="../index.php"><i class="fas fa-home"></i> Home</a>
        <?php
        if (isset($_SESSION['nip'])) {
            ?>
            <div class="mycare-dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">Menu</a>
                                <div class="mycare-dropdown-content">
                                    <a class="dropdown-item" href="ubah_profil.php?page=ubah_profil">Ubah Profil Dokter</a>
                                    <a class="dropdown-item" href="atur_jadwal.php?page=atur_jadwal">Atur jadwal poli</a>
                                    <a class="dropdown-item" href="jadwal_periksa.php?page=jadwal_periksa">Jadwal Saya</a>
                                    <a class="dropdown-item" href="riwayat_pasien.php?page=poli">Cari Riwayat Pasien</a>
                                </div>

                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
                if (isset($_SESSION['nip'])) {
                    // Jika pengguna sudah login, tampilkan tombol "Logout"
                    ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">Logout
                                (
                                <?php echo isset($_SESSION['nama_dokter']) ? $_SESSION['nama_dokter'] : $_SESSION['nip'] ?>)
                            </a>
                        </li>
                    </ul>
                    <?php
                } else {
                    // Jika pengguna belum login, tampilkan tombol "Login" dan "Register"
                    ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=loginDokter">Login</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="index.php?page=registerDokter">Registrasi Pasien</a>
                        </li> -->
                    </ul>
                    <?php
                }
                ?>
                <!-- <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=registerAdmin">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=loginAdmin">Login</a>
                </li>
            </ul> -->
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Edit Periksa Pasien</h3>
                    </div>
                    <div class="card-body">
                        <form
                            action="proses_periksa.php?id=<?php echo $id; ?>&nama_pasien=<?php echo $nama_pasien; ?>&keluhan=<?php echo $keluhan; ?>&no_antrian=<?php echo $no_antrian; ?>"
                            method="POST">
                            <!-- Form untuk menampilkan data yang diambil dari database -->
                            <div class="mb-3">
                                <label for="nama_pasien" class="form-label">Nama Pasien</label>
                                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                                    value="<?php echo $nama_pasien; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="keluhan" class="form-label">Keluhan</label>
                                <textarea class="form-control" id="keluhan" name="keluhan" rows="3"
                                    readonly><?php echo $keluhan; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="no_antrian" class="form-label">Nomor Antrian</label>
                                <input type="text" class="form-control" id="no_antrian" name="no_antrian"
                                    value="<?php echo $no_antrian; ?>" readonly>
                            </div>
                            <!-- Form untuk mengedit data periksa -->
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3"
                                    required><?php echo isset($data_periksa['catatan']) ? $data_periksa['catatan'] : ''; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="obat" class="form-label">Pilih Obat</label>
                                <?php
                                // Query untuk mengambil daftar obat dari tabel 'obat'
                                $query_obat = "SELECT * FROM obat";
                                $result_obat = $mysqli->query($query_obat);
                                ?>
                                <div class="form-group">
                                    <?php
                                    // Menampilkan opsi checkbox untuk setiap obat
                                    while ($row_obat = $result_obat->fetch_assoc()) {
                                        echo '<div class="form-check">';
                                        echo '<input class="form-check-input" type="checkbox" name="obat[]" value="' . $row_obat['id'] . '" id="obat' . $row_obat['id'] . '">';
                                        echo '<label class="form-check-label" for="obat' . $row_obat['id'] . '">';
                                        echo $row_obat['nama_obat'] . ' - ' . $row_obat['kemasan'] . ' - Rp ' . $row_obat['harga'];
                                        echo '</label>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                <button type="button" class="btn btn-primary" id="hitungBtn">Hitung</button>

                            </div>
                            <div class="mb-3">
                                <h5>Total Harga:</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Obat</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Baris pertama dengan biaya periksa -->
                                        <tr>
                                            <td>Biaya Periksa</td>
                                            <td>Rp 150.000</td>
                                        </tr>
                                        <!-- Baris kedua untuk menampilkan harga obat yang dipilih -->
                                        <tr id="harga_obat">
                                            <td>Total Obat</td>
                                            <td>Rp 0</td>
                                        </tr>
                                        <!-- Baris ketiga untuk menampilkan total biaya -->
                                        <tr id="total_biaya">
                                            <td>Total Biaya</td>
                                            <td>Rp 150.000</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mb-3">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                    <input type="hidden" id="id_jadwal" name="id_jadwal" value="">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <script>
                        document.getElementById('hitungBtn').addEventListener('click', function () {
                            var selectedOptions = document.querySelectorAll('input[name="obat[]"]:checked');
                            var totalHargaObat = 0;

                            selectedOptions.forEach(function (option) {
                                var hargaObat = option.nextElementSibling.textContent.match(/Rp (\d+)/)[1];
                                totalHargaObat += parseInt(hargaObat);
                            });

                            var biayaPeriksa = 150000;
                            var totalHarga = biayaPeriksa + totalHargaObat;

                            var htmlObat = "<td>Total Obat</td><td>Rp " + totalHargaObat + "</td>";
                            document.getElementById('harga_obat').innerHTML = htmlObat;

                            var htmlBiaya = "<td>Total Biaya</td><td>Rp " + totalHarga + "</td>";
                            document.getElementById('total_biaya').innerHTML = htmlBiaya;

                            // Simpan total harga ke dalam hidden input untuk dikirimkan ke proses_periksa.php
                            var inputTotalHarga = document.createElement("input");
                            inputTotalHarga.type = "hidden";
                            inputTotalHarga.name = "total_harga";
                            inputTotalHarga.value = totalHarga;
                            document.getElementById('total_biaya').appendChild(inputTotalHarga);
                        });
                        var hitungClicked = false;

                        document.getElementById('hitungBtn').addEventListener('click', function () {
                            // ... (Script untuk menghitung total harga)

                            // Set variabel hitungClicked menjadi true ketika tombol Hitung diklik
                            hitungClicked = true;
                        });

                        document.querySelector('form').addEventListener('submit', function (event) {
                            // Hentikan tindakan default formulir
                            event.preventDefault();

                            // Periksa apakah tombol Hitung sudah diklik sebelumnya
                            if (!hitungClicked) {
                                // Jika belum, berikan pesan kepada pengguna
                                alert('Harap klik tombol "Hitung" terlebih dahulu sebelum menyimpan perubahan.');
                            } else {
                                // Jika sudah, lanjutkan dengan mengirimkan formulir
                                this.submit();
                            }
                        });
                        document.getElementById('hitungBtn').addEventListener('click', function () {
                            // Mendapatkan nilai id_jadwal yang dipilih dari elemen select dengan id 'jadwal'
                            var idJadwal = document.getElementById('jadwal').value;

                            // Mengubah nilai input hidden dengan id 'id_jadwal' sesuai dengan id_jadwal yang dipilih
                            document.getElementById('id_jadwal').value = idJadwal;
                            // ... (Script JavaScript yang sudah ada) ...
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>