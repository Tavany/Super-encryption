<?php

include '../database.php';
include '../rot13_encrypt.php';
include '../AES256.php';

if ($_POST['update'] == "Update") {
    // Proses data yang diterima dari formulir
    // Lakukan validasi dan sanitasi data yang diterima

    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $pekerjaan = $_POST['pekerjaan'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_member = $_POST['id_member'];

    $encryptedNama = openssl_encrypt(rot13_encrypt($nama), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedNik = openssl_encrypt(rot13_encrypt($nik), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedTgl_lahir = openssl_encrypt(rot13_encrypt($tgl_lahir), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedPekerjaan = openssl_encrypt(rot13_encrypt($pekerjaan), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedAlamat = openssl_encrypt(rot13_encrypt($alamat), $encryptionMethod, $encryptionKey, 0, $iv);
    $encryptedNoHp = openssl_encrypt(rot13_encrypt($no_hp), $encryptionMethod, $encryptionKey, 0, $iv);

    $Update = "UPDATE anggota SET nama = :nama, nik = :nik, tgl_lahir = :tgl_lahir, pekerjaan = :pekerjaan, alamat = :alamat, no_hp = :no_hp WHERE id_member = :id_member";
    $stmt = $conn->prepare($Update);
    $stmt->bindParam(':nama', $encryptedNama);
    $stmt->bindParam(':nik', $encryptedNik);
    $stmt->bindParam(':tgl_lahir', $encryptedTgl_lahir);
    $stmt->bindParam(':pekerjaan', $encryptedPekerjaan);
    $stmt->bindParam(':alamat', $encryptedAlamat);
    $stmt->bindParam(':no_hp', $encryptedNoHp);
    $stmt->bindParam(':id_member', $id_member);

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
        

        $conn = null; // Menutup koneksi PDO
    }

?>
