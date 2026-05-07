<header>
    <nav class="flex items-center justify-between bg-white py-4 px-8 shadow-sm">
        <button 
            class="px-4 py-2 rounded-lg cursor-pointer hover:bg-offwhite transition-colors" 
            onClick="window.location.href=`welcome.php`"
        >
            Home
        </button>
        <span class="text-xl font-bold tracking-widest">BLOGGEN</span>
        <span class="flex gap-2">
            <button 
                class="px-4 py-2 rounded-lg border border-gray cursor-pointer hover:bg-offwhite transition-colors"
                onClick="window.location.href=`login.php`"    
            >
                Login
            </button>
            <button 
                class="px-4 py-2 rounded-lg bg-primary cursor-pointer hover:opacity-90 transition-opacity font-semibold"
                onClick="window.location.href=`register.php`"
            >
                Sign up
            </button>
        </span>
    </nav>
</header>
