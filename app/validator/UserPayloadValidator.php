<?php
class UserPayloadValidator
{
    public static function isPayloadEmpty($fields)
    {
        foreach ($fields as $field) {
            if (empty($field)) {
                return true;
            }
        }
        return false;
    }
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function passwordsMatch($password, $passwordConfirm)
    {
        return $password === $passwordConfirm;
    }
}