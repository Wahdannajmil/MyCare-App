<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_GET['id_pasien'])) {
    $_SESSION['id_pasien'] = $_GET['id_pasien'];
}

include_once("../koneksi.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
        if (isset($_SESSION['nama_pasien'])) {
            ?>
            <div class="mycare-dropdown">
                <a href="#"><i class="fas fa-bars"></i> Menu</a>
                <div class="mycare-dropdown-content">
                    <a href="daftar_poli.php?page=dokter"><i class="fas fa-user-md"></i> Mendaftar ke Poli</a>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
        if (isset($_SESSION['nama_pasien'])) {
            ?>
            <a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout (<?php echo $_SESSION['nama_pasien'] ?>)</a>
        <?php
        } else {
            ?>
            <a href="index.php?page=loginPasien"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="index.php?page=registerPasien"><i class="fas fa-user-plus"></i> Registrasi Pasien</a>
        <?php
        }
        ?>
    </div>

    <div class="mycare-content">
        <?php
        if (isset($_GET['page'])) {
            include($_GET['page'] . ".php");
        } else {
            echo "<div class='container mt-5'>
                    <h2>Selamat Datang di MyCare Poliklinik</h2>
                    <p>MyCare adalah sistem informasi poliklinik yang dirancang untuk memudahkan Anda dalam melakukan pendaftaran poliklinik secara online. Dengan MyCare, Anda dapat:</p>
                    <ul>
                        <li>Mendaftar ke berbagai poliklinik tanpa harus datang ke rumah sakit secara langsung.</li>
                        <li>Melihat jadwal dokter dan memilih waktu yang sesuai dengan kenyamanan Anda.</li>
                        <li>Mengisi keluhan Anda secara online untuk mempermudah proses pelayanan dokter.</li>
                    </ul>
                    <p>Jika Anda sudah memiliki akun, silakan login untuk mengakses layanan MyCare. Jika belum memiliki akun, Anda dapat melakukan registrasi pada halaman Registrasi Pasien.</p>
                    </div>";
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXyST6ozvATL6u0DTdP4QF+eP9IhIjLgi5x6"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
