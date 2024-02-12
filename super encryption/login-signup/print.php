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
    <link rel="stylesheet" href="css/print-style.css"> <!-- Include the print stylesheet -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" charset="UTF-8"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 2px solid black;
        }

        th,
        td {
            padding: 10px;
        }
    </style>

</head>

<body>
    <div class="navbar">
        <?php include 'navbar.php';
        ?>
    </div>

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
    ?>
    <div style="border: 0; padding: 10px; width: 924px; height: auto; margin: 100px auto; text-align: center;">
        <br />
        <h2 style="color: #042331;"><b>List Pinjaman</b></h2>
        <table width="924" align="center" cellpadding="5" cellspacing="0">
            <tr style="background-color: #042331; color: white;">
                <th width="5%">No</th>&nbsp;
                <th width="15%" height="42">Username</th>&nbsp;
                <th width="40%">Nama</th>&nbsp;
                <th width="20%">Total Pinjaman</th>&nbsp;
            </tr>

            <?php
            $Cari_pinjam = "SELECT * FROM anggota WHERE pinjaman > 0";
            $Tampil_pinjam = $conn->query($Cari_pinjam);
            $nomer = 0;
            $totalLoan = 0;

            while ($hasil_pinjam = $Tampil_pinjam->fetch(PDO::FETCH_ASSOC)) {
                $id_member = stripslashes($hasil_pinjam["id_member"]);
                $username = stripslashes($hasil_pinjam["username"]);
                $nama = stripslashes($hasil_pinjam["nama"]);
                $pinjaman = stripslashes($hasil_pinjam["pinjaman"]);

                $decryptedUsername = rot13_decrypt(openssl_decrypt($username, $encryptionMethod, $encryptionKey, 0, $iv));
                $decryptedNama = rot13_decrypt(openssl_decrypt($nama, $encryptionMethod, $encryptionKey, 0, $iv));

                $nomer++;
                $totalLoan += $pinjaman;
            ?>
                <tr align="center">
                    <td height="32"><?= $nomer ?><div align="center"></div>
                    </td>
                    <td><?= $decryptedUsername ?><div align="center"></div>
                    </td>
                    <td><?= $decryptedNama ?><div align="center"></div>
                    </td>
                    <td><?= formatRupiah($pinjaman) ?><div align="center"></div>
                    </td>
                </tr>
                <tr align="center">
                    <td colspan="3"><b>Total</b></td>
                    <td><?= formatRupiah($totalLoan) ?></td>
                </tr>
        </table>
        <br /><br />
        <h2 style="color: #042331;"><b>List Tabungan</b></h2>
        <table width="924" solid black" align="center" cellpadding="5" cellspacing="0">
            <tr style="background-color: #042331; color: white;">
                <th width="5%">No</th>&nbsp;
                <th width="15%" height="42">Username</th>&nbsp;
                <th width="20%">Nama</th>&nbsp;
                <th width="15%">Tabungan Pokok</th>&nbsp;
                <th width="15%">Tabungan Wajib</th>&nbsp;
            </tr>
            <?php
                include 'database.php';
                $Cari_tabung = "SELECT * FROM anggota WHERE tabungan_wajib > 0";
                $Tampil_tabung = $conn->query($Cari_tabung);
                $nomer = 0;
                $totalTabunganPokok = 0;
                $totalTabunganWajib = 0;

                while ($hasil_tabung = $Tampil_tabung->fetch(PDO::FETCH_ASSOC)) {
                    $id_member = stripslashes($hasil_tabung["id_member"]);
                    $username = stripslashes($hasil_tabung["username"]);
                    $nama = stripslashes($hasil_tabung["nama"]);
                    $tabungan_pokok = stripslashes($hasil_tabung["tabungan_pokok"]);
                    $tabungan_wajib = stripslashes($hasil_tabung["tabungan_wajib"]);

                    $decryptedUsername = rot13_decrypt(openssl_decrypt($username, $encryptionMethod, $encryptionKey, 0, $iv));
                    $decryptedNama = rot13_decrypt(openssl_decrypt($nama, $encryptionMethod, $encryptionKey, 0, $iv));

                    $nomer++;
                    $totalTabunganPokok += $tabungan_pokok;
                    $totalTabunganWajib += $tabungan_wajib;
            ?>
                <tr align="center">
                    <td height="32"><?= $nomer ?><div align="center"></div>
                    </td>
                    <td><?= $decryptedUsername ?><div align="center"></div>
                    </td>
                    <td><?= $decryptedNama ?><div align="center"></div>
                    </td>
                    <td><?= formatRupiah($tabungan_pokok) ?><div align="center"></div>
                    </td>
                    <td><?= formatRupiah($tabungan_wajib) ?><div align="center"></div>
                    </td>
                </tr>
                <tr align="center">
                    <td colspan="3"><b>Total</b></td>
                    <td><?= formatRupiah($totalTabunganPokok) ?></td>
                    <td><?= formatRupiah($totalTabunganWajib) ?></td>
                </tr>
        <?php
                }
            }
            $conn = null;
        ?>
        </table>
        <br /><br />
        <button class="btn btn-outline-primary" onclick="printTables()">Print</button>
    </div>

    <script>
        function printTables() {
            // Print the content of the body, excluding navbar
            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print</title>');
            // Include the print stylesheet
            printWindow.document.write('<link rel="stylesheet" type="text/css" href="css/print-style.css">');
            printWindow.document.write('</head><body>');

            // Exclude the navbar and button from printing
            var contentToPrint = document.body.cloneNode(true);
            var navbarToExclude = contentToPrint.querySelector('.navbar');
            var buttonToExclude = contentToPrint.querySelector('button');

            if (navbarToExclude) {
                navbarToExclude.parentNode.removeChild(navbarToExclude);
            }

            if (buttonToExclude) {
                buttonToExclude.parentNode.removeChild(buttonToExclude);
            }

            printWindow.document.write(contentToPrint.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Handle the beforeprint event
            printWindow.addEventListener('beforeprint', function() {
                // Code to execute before printing
            });

            // Handle the afterprint event
            printWindow.addEventListener('afterprint', function() {
                // Code to execute after printing
                printWindow.close(); // Close the new tab after printing
            });

            printWindow.print();
        }
    </script>


</body>

</html>