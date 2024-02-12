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
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" charset="UTF-8"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body style="background-color: #F6FFFF;">
    <?php
    include("navbar.php");
    ?>
    <div style="border: 0; padding: 10px; width: 924px; height: auto; margin: 100px auto; text-align: center;">
        <br />
        <h2 style="color: #042331;"><b>List Tabungan </b></h2>
        <table width="924" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr  style="background-color: #042331; color: white;">
                <th width="5%">No</th>&nbsp;
                <th width="15%" height="42">Username</th>&nbsp;
                <th width="20%">Nama</th>&nbsp;
                <th width="15%">Tabungan Pokok</th>&nbsp;
                <th width="15%">Tabungan Wajib</th>&nbsp;
                <th width="20%">Action</th>&nbsp;
            </tr>
            <?php
            include 'database.php';
            include 'rot13_decrypt.php';
            include 'AES256.php';

            function formatRupiah($amount)
            {
                if (!empty($amount)) {
                    $rupiah = "Rp " . number_format($amount, 0, ',', '.');
                    return $rupiah;
                } else {
                    return "Rp 0";
                }
            }

            $Cari = "SELECT * FROM anggota";
            $Tampil = $conn->query($Cari);
            $nomer = 0;
            while ($hasil = $Tampil->fetch(PDO::FETCH_ASSOC)) {
                $id_member = stripslashes($hasil["id_member"]);
                $username = stripslashes($hasil["username"]);
                $nama = stripslashes($hasil["nama"]);
                $tabungan_pokok = stripslashes($hasil["tabungan_pokok"]);
                $tabungan_wajib = stripslashes($hasil["tabungan_wajib"]);

                $decryptedUsername = rot13_decrypt(openssl_decrypt($username, $encryptionMethod, $encryptionKey, 0, $iv));
                $decryptedNama = rot13_decrypt(openssl_decrypt($nama, $encryptionMethod, $encryptionKey, 0, $iv));

                $nomer++;
            ?>
                <tr align="center" bgcolor="#DFE6EF">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr align="center" bgcolor="white">
                    <td height="32"><?= $nomer ?><div align="center"></div>
                    </td>
                    <td><?= $decryptedUsername ?><div align="center"></div>
                    </td>
                    <td><?= $decryptedNama ?><div align="center"></div>
                    </td>
                    <td><?= formatRupiah($tabungan_pokok) ?><div align="center"></div>
                    </td>
                    </td>
                    <td><?= formatRupiah($tabungan_wajib) ?><div align="center"></div>
                    </td>
                    <td style="background-color: #EEF2F7" ;>
                        <div align="center">
                            <a href="tabungan/lihat-tabungan-member.php?id=<?= $id_member ?>">Lihat</a> |
                            <a href="tabungan/form-input-tabungan.php?id_member=<?= $id_member ?>">Tabung</a> |
                            <a href="ambil_tabungan/form-input-ambil_tabungan.php?id_member=<?= $id_member ?>">Ambil</a>
                        </div>
                    </td>
                </tr>
            <?php
            }
            $conn = null;
            ?>
            <tr align="center" bgcolor="#DFE6EF">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</body>

</html>