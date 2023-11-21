<?php
// views/auth/register_success.php
include 'views/public/templates/header.php'; 
?>

<style>
    .gradient-text {
        background: linear-gradient(45deg, white, silver);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>


<div class="flex flex-col min-h-screen justify-center bg-gradient-to-r from-black via-gray-800 to-gray-900 p-4">
    <div class="flex flex-col items-center text-center">
        <h1 class="text-4xl font-bold gradient-text">Succesfully registered!</h1>
        <p class="text-2xl mt-4 text-slate-200">
            Access your account <a href="/dashboard" class="underline">here</a>.
        <div class="mt-12 button-group">
            <a class="button" href="/dashboard">Access account</a>
        </div>
    </div>
</div>

<?php include 'views/public/templates/footer.php'; ?>