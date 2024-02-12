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
    include("../navbar.php");
    ?>
    <div style="border: 3px solid black; padding: 10px; width: 700px; margin: 150px auto; text-align: center; background-color: white;">
        <?php
        include "../database.php";
        include "../rot13_decrypt.php";
        include "../AES256.php";

        if (isset($_GET['id_member'])) {
            $id_member = $_GET['id_member'];
            $query = "SELECT * FROM anggota WHERE id_member = :id_member";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_member', $id_member);
            $stmt->execute();

            while ($hasil = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $decryptedUsername = rot13_decrypt(openssl_decrypt($hasil['username'], $encryptionMethod, $encryptionKey, 0, $iv));
                $decryptedNama = rot13_decrypt(openssl_decrypt($hasil['nama'], $encryptionMethod, $encryptionKey, 0, $iv));

        ?>
                <form action="input-pinjaman.php" method="post" name="form-input-pinjaman">
                    <!-- Add this line inside your form -->
                    <input type="hidden" name="id_member" value="<?= $id_member ?>">
                    <h2 style="color: #042331; text-align: center ;">Form Input Pinjaman</h2>
                    <table style=" width: 500; align-items : center;" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="10%">&nbsp;</td>
                            <td width="25%" style="text-align: left;">
                                <button type="button" class="btn btn-outline-secondary" onclick="location.href='../list-pinjaman.php'" title="Cancel">Cancel</button><br />
                            </td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>&nbsp;</td>
                            <td width="40%">Username</td>
                            <td>
                                <input name="username" type="text" size="25" value="<?= $decryptedUsername ?>" readonly />
                                <input name="username" type="hidden" size="25" value="<?= $decryptedUsername ?>" />
                            </td>
                            <td width="10%">&nbsp;</td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>&nbsp;</td>
                            <td>Nama</td>
                            <td>
                                <input name="nama" type="text" size="25" value="<?= $decryptedNama ?>" readonly />
                                <input name="nama" type="hidden" size="25" value="<?= $decryptedNama ?>" />
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>&nbsp;</td>
                            <td>Tanggal Transaksi</td>
                            <td>
                                <select name="tgl_transaksi">
                                    <?php
                                    for ($i = 1; $i <= 31; $i++) {
                                        $tg = ($i < 10) ? "0$i" : $i;
                                        echo "<option value='$tg'>$tg</option>";
                                    }
                                    ?>
                                </select>
                                <select name="bln_transaksi">
                                    <?php
                                    for ($bln = 1; $bln <= 12; $bln++) {
                                        $nama_bln = array(1 => "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des");
                                        echo "<option value='$bln'>$nama_bln[$bln]</option>";
                                    }
                                    ?>
                                </select>
                                <select name="thn_transaksi">
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
                            <td>Jumlah Pinjaman</td>
                            <td><span><Input type="text" id="jml_transaksi_idr" name="jml_transaksi_idr" size="25"></Input></span></td>
                            <td><input type="hidden" name="jml_transaksi" id="jml_transaksi"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <input type="submit" class="btn btn-primary" name="Submit" value="Pinjam">&nbsp;&nbsp;&nbsp;
                    <input type="reset" class="btn btn-secondary" name="reset" value="Reset"> <br /><br />
                </form>
        <?php
            }
        } else {
            die("Error! Tidak ada username yang dipilih. Silahkan cek kembali");
        }
        ?>
    </div>

    <script type="text/javascript">
        var jml_transaksi_idr = document.getElementById('jml_transaksi_idr');
        var jml_transaksi = document.getElementById('jml_transaksi'); // Add this line

        jml_transaksi_idr.addEventListener('keyup', function(e) {
            var numericValue = this.value.replace(/[^\d]/g, ''); // Remove non-numeric characters
            jml_transaksi_idr.value = formatRupiah(numericValue, 'Rp. ');
            jml_transaksi.value = numericValue; // Set the raw numeric value
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                jml_transaksi_idr = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                jml_transaksi_idr += separator + ribuan.join('.');
            }

            jml_transaksi_idr = split[1] != undefined ? jml_transaksi_idr + ',' + split[1] : jml_transaksi_idr;
            return prefix == undefined ? jml_transaksi_idr : (jml_transaksi_idr ? 'Rp. ' + jml_transaksi_idr : '');
        }
    </script>

</body>

</html>