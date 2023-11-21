<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google AI Profits</title>

    <!-- Include any CSS/JS files -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://hvacmarketinge52.sg-host.com/app1/assets/css/styles.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tabulator-tables/dist/js/tabulator.min.js"></script>
</head>
<body>


<header class="flex flex-row p-5 border-b border-slate-300 justify-between overflow-x-auto gap-6">
    <ul class="flex flex-row items-center gap-6 text-base shrink-0">
        <li class=""><a href="/dashboard" class="hover:text-blue-800">Dashboard</a></li>
        <li class=""><a href="/dashboard/keywords" class="hover:text-blue-800">Keywords</a></li>
        <li class="button"><a href="/dashboard/keywords/upload" class="hover:text-blue-800">Upload</a></li>
    </ul>

    <ul class="flex flex-row items-center gap-6 text-base shrink-0">
        <li class="logout"><a href="/logout" class="hover:text-blue-800">Log out</a></li>

        <li class="flex flex-row items-center gap-2">
            <a href="/dashboard/profile">
                <?php
                    $userAvatarUrl = new File();
                    $userAvatarUrl = $userAvatarUrl->find($user->avatar());
                ?>
                <img src="/<?php echo $userAvatarUrl ?>" class="w-8 h-8 rounded-full object-cover">
            </a>
            <a href="/dashboard/profile" class="hover:text-blue-800"><?php echo $user->firstName(); ?></a>
        </li>
    </ul>
</header>