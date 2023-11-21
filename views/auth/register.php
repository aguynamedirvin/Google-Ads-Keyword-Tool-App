<?php
// auth/login.php
include 'views/public/templates/header.php';
?>

<main class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-black via-gray-800 to-gray-900">
    
    <div class="flex flex-col w-96">
        <h2 class="text-4xl mb-6 text-white font-medium">Register</h2>

        <?php
        // Check for registration errors and display them
        if (isset($_SESSION['registration_errors'])) {
            foreach ($_SESSION['registration_errors'] as $error) {
                echo '<p class="text-red-500 mb-2">' . htmlspecialchars($error) . '</p>';
            }
            unset($_SESSION['registration_errors']); // Clear the error messages after displaying
        }
        
        $usernameValue = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
        $passwordValue = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
        $confirmPasswordValue = isset($_POST['confirmPassword']) ? htmlspecialchars($_POST['confirmPassword']) : '';
        $emailValue = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        $firstNameValue = isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : '';
        $lastNameValue = isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : '';

        ?>

       <form action="register" method="post" class="flex flex-col gap-4">
            <div class="flex flex-col md:flex-row gap-4">
                <input type="text" name="firstName" placeholder="First Name" required value="<?php echo $firstNameValue ?>">
                <input type="text" name="lastName" placeholder="Last Name" required value="<?php echo $lastNameValue?>">
            </div>
            <input type="text" name="username" placeholder="Username" required value="<?php echo $usernameValue?>">
            <input type="email" name="email" placeholder="Email" required value="<?php echo $emailValue?>">
            <input type="password" name="password" placeholder="Password" required value="<?php echo $passwordValue?>">
            <input type="password" name="confirmPassword" placeholder="Confirm Password" required value="<?php echo $confirmPasswordValue?>">
            <button class="button" type="submit">Register</button>
        </form>


    </div>

    <p class="mt-6 text-slate-400">Already have an account? <a href="login" class="text-slate-300 underline">Log in</a></p>
</main>

<?php include 'views/public/templates/footer.php'; ?>