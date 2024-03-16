<?php

use App\Helpers\Email;

if ( ! function_exists('validate_email') ) {
    function validate_email($email)
    {
        return Email::validate($email);
    }
}