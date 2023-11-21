<?php
// models/File.php

class File {

    public function upload($file) {
        $filename = $file['tmp_name'];
        $destination = 'uploads/' . $file['name'];
        $fileType = pathinfo($destination, PATHINFO_EXTENSION);
        $allowedTypes = ['csv', 'jpg', 'jpeg', 'png', 'gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB in bytes

        // Check file type
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Only CSV, JPG, JPEG, PNG, and GIF types are allowed.'];
        }

        // Check file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File is too large. Maximum file size is 5MB.'];
        }

        // Unique filename generation to avoid overwriting
        $filename = uniqid() . '.' . $fileType;
        $destination = 'uploads/' . $filename;

        // Move the uploaded file to the destination
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success'   => true, 
                'message'   => 'File uploaded successfully.', 
                'data'      => ['filename' => $filename, 'destination' => $destination]];
        } else {
            return ['success' => false, 'message' => 'Failed to upload file.'];
        }
    }

    // Find a file by name
    public function find($file) {
        $directory = 'uploads/';
        $files = scandir($directory);

        if (in_array($file, $files)) {
            return $directory . $file;
        } else {
            return false;
        }
    }
    
    // Delete a file
    public function delete($file) {
        $directory = 'uploads/';
        $filePath = $directory . $file;

        if (file_exists($filePath)) {
            unlink($filePath);
            return ['success' => true, 'message' => 'File deleted successfully.'];
        } else {
            return ['success' => false, 'message' => 'File does not exist.'];
        }
    }
}