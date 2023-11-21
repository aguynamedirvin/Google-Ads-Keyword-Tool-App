<?php
// controllers/ProfileController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/File.php';

class ProfileController {
    public function index() {
        // Check if user is logged in
        $user = new User();

        if (!$user->isLoggedIn()) {
            header('Location: /login');
            exit();
        }

        // Check if the request method is POST and the password fields are set
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newPassword'])) {

            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmNewPassword = $_POST['confirmNewPassword'];

            if ($newPassword != $confirmNewPassword) {
                echo "Passwords do not match.";
            }

            $updatePassword = $user->updatePassword($currentPassword, $newPassword, $confirmNewPassword);

            if ($updatePassword) {
                echo "Password updated successfully.";
            } else {
                echo "Failed to update password.";
            }
        }


        // Check if the request method is POST and the avatar file is set
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
            $file = $_FILES['photo'];

            // Upload the file
            $photoUpload = new File();
            $photoFile = $photoUpload->upload($file);

            // Update the user's photo
            $updatePhoto = $user->updateAvatar($photoFile['data']['filename']);
        }

        // Check if the request method is POST and the first name and last name are set
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
            $email = $_POST['email'];
            $currentEmail = $user->email();

            if ($email == $currentEmail) {
                echo "Email is the same.";
            } else {
                $updateEmail = $user->updateEmail($email);

                if ($updateEmail) {
                    echo "Email updated successfully.";
                } else {
                    echo "Failed to update email.";
                }
            }

            
        }

        // Render the dashboard view
        include 'views/dashboard/profile.php';
    }
}
