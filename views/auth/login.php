<?php 
// views/auth/login.php
include 'views/public/templates/header.php'; 
?>

<main class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-black via-gray-800 to-gray-900">
    
    <div class="flex flex-col w-96">
        <h2 class="text-4xl mb-6 text-white font-medium">Login</h2>

        <?php
            if (isset($_SESSION['login_errors'])) {
                foreach($_SESSION['login_errors'] as $error) {
                    echo '<p class="text-red-500 mb-2">' . htmlspecialchars($error) . '</p>';
                }
                unset($_SESSION['login_errors']);
            }

            // Retain input values after form submission
            $usernameValue = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
            $passwordValue = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
        ?>

        <form action="login" method="POST" class="flex flex-col gap-4">
            <input type="text" name="username" placeholder="Username" required value="<?php echo $usernameValue; ?>">
            <input type="password" name="password" placeholder="Password" required value="<?php echo $passwordValue; ?>">
            <button class="button" type="submit">Login</button>
            <p class="text-sm text-slate-300">Forgot password? <a href="reset" class="underline">Reset password</a></p>
        </form>
    </div>

    <p class="mt-6 text-slate-400">Don't have an account? <a href="register" class="text-slate-300 underline">Sign up</a></p>

</main>

<?php include 'views/public/templates/footer.php'; ?>