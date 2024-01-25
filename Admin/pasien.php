<?php
include_once("../koneksi.php");

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=loginAdmin");
    exit;
}

if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {
        $ubah = mysqli_query($mysqli, "UPDATE pasien SET 
                                            nama_pasien = '" . $_POST['nama_pasien'] . "',
                                            alamat = '" . $_POST['alamat'] . "',
                                            no_ktp = '" . $_POST['no_ktp'] . "',
                                            no_hp = '" . $_POST['no_hp'] . "'
                                            // no_rm = '" . $_POST['no_rm'] . "'
                                            WHERE
                                            id = '" . $_POST['id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO pasien (nama_pasien, alamat, no_ktp, no_hp, no_rm) 
                                            VALUES (
                                                '" . $_POST['nama_pasien'] . "',
                                                '" . $_POST['alamat'] . "',
                                                '" . $_POST['no_ktp'] . "'
                                                '" . $_POST['no_hp'] . "'
                                                // '" . $_POST['no_rm'] . "'
                                            )");
    }
    echo "<script> 
                document.location='index.php?page=pasien';
                </script>";
}
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM pasien WHERE id = '" . $_GET['id'] . "'");
    }

    echo "<script> 
                document.location='index.php?page=pasien';
                </script>";
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Rumah Sakit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
<center><h2>Pasien</h2></center>
<br>
<div class="container">
    <!--Form Input Data-->

    <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
        <!-- Kode php untuk menghubungkan form dengan database -->
        <?php
        $nama_pasien = '';
        $alamat = '';
        $no_ktp = '';
        $no_hp = '';
        // $no_rm = '';
        if (isset($_GET['id'])) {
            $ambil = mysqli_query($mysqli, "SELECT * FROM pasien 
                    WHERE id='" . $_GET['id'] . "'");
            while ($row = mysqli_fetch_array($ambil)) {
                $nama_pasien = $row['nama_pasien'];
                $alamat = $row['alamat'];
                $no_ktp = $row['no_ktp'];
                $no_hp = $row['no_hp'];
                // $no_rm = $row['no_rm'];
            }
            ?>
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php
        }
        ?>
        <div class="row">
            <label for="inputNamapasien" class="form-label fw-bold">
                Nama Pasien
            </label>
            <div>
                <input type="text" class="form-control" name="nama_pasien" id="inputNamapasien"
                    placeholder="Nama Pasien" value="<?php echo $nama_pasien ?>">
            </div>
        </div>
        <div class="row mt-1">
            <label for="inputAlamat" class="form-label fw-bold">
                Alamat
            </label>
            <div>
                <input type="text" class="form-control" name="alamat" id="inputAlamat" placeholder="Alamat Lengkap"
                    value="<?php echo $alamat ?>">
            </div>
        </div>
        <div class="row mt-1">
            <label for="inputNoktp" class="form-label fw-bold">
                No. KTP
            </label>
            <div>
                <input type="text" class="form-control" name="no_ktp" id="inputNoktp" placeholder="No. KTP"
                    value="<?php echo $no_ktp ?>">
            </div>
        </div>
        <div class="row mt-1">
            <label for="inputNohp" class="form-label fw-bold">
                No. HP
            </label>
            <div>
                <input type="text" class="form-control" name="no_hp" id="inputNohp" placeholder="No. HP"
                    value="<?php echo $no_hp ?>">
            </div>
        </div>
        <div class="row mt-3">
            <div class=col>
                <button type="submit" class="btn btn-primary rounded-pill px-3 mt-auto" name="simpan">Simpan</button>
            </div>
        </div>
    </form>
    <br>
    <br>
    <!-- Table-->
    <table class="table table-hover">
        <!--thead atau baris judul-->
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Alamat</th>
                <th scope="col">No. KTP</th>
                <th scope="col">No. HP</th>
                <th scope="col">No. RM</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <!--tbody berisi isi tabel sesuai dengan judul atau head-->
        <tbody>
            <!-- Kode PHP untuk menampilkan semua isi dari tabel urut-->
            <?php
            $result = mysqli_query($mysqli, "SELECT * FROM pasien");
            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <th scope="row">
                        <?php echo $no++ ?>
                    </th>
                    <td>
                        <?php echo $data['nama_pasien'] ?>
                    </td>
                    <td>
                        <?php echo $data['alamat'] ?>
                    </td>
                    <td>
                        <?php echo $data['no_ktp'] ?>
                    </td>
                    <td>
                        <?php echo $data['no_hp'] ?>
                    </td>
                    <td>
                        <?php echo $data['no_rm'] ?>
                    </td>
                    <td>
                        <a class="btn btn-success rounded-pill px-3"
                            href="index.php?page=pasien&id=<?php echo $data['id'] ?>">Ubah</a>
                        <a class="btn btn-danger rounded-pill px-3"
                            href="index.php?page=pasien&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <button class="btn btn-dark" onclick="window.location.href='index.php'">Kembali</button>
    <script>
        function showNotification() {
            alert('Data berhasil disimpan!'); 
        }
    </script>
</body>