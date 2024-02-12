<?php
include "../database.php";
include "../AES256.php";
include "../rot13_encrypt.php";

$id_tabungan = $_POST['id_member'];
$username = $_POST['username'];
$nama = $_POST['nama'];
$tgl_tabungan = $_POST['tgl_tabungan'] . "-" . $_POST['bln_tabungan'] . "-" . $_POST['thn_tabungan'];
$jenis_tabungan = $_POST['jenis_tabungan'];
$jml_tabungan = $_POST['jml_tabungan'];

$encryptedUsername = openssl_encrypt(rot13_encrypt($username), $encryptionMethod, $encryptionKey, 0, $iv);
$encryptedNama = openssl_encrypt(rot13_encrypt($nama), $encryptionMethod, $encryptionKey, 0, $iv);

if (empty($jml_tabungan)) {
    ?>
    <script language="JavaScript">
        alert('Masukkan jumlah tabungan!');
        document.location = '../list-tabungan.php';
    </script>
    <?php
} else {
    try {
        $conn->beginTransaction();

        // Insert tabungan data into the database
        $input = "INSERT INTO tabungan (id_tabungan, username, nama, tgl_tabungan, jenis_tabungan, jml_tabungan) VALUES (:id_tabungan, :username, :nama, :tgl_tabungan, :jenis_tabungan, :jml_tabungan)";
        $stmtInput = $conn->prepare($input);
        $stmtInput->bindParam(':id_tabungan', $id_tabungan);
        $stmtInput->bindParam(':username', $encryptedUsername);
        $stmtInput->bindParam(':nama', $encryptedNama);
        $stmtInput->bindParam(':tgl_tabungan', $tgl_tabungan);
        $stmtInput->bindParam(':jenis_tabungan', $jenis_tabungan);
        $stmtInput->bindParam(':jml_tabungan', $jml_tabungan);
        $stmtInput->execute();

        // Update tabungan in the anggota table
        if ($jenis_tabungan == 'pokok') {
            $update = "UPDATE anggota SET tabungan_pokok = tabungan_pokok + :jml_tabungan WHERE username = :encryptedUsername";
        } elseif ($jenis_tabungan == 'wajib') {
            $update = "UPDATE anggota SET tabungan_wajib = tabungan_wajib + :jml_tabungan WHERE username = :encryptedUsername";
        }

        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bindParam(':jml_tabungan', $jml_tabungan);
        $stmtUpdate->bindParam(':encryptedUsername', $encryptedUsername);
        $stmtUpdate->execute();

        $conn->commit();

        ?>
        <script language="JavaScript">
            alert('Data tabungan berhasil diinput!');
            document.location = '../list-tabungan.php';
        </script>
        <?php
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>
