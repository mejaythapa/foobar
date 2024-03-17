<?php
class validationHelper
{
    // public static function validateEmail($email) {
    //     return filter_var($email, FILTER_VALIDATE_EMAIL);
    // }
    public static function validateEmail($email) {
        // Regular expression for validating an Email
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }
}
?>
