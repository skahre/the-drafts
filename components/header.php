<header>
    <nav class="relative flex items-center justify-between bg-white py-4 px-8 shadow-sm">
        <a 
            class="px-4 py-2 rounded-lg cursor-pointer hover:bg-offwhite transition-colors" 
            href="<?= BASE ?>/welcome.php"
        >
            Home
        </a>
        <span class="absolute left-1/2 -translate-x-1/2 text-xl font-bold tracking-widest">THE DRAFTS</span>
        <span class="flex gap-2">
            <?php // Only render the admin and sign out buttons if the user is logged in, otherwise render the log in and sign up buttons

if (isset($_SESSION["username"])): ?>

                <a 
                    class="px-4 py-2 rounded-lg cursor-pointer hover:bg-offwhite transition-colors" 
                    href="<?= BASE ?>/admin/dashboard.php"
                >
                    Admin
                </a>
                <a
                    class="px-4 py-2 rounded-lg border border-gray cursor-pointer hover:bg-offwhite transition-colors"
                    href="<?= BASE ?>/utils/logout.php"
                >
                    Sign out
                </a>
<?php else: ?>
                <a
                    class="px-4 py-2 rounded-lg border border-gray cursor-pointer hover:bg-offwhite transition-colors"
                    href="<?= BASE ?>/login.php"
                >
                    Log in
                </a>
                <a
                    class="px-4 py-2 rounded-lg bg-primary cursor-pointer hover:opacity-90 transition-opacity font-semibold"
                    href="<?= BASE ?>/register.php"
                >
                    Sign up
                </a>
            <?php endif; ?>
        </span>
    </nav>
</header>
