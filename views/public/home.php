<?php
// views/public/home.php
include 'templates/header.php';
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
        <h1 class="text-6xl font-bold gradient-text">Make Your Google Ads 2x As Profitable</h1>
        <p class="text-2xl mt-4 text-slate-200">Cut budget killing phrases and words and make your ads 2x as profitable in seconds</p>

        <div class="mt-12 button-group">
            <a class="button" href="register">Sign Up</a>
            <a class="button" href="login">Log In</a>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>