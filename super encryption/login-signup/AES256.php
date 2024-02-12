<?php

$encryptionKey = //ur key;
$encryptionMethod = "AES-256-CBC";
$iv = str_repeat("0", openssl_cipher_iv_length($encryptionMethod));

?>
