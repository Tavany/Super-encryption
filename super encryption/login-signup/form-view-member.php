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
    <div style="border: 0; padding: 10px; width: 924px; height: auto; margin: 100px auto; text-align: center;"><br />
        <h2 style="color: #042331;"><b>List Anggota</b></h2>
        <table table width="924" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr height="32" style="background-color: #042331; color: white;">
                <th style="width: 5%;">No</th>&nbsp;
                <th style="width: 15%;">Username</th>&nbsp;
                <th style="width: 28%;">Nama</th>&nbsp;
                <th style="width: 15%;">NIK</th>&nbsp;
                <th style="width: 20%;">No HP</th>&nbsp;
                <th style="width: 17%;">Action</th>&nbsp;
            </tr>
            <?php
            include 'database.php';
            include 'rot13_decrypt.php';
            include 'AES256.php';

            $Cari = "SELECT * FROM anggota";
            $Tampil = $conn->query($Cari);
            $nomer = 0;

            while ($hasil = $Tampil->fetch(PDO::FETCH_ASSOC)) {
                $id_member = $hasil['id_member'];
                $username = $hasil['username']; // Get the encrypted data from the database
                $nama = $hasil['nama'];
                $nik = $hasil['nik'];
                $no_hp = $hasil['no_hp'];

                // Decrypt data using OpenSSL and then ROT13
                $decryptedUsername = rot13_decrypt(openssl_decrypt($username, $encryptionMethod, $encryptionKey, 0, $iv));
                $decryptedNama = rot13_decrypt(openssl_decrypt($nama, $encryptionMethod, $encryptionKey, 0, $iv));
                $decryptedNik = rot13_decrypt(openssl_decrypt($nik, $encryptionMethod, $encryptionKey, 0, $iv));
                $decryptedNoHp = rot13_decrypt(openssl_decrypt($no_hp, $encryptionMethod, $encryptionKey, 0, $iv));
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
                    <td><?= $decryptedNik ?><div align="center"></div>
                    </td>
                    <td><?= $decryptedNoHp ?><div align="center"></div>
                    </td>
                    <td style="background-color: #EEF2F7;">
                        <div align="center">
                            <a href="member/view-detail-member.php?id_member=<?= $id_member ?>">Detail</a> |
                            <a href="member/edit-member.php?id_member=<?= $id_member ?>">Edit</a> |
                            <a href="member/hapus-member.php?id_member=<?= $id_member ?>">Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php
            }
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
        <br />
        <button type="button" class="btn btn-outline-primary" onclick="window.location.href='form-input-member.php'">Tambah</button>
    </div>
</body>

</html>