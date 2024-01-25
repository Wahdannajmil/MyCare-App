<!DOCTYPE html>
<html>

<head>
    <title>Profile Card - Data Pasien</title>
    <style>
        .card {
            border-radius: 18px;
            box-shadow: 0 4px 8px 0 rgba(12, 12, 12, 10);
            max-width: 400px;
            margin: auto;
            text-align: center;
            font-family: arial;
            padding-bottom: 15px;
            padding-top: 10px;
        }

        .title {
            color: grey;
            font-size: 18px;
        }

    </style>
</head>

<body>

    <h1 style="text-align:center; padding-bottom:10px">Profile Pasien</h1>
    <div class="card">
        <?php
        // Koneksi ke database
        $mysqli = new mysqli("localhost", "root", "", "poli");

        // Cek koneksi
        if ($mysqli->connect_error) {
            die("Koneksi database gagal: " . $mysqli->connect_error);
        }

        // Query untuk mengambil data pasien dari tabel
        $query = "SELECT * FROM pasien LIMIT 1"; // Ubah sesuai dengan kebutuhan Anda

        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            // Ambil data dari hasil query
            $row = $result->fetch_assoc();
            ?>
            <h1><?php echo $row['nama_pasien']; ?></h1>
            <p class="title">Nomor Rekam Medis: <?php echo $row['no_rm']; ?></p>
            <p>Alamat: <?php echo $row['alamat']; ?></p>
            <p>No. KTP: <?php echo $row['no_ktp']; ?></p>
            <p>No. HP: <?php echo $row['no_hp']; ?></p>
        <?php
        } else {
            echo "Data pasien tidak ditemukan";
        }

        $mysqli->close(); // Tutup koneksi ke database
        ?>
        <button class="btn btn-dark" onclick="window.location.href='index.php'">Kembali</button>
    </div>

</body>

</html>

