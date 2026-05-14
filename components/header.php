<?php
// Start a user session.
// Since this file is included in every page, we can be sure that the session is always started
session_start();

// Define a constant for the base URL of the project, making sure routing works regardless of current directory.
define("BASE", "/PROJEKT");
?>

<header>
    <nav class="relative flex items-center justify-between bg-white py-4 px-8 shadow-sm">
        <button 
            class="px-4 py-2 rounded-lg cursor-pointer hover:bg-offwhite transition-colors" 
            onClick="window.location.href=`<?= BASE ?>/welcome.php`"
        >
            Home
        </button>
        <span class="absolute left-1/2 -translate-x-1/2 text-xl font-bold tracking-widest">THE DRAFTS</span>
        <span class="flex gap-2">
            <?php // Only render the admin and sign out buttons if the user is logged in, otherwise render the log in and sign up buttons

if (isset($_SESSION["username"])): ?>

                <button 
                    class="px-4 py-2 rounded-lg cursor-pointer hover:bg-offwhite transition-colors" 
                    onClick="window.location.href=`<?= BASE ?>/admin/dashboard.php`"
                >
                    Admin
                </button>
                <button
                    class="px-4 py-2 rounded-lg border border-gray cursor-pointer hover:bg-offwhite transition-colors"
                    onClick="window.location.href=`<?= BASE ?>/utils/logout.php`"
                >
                    Sign out
                </button>
            <?php else: ?>
                <button
                    class="px-4 py-2 rounded-lg border border-gray cursor-pointer hover:bg-offwhite transition-colors"
                    onClick="window.location.href=`<?= BASE ?>/login.php`"
                >
                    Log in
                </button>
                <button
                    class="px-4 py-2 rounded-lg bg-primary cursor-pointer hover:opacity-90 transition-opacity font-semibold"
                    onClick="window.location.href=`<?= BASE ?>/register.php`"
                >
                    Sign up
                </button>
            <?php endif; ?>
        </span>
    </nav>
</header>
