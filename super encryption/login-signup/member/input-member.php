<?php

include '../database.php';
include '../rot13_encrypt.php';
include '../AES256.php';

if ($_POST['submit'] == "Input") {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $tgl_lahir = $_POST['tgl_lahir'] . "-" . $_POST['bln_lahir'] . "-" . $_POST['thn_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $pekerjaan = $_POST['pekerjaan'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    // Initialize $base64 to null (no photo provided by default)
    $base64 = null;

    // Check if a photo was provided and it has no errors
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['foto']['tmp_name'];
        $filecontent = file_get_contents($uploadedFile);
        $base64 = base64_encode($filecontent);
        $encryptedFoto = openssl_encrypt(rot13_encrypt($base64), $encryptionMethod, $encryptionKey, 0, $iv);
        if ($encryptedFoto === false) {
            $error = openssl_error_string();
            echo "Error encrypting the photo: $error";
        }

    }

    $ktp = null;

    // Check if a KTP file was provided and it has no errors
    if (isset($_FILES['ktp']) && $_FILES['ktp']['error'] === UPLOAD_ERR_OK) {
        $uploadedKTP = $_FILES['ktp']['tmp_name'];
        $ktpContent = file_get_contents($uploadedKTP);
        $ktp = base64_encode($ktpContent);

        $encryptedKTP = openssl_encrypt(rot13_encrypt($ktp), $encryptionMethod, $encryptionKey, 0, $iv);
        if ($encryptedKTP === false) {
            $error = openssl_error_string();
            echo "Error encrypting the KTP: $error";
        }
    }
    
    $kk = null;

    // Check if a KTP file was provided and it has no errors
    if (isset($_FILES['kk']) && $_FILES['kk']['error'] === UPLOAD_ERR_OK) {
        $uploadedKK = $_FILES['kk']['tmp_name'];
        $kkContent = file_get_contents($uploadedKK);
        $kk = base64_encode($kkContent);

        $encryptedKK = openssl_encrypt(rot13_encrypt($kk), $encryptionMethod, $encryptionKey, 0, $iv);
        if ($encryptedKK === false) {
            $error = openssl_error_string();
            echo "Error encrypting the KTP: $error";
        }
    }

    // Encrypt data using OpenSSL
    $encryptedUsername = openssl_encrypt(rot13_encrypt($username), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedNama = openssl_encrypt(rot13_encrypt($nama), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedNik = openssl_encrypt(rot13_encrypt($nik), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedTglLahir = openssl_encrypt(rot13_encrypt($tgl_lahir), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedPekerjaan = openssl_encrypt(rot13_encrypt($pekerjaan), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedAlamat = openssl_encrypt(rot13_encrypt($alamat), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedNoHp = openssl_encrypt(rot13_encrypt($no_hp), $encryptionMethod, $encryptionKey, 0, $iv);

    if (empty($username) || empty($nama) || empty($nik)) {
?>
        <script language="JavaScript">
            alert("Data Harap Dilengkapi!");
            document.location = '../form-input-member.php';
        </script>
    <?php
    } else {
        // Check if the username is already used
        $stmt = $conn->prepare("SELECT username FROM anggota WHERE username = :username");
        $stmt->bindParam(':username', $encryptedUsername);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
        ?>
            <script language="JavaScript">
                alert('Username sudah dipakai, silahkan ganti username yang lain');
                document.location = '../form-input-member.php';
            </script>
        <?php
        } else {
            // Insert member data into the database
            $stmt = $conn->prepare("INSERT INTO anggota (username, nama, nik, tgl_lahir, jenis_kelamin, pekerjaan, alamat, no_hp, foto, ktp, kk) VALUES (:username, :nama, :nik, :tgl_lahir, :jenis_kelamin, :pekerjaan, :alamat, :no_hp, :foto, :ktp, :kk)");
            $stmt->bindParam(':username', $encryptedUsername);
            $stmt->bindParam(':nama', $encryptedNama);
            $stmt->bindParam(':nik', $encryptedNik);
            $stmt->bindParam(':tgl_lahir', $encryptedTglLahir);
            $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
            $stmt->bindParam(':pekerjaan', $encryptedPekerjaan);
            $stmt->bindParam(':alamat', $encryptedAlamat);
            $stmt->bindParam(':no_hp', $encryptedNoHp);
            $stmt->bindParam(':foto', $encryptedFoto);
            $stmt->bindParam(':ktp', $encryptedKTP);
            $stmt->bindParam(':kk', $encryptedKK);

            // ...

            if ($stmt->execute()) {
            ?>
                <script language="JavaScript">
                    alert('Input Data Member Berhasil');
                    document.location = '../form-view-member.php';
                </script>
    <?php
            } else {
                echo "Input Gagal";
            }
        }

        $conn = null; // Close the PDO connection
    }
}
?>
