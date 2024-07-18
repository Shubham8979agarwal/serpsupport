<?php

// app/Helpers/helpers.php

use Illuminate\Support\Facades\Crypt;

if (!function_exists('decryptData')) {
    function decryptData($value)
    {
        try {
            return Crypt::decrypt($value);
        } catch (Exception $e) {
            return 'Error decrypting data';
        }
    }
}
