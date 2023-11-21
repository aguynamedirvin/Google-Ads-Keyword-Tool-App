<?php
// views/dashboard/profile.php

include 'partials/header.php';

?>

<div class="screen">

        <div class="p-8">
            <h1 class="text-5xl font-medium mb-4">Profile Settings</h1>
            <p class="text-xl">Manage your profile information.</p>
        </div>
        
        <div class="p-8">
            
            <div class="bg-slate-300 rounded-lg p-6">

                <h2 class="font-medium text-xl mb-4">Update Photo</h2>

                <?php
                    $userAvatarUrl = new File();
                    $userAvatarUrl = $userAvatarUrl->find($user->avatar());

                    if ($userAvatarUrl):
                ?>
                    <img src="/<?php echo $userAvatarUrl ?>" class="w-32 h-32 rounded-full object-cover">
                <?php else: ?>
                    <div class="w-32 h-32 rounded-full bg-gray-300"></div>
                <?php endif; ?>


                <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    <input type="file" name="photo" required>
                    <button class="button" type="submit">Update Photo</button>
                </form>

                <h2 class="font-medium text-xl mb-4">Update Name</h2>
                <form action="settings" method="POST" class="flex flex-col gap-4">
                    <input type="text" name="firstName" placeholder="First Name" required value="<?php echo $user->firstName() ?>">
                    <input type="text" name="lastName" placeholder="Last Name" required value="<?php echo $user->lastName() ?>">
                    <button class="button" type="submit">Update Name</button>
                </form>

                <h2 class="font-medium text-xl mb-4">Update Email</h2>
                <form action="" method="POST" class="flex flex-col gap-4">
                    <input type="email" name="email" placeholder="Email" required value="<?php echo $user->email() ?>">
                    <button class="button" type="submit">Update Email</button>
                </form>

                <h2 class="font-medium text-xl mb-4">Change Password</h2>
                <form action="" method="POST" class="flex flex-col gap-4">
                    <input type="password" name="currentPassword" placeholder="Current Password" required>
                    <input type="password" name="newPassword" placeholder="New Password" required>
                    <input type="password" name="confirmNewPassword" placeholder="Confirm New Password" required>
                    <button class="button" type="submit">Change Password</button>
                </form>
            </div>

        </div>

    </div>
</div>