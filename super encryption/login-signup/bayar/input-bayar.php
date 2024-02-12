<?php
include "../database.php";
include "../AES256.php";
include "../rot13_encrypt.php";

$id_bayar = $_POST['id_member'];
$username = $_POST['username'];
$nama = $_POST['nama'];
$tgl_bayar = $_POST['tgl_bayar'] . "-" . $_POST['bln_bayar'] . "-" . $_POST['thn_bayar'];
$jml_bayar = $_POST['jml_bayar'];

$encryptedUsername = openssl_encrypt(rot13_encrypt($username), $encryptionMethod, $encryptionKey, 0, $iv);
$encryptedNama = openssl_encrypt(rot13_encrypt($nama), $encryptionMethod, $encryptionKey, 0, $iv);

if (empty($jml_bayar)) {
    ?>
    <script language="JavaScript">
        alert('Masukkan jumlah pembayaran!');
        document.location = '../list-pinjaman.php';
    </script>
    <?php
} else {
    try {
        $conn->beginTransaction();

        // Insert pinjaman data into the database using the provided username
        $input = "INSERT INTO bayar (id_bayar, username, nama, tgl_bayar, jml_bayar) VALUES (:id_bayar, :username, :nama, :tgl_bayar, :jml_bayar)";
        $stmtInput = $conn->prepare($input);
        $stmtInput->bindParam(':id_bayar', $id_bayar);
        $stmtInput->bindParam(':username', $encryptedUsername);
        $stmtInput->bindParam(':nama', $encryptedNama);
        $stmtInput->bindParam(':tgl_bayar', $tgl_bayar);
        $stmtInput->bindParam(':jml_bayar', $jml_bayar);
        $stmtInput->execute();

        // Update pinjaman in the anggota table
        $update = "UPDATE anggota SET pinjaman = pinjaman - :jml_bayar WHERE username = :encryptedUsername";
        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bindParam(':jml_bayar', $jml_bayar);
        $stmtUpdate->bindParam(':encryptedUsername', $encryptedUsername);
        $stmtUpdate->execute();

        $conn->commit();

        ?>
        <script language="JavaScript">
            alert('Data pinjaman berhasil diinput!');
            document.location = '../list-pinjaman.php';
        </script>
        <?php
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>
