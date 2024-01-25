<?php
session_start();

include_once("../koneksi.php");

// Inisialisasi variabel $id_pasien dan $namaDokter
$id_pasien = $namaDokter = $no_antrian = "";

// Periksa apakah parameter id_pasien ada dalam URL
if (isset($_GET['id_pasien'])) {
    $_SESSION['id_pasien'] = $_GET['id_pasien'];
}

// Periksa apakah parameter id_jadwal ada dalam URL
if (isset($_GET['id_jadwal'])) {
    $_SESSION['id_jadwal'] = $_GET['id_jadwal'];
}

if (isset($_SESSION['id_pasien'])) {

} else {
    // Variabel $_SESSION['id_pasien'] belum tersimpan
    echo "id_pasien belum tersimpan";
}

if (isset($_SESSION['id_jadwal'])) {

} else {
    // Variabel $_SESSION['id_jadwal'] belum tersimpan
    echo "id_jadwal belum tersimpan";
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_keluhan'])) {
    if (isset($_SESSION['id_pasien']) && isset($_SESSION['id_jadwal'])) {
        $id_pasien = $_SESSION['id_pasien'];
        $id_jadwal = $_SESSION['id_jadwal'];
        $keluhan = $_POST['keluhan'];

        // Validasi nilai id_pasien dan id_jadwal
        $queryCheckPasien = "SELECT id FROM pasien WHERE id = ?";
        $stmtCheckPasien = $mysqli->prepare($queryCheckPasien);
        if ($stmtCheckPasien === false) {
            die("Error: " . htmlspecialchars($mysqli->error));
        }

        $stmtCheckPasien->bind_param("i", $id_pasien);
        $stmtCheckPasien->execute();
        $resultCheckPasien = $stmtCheckPasien->get_result();

        $queryCheckJadwal = "SELECT id FROM jadwal_periksa WHERE id = ?";
        $stmtCheckJadwal = $mysqli->prepare($queryCheckJadwal);
        if ($stmtCheckJadwal === false) {
            die("Error: " . htmlspecialchars($mysqli->error));
        }

        $stmtCheckJadwal->bind_param("i", $id_jadwal);
        $stmtCheckJadwal->execute();
        $resultCheckJadwal = $stmtCheckJadwal->get_result();

        if ($resultCheckPasien->num_rows > 0 && $resultCheckJadwal->num_rows > 0) {
            // Query untuk mendapatkan jumlah antrian pada hari tersebut
            $queryAntrian = "SELECT COUNT(*) AS total_antrian FROM daftar_poli WHERE id_jadwal = ?";
            $stmtAntrian = $mysqli->prepare($queryAntrian);
            if ($stmtAntrian === false) {
                die("Error: " . htmlspecialchars($mysqli->error));
            }

            $stmtAntrian->bind_param("i", $id_jadwal);
            $stmtAntrian->execute();
            $resultAntrian = $stmtAntrian->get_result();

            if ($resultAntrian->num_rows > 0) {
                $rowAntrian = $resultAntrian->fetch_assoc();
                $no_antrian = $rowAntrian['total_antrian'] + 1; // Nomor antrian baru
            } else {
                $no_antrian = 1; // Jika tidak ada antrian, nomor antrian dimulai dari 1
            }

            // Query untuk menyimpan informasi ke dalam tabel daftar_poli beserta nomor antrian
            $query = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            if ($stmt === false) {
                die("Error: " . htmlspecialchars($mysqli->error));
            }

            $stmt->bind_param("iisi", $id_pasien, $id_jadwal, $keluhan, $no_antrian);
            if ($stmt->execute()) {
                echo '<script>alert("Keluhan telah disimpan. Nomor Antrian Anda: ' . $no_antrian . '");</script>';
                echo '<script>window.location.href = "index.php";</script>';
                exit();
            } else {
                echo '<script>alert("Terjadi kesalahan: ' . $stmt->error . '");</script>';
            }
            
            
        } else {
            echo "ID Pasien atau ID Jadwal tidak valid.";
        }
    } else {
        echo "ID Pasien atau ID Jadwal tidak tersedia.";
    }
} else {
    if (isset($_SESSION['id_pasien'])) {
        $id_pasien = $_SESSION['id_pasien'];

        // Query untuk mendapatkan nama dokter
        $queryDokter = "SELECT nama_dokter FROM dokter WHERE id = ?";
        $stmtDokter = $mysqli->prepare($queryDokter);
        if ($stmtDokter === false) {
            die("Error: " . htmlspecialchars($mysqli->error));
        }

        $stmtDokter->bind_param("i", $_SESSION['id_dokter']);
        $stmtDokter->execute();
        $resultDokter = $stmtDokter->get_result();

        if ($resultDokter->num_rows > 0) {
            $rowDokter = $resultDokter->fetch_assoc();
            $namaDokter = $rowDokter['nama_dokter']; // Simpan nama dokter dalam variabel
        } else {
            $namaDokter = 'Nama Dokter Tidak Tersedia';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Poli Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your existing styles here */

        .mycare-sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 15px;
            background-color: #4267b2; /* Warna biru Facebook */
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
            background-color: #3a5795; /* Warna biru Facebook lebih gelap saat di-hover */
        }

        .mycare-sidebar .navbar-brand {
            font-size: 1.8rem;
            color: #fff;
            font-weight: bold;
            margin-bottom: 20px; /* Jarak antara brand dan link */
        }

        .mycare-dropdown-content {
            display: none;
            background-color: #3a5795; /* Warna biru Facebook pada dropdown */
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
            background-color: #29487d; /* Warna biru Facebook lebih gelap pada dropdown saat di-hover */
        }

        .mycare-dropdown:hover .mycare-dropdown-content {
            display: block;
        }

        .mycare-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
            width: calc(100% - 250px);
            float: right;
        }

        @media (max-width: 768px) {
            .mycare-sidebar {
                left: -250px;
            }

            .mycare-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="mycare-sidebar">
        <a class="navbar-brand" href="../index.php">My Care</a>
        <a href="../index.php"><i class="fas fa-home"></i> Home</a>
            <div class="mycare-dropdown">
                <a href="#"><i class="fas fa-bars"></i> Menu</a>
                <div class="mycare-dropdown-content">
                    <a href="daftar_poli.php?page=dokter"><i class="fas fa-user-md"></i> Mendaftar ke Poli</a>
                </div>
            </div>
                    <?php
                    if (isset($_SESSION['no_rm'])) {
                        ?>
                        <?php
                    }
                    ?>
                </ul>
                <?php
                if (isset($_SESSION['nama_pasien'])) {
                    ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">Logout (
                                <?php echo $_SESSION['nama_pasien'] ?>)
                            </a>
                        </li>
                    </ul>
                    <?php
                } else {
                    ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=loginPasien">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=registerPasien">Registrasi Pasien</a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
            </div>
        </div>
    </nav>

    <div class="mycare-content">
        <div class="container mt-5">
        <h2>Form Keluhan</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mb-3">
                <label for="keluhan" class="form-label">Keluhan anda</label>
                <textarea class="form-control" id="keluhan" name="keluhan" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_keluhan">Submit</button>
            <button class="btn btn-dark" onclick="window.location.href='daftar_poli.php'">Kembali</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>