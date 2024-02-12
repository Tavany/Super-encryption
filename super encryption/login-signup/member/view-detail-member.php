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

        table {
            width: 700px;
            margin: auto;
            text-align: center;
        }
    </style>
</head>

<body style="background-color: #F6FFFF;">
    <?php
    include("../navbar.php");
    ?>
    <div style="border: 3px solid black; padding: 10px; width: 800px; margin: 150px auto; text-align: center; background-color:white;">
        <?php
        include "../database.php";
        include "../rot13_decrypt.php";
        include "../AES256.php";

        if (isset($_GET['id_member'])) {
            $id_member = $_GET['id_member'];

            // Query the database to get the member details
            $query = "SELECT * FROM anggota WHERE id_member = :id_member";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_member', $id_member);
            $stmt->execute();

            // Check if the query executed successfully and if there are results
            if ($stmt) {
                $hasil = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($hasil) {
                    // Decrypt and display member details
                    $decryptedUsername = rot13_decrypt(openssl_decrypt($hasil['username'], $encryptionMethod, $encryptionKey, 0, $iv));
                    $decryptedNama = rot13_decrypt(openssl_decrypt($hasil['nama'], $encryptionMethod, $encryptionKey, 0, $iv));
                    $decryptedNik = rot13_decrypt(openssl_decrypt($hasil['nik'], $encryptionMethod, $encryptionKey, 0, $iv));
                    $decryptedTgl_lahir = rot13_decrypt(openssl_decrypt($hasil['tgl_lahir'], $encryptionMethod, $encryptionKey, 0, $iv));
                    $decryptedPekerjaan = rot13_decrypt(openssl_decrypt($hasil['pekerjaan'], $encryptionMethod, $encryptionKey, 0, $iv));
                    $decryptedAlamat = rot13_decrypt(openssl_decrypt($hasil['alamat'], $encryptionMethod, $encryptionKey, 0, $iv));
                    $decryptedNoHp = rot13_decrypt(openssl_decrypt($hasil['no_hp'], $encryptionMethod, $encryptionKey, 0, $iv));
        ?>
                    <h2 style="color: #042331; text-align: center;">Detail Member <?= $decryptedUsername ?></h2>

                    <?php // Get the photo from the database
                    // Get the photo from the database
                    if (!empty($hasil['foto'])) {
                        $encryptedFoto = rot13_decrypt(openssl_decrypt($hasil['foto'], $encryptionMethod, $encryptionKey, 0, $iv));
                        $decryptedFoto = base64_decode($encryptedFoto);

                        if ($decryptedFoto === false) {
                            $error = openssl_error_string();
                            echo "Error decrypting the photo: $error";
                        } else {
                            $imageInfo = getimagesizefromstring($decryptedFoto);

                            if ($imageInfo !== false) {
                                $mimeType = $imageInfo['mime'];
                                echo '<img src="data:' . $mimeType . ';base64,' . base64_encode($decryptedFoto) . '" width="200" height="200" />';
                            } else {
                                echo 'Tidak dapat menampilkan foto. Format file mungkin tidak didukung.';
                            }
                        }
                    } else {
                        echo "Error: Encrypted data is empty.";
                    }

                    ?>

                    <table>
                        <tr height="46" style="text-align: left;">
                            <td width="25%">Nama</td>
                            <td><?= $decryptedNama ?></td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>NIK</td>
                            <td><?= $decryptedNik ?></td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>Jenis Kelamin</td>
                            <td><?= $hasil['jenis_kelamin'] ?></td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>Tanggal Lahir</td>
                            <td><?= $decryptedTgl_lahir ?></td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>Pekerjaan</td>
                            <td><?= $decryptedPekerjaan ?></td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>Alamat</td>
                            <td><?= $decryptedAlamat ?></td>
                        </tr>
                        <tr height="46" style="text-align: left;">
                            <td>No HP</td>
                            <td><?= $decryptedNoHp ?></td>
                        </tr>
                        <tr height="175" style="text-align: left;">
                            <td>KTP</td>
                            <td>
                                <?php
                                // Get the KTP from the database
                                if (!empty($hasil['ktp'])) {
                                    $encryptedKTP = rot13_decrypt(openssl_decrypt($hasil['ktp'], $encryptionMethod, $encryptionKey, 0, $iv));
                                    $decryptedKTP = base64_decode($encryptedKTP);

                                    if ($decryptedKTP === false) {
                                        $error = openssl_error_string();
                                        echo "Error decrypting the KTP: $error";
                                    } else {
                                        $imageInfoKTP = getimagesizefromstring($decryptedKTP);

                                        if ($imageInfoKTP !== false) {
                                            $mimeTypeKTP = $imageInfoKTP['mime'];
                                            echo '<img src="data:' . $mimeTypeKTP . ';base64,' . base64_encode($decryptedKTP) . '" width="350" height="175" />';
                                        } else {
                                            echo 'Tidak dapat menampilkan KTP. Format file mungkin tidak didukung.';
                                        }
                                    }
                                } else {
                                    echo "Error: Encrypted KTP data is empty.";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr height="300" style="text-align: left;">
                            <td>Kartu Keluarga</td>
                            <td>
                                <?php
                                // Get the KTP from the database
                                if (!empty($hasil['kk'])) {
                                    $encryptedKK = rot13_decrypt(openssl_decrypt($hasil['kk'], $encryptionMethod, $encryptionKey, 0, $iv));
                                    $decryptedKK = base64_decode($encryptedKK);

                                    if ($decryptedKK === false) {
                                        $error = openssl_error_string();
                                        echo "Error decrypting the KTP: $error";
                                    } else {
                                        $imageInfoKK = getimagesizefromstring($decryptedKK);

                                        if ($imageInfoKK !== false) {
                                            $mimeTypeKK = $imageInfoKK['mime'];
                                            echo '<img src="data:' . $mimeTypeKK . ';base64,' . base64_encode($decryptedKK) . '" width="500" height="250" />';
                                        } else {
                                            echo 'Tidak dapat menampilkan Kartu Keluarga. Format file mungkin tidak didukung.';
                                        }
                                    }
                                } else {
                                    echo "Error: Encrypted KTP data is empty.";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
        <?php
                } else {
                    echo "Error: No data found for the provided username.";
                }
            } else {
                echo "Error: Query execution failed.";
            }
        } else {
            echo "Error: No ID selected.";
        }
        ?>
        <br>
        <input type="button" class="btn btn-secondary" value="Kembali" onclick="location.href='../form-view-member.php'" title="Kembali"> <br /><br />
    </div>
</body>

</html>