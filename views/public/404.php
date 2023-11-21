<?php
// views/public/404.php
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
        <h1 class="text-8xl font-bold gradient-text">404</h1>
        <p class="text-2xl mt-4 text-slate-200">The page you were looking for does not exist or has been move.</p>

        <div class="mt-12 button-group">
            <a class="button" href="/">Back to homepage</a>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>