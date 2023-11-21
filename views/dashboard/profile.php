<?php
// views/dashboard/profile.php

include 'partials/header.php';

?>

<div class="screen container mx-auto">

        <div class="p-4">
            <h1 class="text-3xl md:text-6xl font-bold mb-2 md:mb-4">Profile Settings</h1>
            <p class="text-xl">Manage your profile information.</p>
        </div>
        
        <div class="p-4">
            
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md">

                
                <form class="" action="" method="POST" enctype="multipart/form-data" class="flex md:flex-row gap-4">
                    <h2 class="font-medium text-xl mb-4">General Information</h2>
                    
                    <div class="mb-6">
                        <?php
                            $userAvatarUrl = new File();
                            $userAvatarUrl = $userAvatarUrl->find($user->avatar());

                            if ($userAvatarUrl):
                        ?>
                            <img src="/<?= $userAvatarUrl ?>" class="w-24 h-24 rounded-2xl object-cover">
                        <?php else: ?>
                            <div class="w-32 h-32 rounded-full bg-gray-300"></div>
                        <?php endif; ?>
                        <input type="file" name="photo" required>
                        <button class="button" type="submit">Update Photo</button>
                    </div>

                    <div class="flex flex-col gap-4">

                        <div class="flex">
                            <div class="flex flex-col">
                                <label for="firstName">First Name</label>
                                <input type="text" name="firstName" placeholder="First Name" required value="<?= $user->firstName() ?>">
                            </div>

                            <div class="flex flex-col">
                                <label for="lastName">Last Name</label>
                                <input type="text" name="lastName" placeholder="Last Name" required value="<?= $user->lastName() ?>">
                            </div>
                        </div>
                        

                        <div class="flex flex-col">
                            <label for="email">Update Email</label>
                            <input type="email" name="email" placeholder="Email" required value="<?= $user->email() ?>">
                        </div>

                        <div class="flex flex-col gap-4">
                            <h2 class="font-medium text-xl mt-2">Change Password</h2>
                            <input type="password" name="currentPassword" placeholder="Current Password" required>
                            <input type="password" name="newPassword" placeholder="New Password" required>
                            <input type="password" name="confirmNewPassword" placeholder="Confirm New Password" required>
                        </div>
                    </div>

                    <button class="button mt-4" type="submit">Save</button>
                </form>
            </div>

        </div>

    </div>
</div>