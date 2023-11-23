<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google AI Profits</title>

    <!-- Include any CSS/JS files -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://hvacmarketinge53.sg-host.com/assets/css/styles.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tabulator-tables/dist/js/tabulator.min.js"></script>
</head>
<body class="bg-slate-100/50 dark:bg-gradient-to-r dark:from-slate-900 dar:via-slate-800 dark:to-slate-900 dark:text-white">


<header id="header" class="bg-white p-5 px-8 border-b border-slate-100 shadow-sm dark:border-slate-700 dark:bg-slate-900">
    <div id="app-header-nav" class="flex flex-row mx-auto justify-between gap-6">

        <!-- Hamburger Menu Button -->
        <a id="open-sidebar" class="md:hidden !bg-neutral-50 p-2 border border-slate-100 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </a>


        <ul class="hidden md:flex flex-row items-center gap-6 text-base shrink-0">
            <li class="hidden md:inline-flex"><a href="/dashboard">Dashboard</a></li>
            <li class=""><a href="/dashboard/keywords">Keywords</a></li>
            <li class="button"><a href="/dashboard/keywords/upload">Upload</a></li>
        </ul>

        <ul class="flex flex-row items-center gap-6 text-base shrink-0">

            <li id="userSettings" class="menu-has-subitems gap-2 relative">
                <a type="button" class="bg-slate-50 px-2 py-1 rounded-md border cursor-pointer border-slate-300 hover:border-slate-400 inline-flex flex flex-row items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900" aria-expanded="false">
                    <?php
                        $userAvatarUrl = new File();
                        $userAvatarUrl = $userAvatarUrl->find($user->avatar());
                    ?>
                    <img src="/<?= $userAvatarUrl ?>" class="w-8 h-8 rounded-full object-cover">
                    <span>Settings</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </a>

                <div class="menu-sub-menu absolute z-10 mt-5 flex w-screen max-w-max float-left translate-x-0 left-0 md:-translate-x-1/2 md:px-4 hidden">
                    <div class="w-screen max-w-md flex-auto overflow-hidden rounded-3xl bg-white text-sm leading-6 shadow-lg ring-1 ring-gray-900/5">
                        <div class="p-4">
                            
                            <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                    <svg class="h-6 w-6 text-gray-600 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                </div>
                                <div>
                                    <a href="/dashboard/profile" class="font-semibold text-gray-900">
                                        Account Settings
                                        <span class="absolute inset-0"></span>
                                    </a>
                                    <p class="mt-1 text-gray-600">Manage your account and profile settings</p>
                                </div>
                            </div>

                            <div class="group relative flex items-center gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                                <div class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                                <svg class="h-6 w-6 text-gray-600 group-hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>

                                </div>
                                <div>
                                    <a href="/logout" class="font-semibold text-gray-900 group-hover:text-red-600">
                                        Logout
                                        <span class="absolute inset-0"></span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </li>
            
        </ul>

    </div>
</header>


<main class="app flex flex-row items-stretch min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="w-[300px] z-index-99 -translate-x-full md:translate-x-0 md:relative md:rounded-none rounded-3xl absolute bg-white p-5 border-r border-slate-100 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <ul clsss="flex flex-col gap-4 ">
            <li class="border-b border-slate-100 p-3 rounded-md hover:bg-slate-100">
                <a href="/dashboard">Dashboard</a>
            </li>
            <li class="border-b border-slate-100 p-3 rounded-md hover:bg-slate-100">
                <a href="/dashboard/keywords">Keywords</a>
            </li>

            <li class="border-b border-slate-100 p-3 rounded-md hover:bg-slate-100">Link 2</li>
            <li class="p-3 rounded-md hover:bg-slate-100 mb-6">Link 3</li>
            <li class="button"><a href="/dashboard/keywords/upload">Upload</a></li>
        </ul>
    </div>