<?php

namespace app\library;

/**
 * 校验输入
 */
class Validate
{
    /**
     * @param $input
     * @param $rules
     *
     * @return array
     */
    public static function check($input, $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $fieldRules) {
            $fieldValue = $input[$field] ?? NULL;
            foreach ($fieldRules as $rule => $ruleValue) {
                switch ($rule) {
                    case 'required':
                        if(empty($fieldValue)) {
                            $errors[] = "[{$field}]" . ' : ' .'This field is required.';
                        }
                        break;
                    case 'email':
                        if ($fieldValue && !filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "[{$field}]" . ' : ' .'This field must be a valid email address.';
                        }
                        break;
                    case 'numeric':
                        if ($fieldValue && !is_numeric($fieldValue)) {
                            $errors[] = "[{$field}]" . ' : ' .'This field must be a number.';
                        }
                        break;
                    case 'min':
                        if ($fieldValue && strlen($fieldValue) < $ruleValue) {
                            $errors[] = "[{$field}]" . ' : ' .'This field must be at least ' . $ruleValue . ' characters long.';
                        }
                        break;
                    case 'max':
                        if ($fieldValue && strlen($fieldValue) > $ruleValue) {
                            $errors[] = "[{$field}]" . ' : ' .'This field must be no more than ' . $ruleValue . ' characters long.';
                        }
                        break;
                    case 'phone_num':
                        if ($fieldValue && !preg_match("/^1[3456789]\d{9}$/", $fieldValue)) {
                            $errors[] = "[{$field}]" . ' : ' .'This field must be a valid mobile number.';
                        }
                        break;
                }
            }
        }
        return $errors;
    }
}