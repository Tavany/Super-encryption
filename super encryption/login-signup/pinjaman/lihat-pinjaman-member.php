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
</head>

<body style="background-color: #F6FFFF;">
    <?php
    include("../navbar.php");
    include "../database.php";
    include "../rot13_decrypt.php";
    include "../AES256.php";

    function formatRupiah($amount)
    {
        if (!empty($amount)) {
            $rupiah = "Rp " . number_format($amount, 0, ',', '.');
            return $rupiah;
        } else {
            return "Rp 0";
        }
    }

    if (isset($_GET['id'])) {
        $id_member = $_GET['id'];

        $query = "SELECT id_pinjam, username AS nama, tgl_transaksi AS tgl, jml_transaksi AS jumlah, 'pinjam' AS kategori, 'waktu' AS urutan FROM pinjaman WHERE id_pinjam = :id_pinjam";
        $stmtPinjam = $conn->prepare($query);
        $stmtPinjam->bindParam(':id_pinjam', $id_member);
        $stmtPinjam->execute();
        $resultPinjam = $stmtPinjam->fetchAll(PDO::FETCH_ASSOC);

        $queryBayar = "SELECT id_bayar, username AS nama, tgl_bayar AS tgl, jml_bayar AS jumlah, 'bayar' AS kategori, 'waktu' AS urutan FROM bayar WHERE id_bayar = :id_bayar";
        $stmtBayar = $conn->prepare($queryBayar);
        $stmtBayar->bindParam(':id_bayar', $id_member);
        $stmtBayar->execute();
        $resultBayar = $stmtBayar->fetchAll(PDO::FETCH_ASSOC);

        $allResults = array_merge($resultPinjam, $resultBayar);

        // Extract timestamps and create an array to store them
        $timestamps = array_column($allResults, 'tgl');

        // Sort $allResults based on the timestamps
        array_multisort($timestamps, SORT_ASC, $allResults);

        // Now $allResults is sorted by timestamp in descending order

        // Access the first row of the result
    $firstRow = reset($allResults);

    // Retrieve the 'username' from the first row
    $username = isset($firstRow['nama']) ? $firstRow['nama'] : 'N/A';

    // Decrypt the username
    $decryptedUsername = rot13_decrypt(openssl_decrypt($username, $encryptionMethod, $encryptionKey, 0, $iv));


    ?>


        <div style="border: 0; padding: 10px; width: 924px; height: auto; margin: 100px auto; text-align: center;">
            <br />
            <h2 style="color: #042331;"><b>List Pinjaman <?= $decryptedUsername ?></b></h2>
            <table width="924" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr height="32" style="background-color: #042331; color: white; ">
                    <th width="5%">No</th>&nbsp;
                    <th width="20%">Transaksi</th>&nbsp;
                    <th width="20%">Tanggal Transaksi</th>&nbsp;
                    <th width="20%">Jumlah Transaksi</th>&nbsp;
                </tr>

                <?php
                $nomer = 0;
                foreach ($allResults as $result) {
                    $nomer++;
                    $kategori = isset($result['id_pinjam']) ? 'pinjam' : 'bayar';
                    $tgl = $result['tgl'];
                    $jumlah = $result['jumlah'];
                ?>

                    <tr align="center" bgcolor="#DFE6EF">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr align="center" bgcolor="white">
                        <td><?= $nomer ?></td>
                        <td><?= $kategori ?></td>
                        <td><?= $tgl ?></td>
                        <td><?= formatRupiah($jumlah) ?></td>
                    </tr>
            <?php
                }
            }
            ?>
            <tr bgcolor="#DFE6EF">
                <td colspan="4">&nbsp;</td>
            </tr>
            </table>
            <br />
            <button type="button" class="btn btn-secondary" onclick="location.href='../list-pinjaman.php'" title="Cancel">Cancel</button>
            <br />
        </div>
</body>

</html>