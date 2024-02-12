<?php
include "../database.php";
include "../AES256.php";
include "../rot13_encrypt.php";

$id_ambil = $_POST['id_member'];
$username = $_POST['username'];
$nama = $_POST['nama'];
$tgl_ambil = $_POST['tgl_ambil'] . "-" . $_POST['bln_ambil'] . "-" . $_POST['thn_ambil'];
$jenis_tabungan = $_POST['jenis_tabungan'];
$jml_ambil = $_POST['jml_ambil'];

$encryptedUsername = openssl_encrypt(rot13_encrypt($username), $encryptionMethod, $encryptionKey, 0, $iv);
$encryptedNama = openssl_encrypt(rot13_encrypt($nama), $encryptionMethod, $encryptionKey, 0, $iv);

if (empty($jml_ambil)) {
    ?>
    <script language="JavaScript">
        alert('Masukkan jumlah pengambilan!');
        document.location = '../list-tabungan.php';
    </script>
    <?php
} else {
    try {
        $conn->beginTransaction();

        // Insert ambil_tabungan data into the database
        $input = "INSERT INTO ambil_tabungan (id_ambil, username, nama, tgl_ambil, jenis_tabungan, jml_ambil) VALUES (:id_ambil, :username, :nama, :tgl_ambil, :jenis_tabungan, :jml_ambil)";
        $stmtInput = $conn->prepare($input);
        $stmtInput->bindParam(':id_ambil', $id_ambil);
        $stmtInput->bindParam(':username', $encryptedUsername);
        $stmtInput->bindParam(':nama', $encryptedNama);
        $stmtInput->bindParam(':tgl_ambil', $tgl_ambil);
        $stmtInput->bindParam(':jenis_tabungan', $jenis_tabungan);
        $stmtInput->bindParam(':jml_ambil', $jml_ambil);
        $stmtInput->execute();

        if ($jenis_tabungan == 'pokok') {
            $update = "UPDATE anggota SET tabungan_pokok = tabungan_pokok - :jml_ambil WHERE username = :encryptedUsername";
        } elseif ($jenis_tabungan == 'wajib') {
            $update = "UPDATE anggota SET tabungan_wajib = tabungan_wajib - :jml_ambil WHERE username = :encryptedUsername";
        }

        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bindParam(':jml_ambil', $jml_ambil);
        $stmtUpdate->bindParam(':encryptedUsername', $encryptedUsername);
        $stmtUpdate->execute();

        $conn->commit();

        ?>
        <script language="JavaScript">
            alert('Data ambil tabungan berhasil diinput!');
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
