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
    <style>
        body {
            font-family: sans-serif;
            font-size: 16;
        }
    </style>
</head>

<body style="background-color: #F6FFFF;">
    <?php
    include("navbar.php");
    ?>
    <div style="border: 3px solid black; padding: 10px; width: 700px; margin: 150px auto; text-align: center; background-color: white;">
        <form action="member/input-member.php" method="post" name="form-input-member" enctype="multipart/form-data">
            <h2 style="color: #042331; text-align: center ;">Form Input Member</h2>
            <table style=" width: 500; border: 0; align-items : center;" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="10%">&nbsp;</td>
                    <td width="25%" style="text-align: left;">
                        <button type="button" class="btn btn-outline-secondary" onclick="location.href='form-view-member.php'" title="Cancel">Cancel</button>
                        <br /><br />
                    </td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Username</td>
                    <td><input type="text" name="username" size="50" /></td>
                    <td width="10%">&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Nama</td>
                    <td><input type="text" name="nama" size="50" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>NIK</td>
                    <td><input type="text" name="nik" size="50" /></td>
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
                        </select> -
                        <select name="bln_lahir">
                            <?php
                            for ($bln = 1; $bln <= 12; $bln++) {
                                $nama_bln = array(1 => "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des");
                                echo "<option value='$bln'>$nama_bln[$bln]</option>";
                            }
                            ?>
                        </select> -
                        <select name="thn_lahir">
                            <?php
                            for ($i = 1945; $i <= 2020; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Jenis Kelamin</td>
                    <td><input type="radio" name="jenis_kelamin" value="L" checked>Laki-laki
                        <input type="radio" name="jenis_kelamin" value="P">Perempuan
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Pekerjaan</td>
                    <td><input type="text" name="pekerjaan" size="50" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Alamat</td>
                    <td><textarea name="alamat" rows="2" cols="53"></textarea></td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Nomor HP</td>
                    <td><input type="text" name="no_hp" size="50" maxlength="12" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Foto</td>
                    <td><input type="file" name="foto" id="foto" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>KTP</td>
                    <td><input type="file" name="ktp" id="ktp" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr height="46" style="text-align: left;">
                    <td>&nbsp;</td>
                    <td>Kartu Keluarga</td>
                    <td><input type="file" name="kk" id="kk" /></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <input type="submit" class="btn btn-primary" name="submit" value="Input"><br /><br />
        </form>
    </div>

</body>

</html>