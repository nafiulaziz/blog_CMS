<?php
namespace LH;

class RequestHandler 
{
    public static function getRequest($key, $default = null) 
    {
        return $_GET[$key] ?? $default;
    }

    public static function postRequest($key, $default = null) 
    {
        return $_POST[$key] ?? $default;
    }

    public static function validate($data, $rules) 
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            
            if (isset($rule['required']) && $rule['required'] && empty($value)) {
                $errors[$field] = $field . ' is required';
                continue;
            }

            if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
                $errors[$field] = $field . ' must be at least ' . $rule['min_length'] . ' characters';
            }

            if (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
                $errors[$field] = $field . ' must not exceed ' . $rule['max_length'] . ' characters';
            }
        }

        return $errors;
    }

    public static function sanitize($data) 
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public static function handleFileUpload($file, $uploadDir = 'uploads/') 
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'No file uploaded or upload error'];
        }

        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Only JPG, JPEG, and PNG files are allowed'];
        }

        $fileInfo = pathinfo($file['name']);
        $filename = $fileInfo['filename'] . '_' . date('YmdHis') . '.' . $fileInfo['extension'];
        $uploadPath = $uploadDir . $filename;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename, 'path' => $uploadPath];
        }

        return ['success' => false, 'message' => 'Failed to upload file'];
    }
}