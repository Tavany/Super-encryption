<?php
session_start();

// Check if the 'fname' key exists in the $_SESSION array
$fname = isset($_SESSION['fname']) ? htmlspecialchars($_SESSION['fname']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" charset="UTF-8"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            font-size: 16;
        }
    </style>
</head>

<body style="background-color: #F6FFFF;">
    <?php
    session_start();

    include '../navbar.php';
    include '../rot13_decrypt.php';
    include '../database.php';
    include '../AES256.php';

    if (isset($_GET['id_member'])) {
        // Parameter username yang akan diedit

        // Mengambil data anggota dari databas

        $id_member = $_GET['id_member'];

        $Cari = "SELECT * FROM anggota WHERE id_member = :id_member";
        $stmt = $conn->prepare($Cari);
        $stmt->bindParam(':id_member', $id_member);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // Data tidak ditemukan, beri pesan kesalahan atau redirect ke halaman lain
            echo "Data anggota tidak ditemukan";
        } else {
            // Decrypt data yang ditemukan
            $decryptedNama = rot13_decrypt(openssl_decrypt($result['nama'], $encryptionMethod, $encryptionKey, 0, $iv));
            $decryptedNik = rot13_decrypt(openssl_decrypt($result['nik'], $encryptionMethod, $encryptionKey, 0, $iv));
            $decryptedTgl_lahir = rot13_decrypt(openssl_decrypt($result['tgl_lahir'], $encryptionMethod, $encryptionKey, 0, $iv));
            $decryptedPekerjaan = rot13_decrypt(openssl_decrypt($result['pekerjaan'], $encryptionMethod, $encryptionKey, 0, $iv));
            $decryptedAlamat = rot13_decrypt(openssl_decrypt($result['alamat'], $encryptionMethod, $encryptionKey, 0, $iv));
            $decryptedNoHp = rot13_decrypt(openssl_decrypt($result['no_hp'], $encryptionMethod, $encryptionKey, 0, $iv));
        }
    } else {
        // Jika parameter username tidak diberikan, beri pesan kesalahan atau redirect ke halaman lain
        echo "Parameter username tidak ditemukan";
    }
    ?>

    <div style="border: 3px solid black; padding: 10px; width: 700px; margin: 150px auto; text-align: center; background-color: white;">
        <form action="input-edit-member.php" method="POST" name="form-edit-member">
            <input type="hidden" name="id_member" value="<?= $id_member ?>">
            <table style=" width: 500; border: 0; align-items : center;" cellpadding="0" cellspacing="0">
                <tr height="46">
                    <td colspan="3">
                        <h2 style="color: #042331; text-align: center ;">Form Edit Member</h2>
                    </td>
                </tr>

                <tr>
                    <td width="10%">&nbsp;</td>
                    <td width="25%" style="text-align: left;">
                    <button type="button" class="btn btn-outline-secondary" onclick="location.href='../form-view-member.php'" title="Cancel">Cancel</button>
                </tr>

                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Nama</td>
                    <td><input type="text" name="nama" size="50" value="<?= $decryptedNama ?>" /></td>
                    <td width="10%">&nbsp;</td>
                </tr>

                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>NIK</td>
                    <td><input type="text" name="nik" size="50" value="<?= $decryptedNik ?>" /></td>
                    <td>&nbsp;</td>
                </tr>

                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Tanggal Lahir</td>
                    <td>
                        <select name="tgl_lahir">
                            <?php
                            for ($i = 1; $i <= 31; $i++) {
                                $tg = ($i < 10) ? "0$i" : $i;
                                echo "<option value='$tg'>$tg</option>";
                            }
                            ?>
                        </select>
                        <select name="bln_lahir">
                            <?php
                            for ($bln = 1; $bln <= 12; $bln++) {
                                $nama_bln = array(1 => "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des");
                                echo "<option value='$bln'>$nama_bln[$bln]</option>";
                            }
                            ?>
                        </select>
                        <select name="thn_lahir">
                            <?php
                            for ($i = 2015; $i <= 2050; $i++) {  // Fix the typo here
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>

                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Pekerjaan</td>
                    <td><input type="text" name="pekerjaan" size="50" value="<?= $decryptedPekerjaan ?>" /></td>
                    <td>&nbsp;</td>
                </tr>

                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Alamat</td>
                    <td><input type="text" name="alamat" size="50" value="<?= $decryptedAlamat ?>" /></td>
                    <td>&nbsp;</td>
                </tr>

                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>No HP</td>
                    <td><input type="text" name="no_hp" size="50" value="<?= $decryptedNoHp ?>" /></td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <input type="submit" class="btn btn-primary" name="update" value="Update"><br /><br />

        </form>
    </div>

</body>

</html>