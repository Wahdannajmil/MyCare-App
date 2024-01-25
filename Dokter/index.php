<?php
if (!isset($_SESSION)) {
    session_start();
}

include_once("../koneksi.php");

if (isset($_SESSION['nip']) && isset($koneksi)) {
    $nip = $_SESSION['nip'];
    $query = "SELECT nama_dokter FROM dokter WHERE nip = '$nip'";
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $nama_dokter = $row['nama_dokter'];
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
            <div class="mycare-content col-md-9">
                <?php
                if (isset($_GET['page'])) {
                    include($_GET['page'] . ".php");
                } else {
                    echo "<div class='container mt-5'>
                            <h2>Selamat Datang di Sistem Informasi Poliklinik";
                    if (isset($_SESSION['nip'])) {
                        echo ", Dokter " . $_SESSION['nama_dokter'] . "</h2><hr>";
                    } else {
                        echo "</h2><hr>Silakan Login untuk menggunakan NIP dan Password yang sudah diberikan oleh Admin, apabila belum memiliki akun silahkan hubungi Admin.";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>