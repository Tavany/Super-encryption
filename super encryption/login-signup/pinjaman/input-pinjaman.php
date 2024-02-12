<?php
include "../database.php";
include "../AES256.php";
include "../rot13_encrypt.php";

$id_pinjam = $_POST['id_member'];
$username = $_POST['username'];
$nama = $_POST['nama'];
$tgl_transaksi = $_POST['tgl_transaksi'] . "-" . $_POST['bln_transaksi'] . "-" . $_POST['thn_transaksi'];
$jml_transaksi = $_POST['jml_transaksi'];

$encryptedUsername = openssl_encrypt(rot13_encrypt($username), $encryptionMethod, $encryptionKey, 0, $iv);
$encryptedNama = openssl_encrypt(rot13_encrypt($nama), $encryptionMethod, $encryptionKey, 0, $iv);
$encryptedNama = openssl_encrypt(rot13_encrypt($nama), $encryptionMethod, $encryptionKey, 0, $iv);

if (empty($jml_transaksi)) {
    ?>
    <script language="JavaScript">
        alert('Masukkan jumlah pinjaman!');
        document.location = '../list-pinjaman.php';
    </script>
    <?php
} else {
    try {
        $conn->beginTransaction();

        // Insert pinjaman data into the database using the provided username
        $input = "INSERT INTO pinjaman (id_pinjam, username, nama, tgl_transaksi, jml_transaksi) VALUES (:id_pinjam, :username, :nama, :tgl_transaksi, :jml_transaksi)";
        $stmtInput = $conn->prepare($input);
        $stmtInput->bindParam(':id_pinjam', $id_pinjam);
        $stmtInput->bindParam(':username', $encryptedUsername);
        $stmtInput->bindParam(':nama', $encryptedNama);
        $stmtInput->bindParam(':tgl_transaksi', $tgl_transaksi);
        $stmtInput->bindParam(':jml_transaksi', $jml_transaksi);
        $stmtInput->execute();

        // Update pinjaman in the anggota table
        $update = "UPDATE anggota SET pinjaman = pinjaman + :jml_transaksi WHERE username = :encryptedUsername";
        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bindParam(':jml_transaksi', $jml_transaksi);
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
