<?php 
    $enc = openssl_encrypt("pwd", "AES-256-CTR", "assignments608621861023", 0, 1234567891011121);

    echo $enc."<br>";

    echo openssl_decrypt($enc, "AES-256-CTR", "assignments608621861023", 0, 1234567891011121);

    //1234567891011121
?>