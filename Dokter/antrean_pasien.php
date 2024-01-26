<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once("../koneksi.php");
$id_dokter = null;

if (isset($_SESSION['nip'])) {
    $nip = $_SESSION['nip'];
    $query = "SELECT id FROM dokter WHERE nip = '$nip'";
    $result = $mysqli->query($query);

    if (!$result) {
        die("Query error: " . $mysqli->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_dokter = $row['id'];
    } else {
        echo "Data dokter tidak ditemukan";
        exit();
    }
}

// Aksi Hapus
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id_antrean_hapus = $_GET['id'];

    // Hapus antrean
    $query_hapus_antrean = "DELETE FROM daftar_poli WHERE id = $id_antrean_hapus";
    $result_hapus_antrean = $mysqli->query($query_hapus_antrean);

    if (!$result_hapus_antrean) {
        die("Query error: " . $mysqli->error);
    }

    // Redirect kembali ke halaman daftar_poli
    header("Location: daftar_poli.php");
    exit();
}

$query_daftar_pasien = "SELECT dp.id, p.nama_pasien, dp.keluhan, dp.no_antrian, dp.status_periksa
                        FROM daftar_poli dp 
                        JOIN pasien p ON dp.id_pasien = p.id 
                        WHERE dp.id_jadwal IN (SELECT id FROM jadwal_periksa WHERE id_dokter = '$id_dokter')
                        ORDER BY dp.no_antrian";

$result_daftar_pasien = $mysqli->query($query_daftar_pasien);

if (!$result_daftar_pasien) {
    die("Query error: " . $mysqli->error);
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

        .mycare-sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 15px;
            background-color: #4267b2; 
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
            background-color: #3a5795;
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
            background-color: #29487d; 
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
                    <?php
                    if (isset($_SESSION['nip'])) {
                        ?>
                                    <div class="mycare-dropdown">
                <a href="../index.php"><i class="fas fa-bars"></i> Menu</a>
                <div class="mycare-dropdown-content">
                                    <a class="dropdown-item" href="ubah_profil.php?page=ubah_profil">Ubah Profil Dokter</a>
                                    <a class="dropdown-item" href="atur_jadwal.php?page=atur_jadwal">Atur jadwal poli</a>
                                    <a class="dropdown-item" href="jadwal_periksa.php?page=antrean_pasien">Jadwal Saya</a>
                                    <a class="dropdown-item" href="riwayat_pasien.php?page=riwayat_pasien">Cari Riwayat Pasien</a>                                                </div>
            </div>
                        <?php
                    }
                    ?>
                </ul>
                <?php
                if (isset($_SESSION['nip'])) {
                    ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">Logout
                                (<?php echo isset($_SESSION['nama_dokter']) ? $_SESSION['nama_dokter'] : $_SESSION['nip'] ?>)
                            </a>
                        </li>
                    </ul>
                    <?php
                } else {
                    ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=loginDokter">Login</a>
                        </li>
                    </ul>
                    <?php
                }
                ?>
            </div>
        </div>
    </nav>
<div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Daftar Pasien Dokter</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($result_daftar_pasien->num_rows > 0) {
                            echo '<table class="table table-hover">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th scope="col">Nama Pasien</th>';
                            echo '<th scope="col">Keluhan</th>';
                            echo '<th scope="col">Nomor Antrian</th>';
                            echo '<th scope="col">Tindakan</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            while ($row_daftar_pasien = $result_daftar_pasien->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row_daftar_pasien['nama_pasien'] . '</td>';
                                echo '<td>' . $row_daftar_pasien['keluhan'] . '</td>';
                                echo '<td>' . $row_daftar_pasien['no_antrian'] . '</td>';

                                $query_cek_periksa = "SELECT * FROM periksa WHERE id_daftar_poli = " . $row_daftar_pasien['id'];
                                $result_cek_periksa = $mysqli->query($query_cek_periksa);

                                if (!$result_cek_periksa) {
                                    die("Query error: " . $mysqli->error);
                                }

                                echo '<td>';
                                if ($result_cek_periksa->num_rows > 0) {
                                    echo "<a href='edit_periksa.php?id=" . $row_daftar_pasien['id'] . "&nama_pasien=" . $row_daftar_pasien['nama_pasien'] . "&keluhan=" . $row_daftar_pasien['keluhan'] . "&no_antrian=" . $row_daftar_pasien['no_antrian'] . "' class='btn btn-warning ml-2'>Edit</a>";
                                    echo "<button class='btn btn-primary' disabled>Periksa Pasien</button>";
                                } else {
                                    echo "<a href='periksa_pasien.php?id=" . $row_daftar_pasien['id'] . "&nama_pasien=" . $row_daftar_pasien['nama_pasien'] . "&keluhan=" . $row_daftar_pasien['keluhan'] . "&no_antrian=" . $row_daftar_pasien['no_antrian'] . "' class='btn btn-primary mr-2'>Periksa Pasien</a>";
                                    // echo "<a href='daftar_poli.php?action=hapus&id=" . $row_daftar_pasien['id'] . "' class='btn btn-danger ms-2'>Hapus</a>";
                                }
                                echo '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p>Tidak ada pasien yang terdaftar</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>