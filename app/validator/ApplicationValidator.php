<?php
class ApplicationValidator
{
    public static function validatePlayer($data)
    {
        if (empty($data['debt_amount']) || !is_numeric($data['debt_amount'])) {
            return "Debt amount must be a number.";
        }
        if (empty($data['reason'])) {
            return "Reason for joining is required.";
        }
        return true;
    }
    public static function validateGuard($data)
    {
        if (empty($data['combat_skill'])) {
            return "Combat Skill is required.";
        }
        return true;
    }
}