<?php

$encryptionKey = "12345678901234567890123456789012";
$encryptionMethod = "AES-256-CBC";
$iv = str_repeat("0", openssl_cipher_iv_length($encryptionMethod));

?>