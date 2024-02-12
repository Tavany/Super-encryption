<?php
function rot13_encrypt($string)
{
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = $string[$i];
        if (ctype_alpha($char)) {
            if (ctype_upper($char)) {
                $result .= chr((ord($char) - ord('A') + 13) % 26 + ord('A'));
            } else {
                $result .= chr((ord($char) - ord('a') + 13) % 26 + ord('a'));
            }
        } elseif (ctype_digit($char)) {
            // Enkripsi karakter angka dengan ROT5
            $result .= chr(((ord($char) - ord('0') + 5) % 10) + ord('0'));
        } else {
            $result .= $char;
        }
    }

    return $result;
}
?>