<?php
session_start();
include '../database.php';

    if (isset($_GET['id_member'])) {

        $id_member = $_GET['id_member'];

        // Hapus data anggota berdasarkan username
        $Hapus = "DELETE FROM anggota WHERE id_member = :id_member";
        $stmt = $conn->prepare($Hapus);
        $stmt->bindParam(':id_member', $id_member);

        if ($stmt->execute()) {
            // Data berhasil dihapus, alihkan kembali ke halaman tampil semua anggota
            header("Location: ../form-view-member.php");
            exit;
        } else {
            // Kesalahan saat menghapus data
            echo "Terjadi kesalahan saat menghapus data.";
        }
    } else {
        // Jika parameter username tidak diberikan, beri pesan kesalahan atau redirect ke halaman lain
        echo "Parameter username tidak ditemukan.";
    }
?>
