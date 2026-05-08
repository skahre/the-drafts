<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>Sign Up | The Drafts</title>
</head>
<body>

    <?php
    require_once "components/header.php";
    require_once "db/db.php";

    if (isset($_SESSION["username"])) {
        header("Location: admin/dashboard.php");
        exit();
    }

    $error = $_SESSION["error"] ?? "";
    unset($_SESSION["error"]);

    if ($_POST) {
        $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $result = add_user($_POST["username"], $hashedPassword);

        if ($result instanceof mysqli_sql_exception) {
            if ($result->getCode() === 1062) {
                $_SESSION["error"] = "That username is already taken.";
            } elseif ($result->getCode() === 1406) {
                $_SESSION["error"] = "Username is too long.";
            } else {
                $_SESSION["error"] = "Registration failed. Please try again.";
            }
            header("Location: register.php");
            exit();
        } else {
            $_SESSION["username"] = $username;
            header("Location: admin/dashboard.php");
            $_SESSION["error"] = "Registrering lyckades!";
            exit();
        }
    }
    ?>

    <script src="javascript/validation.js"></script>

    <main class="flex flex-1 items-center justify-center p-4 bg-offwhite">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm flex flex-col gap-6">

            <h1 class="text-2xl font-bold text-center">Create account</h1>

            <form method="POST" onsubmit="return validatePassword(this)" class="flex flex-col gap-4">

                <div class="flex flex-col gap-1">
                    <label for="username" class="text-sm font-semibold">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary"
                    >
                    <span class="text-xs text-error"><?php echo $error; ?></span>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="password" class="text-sm font-semibold">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            minlength="6"
                            class="w-full border border-gray rounded-lg px-3 py-2 pr-10 bg-offwhite focus:outline-none focus:border-primary"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password', this)" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                        >
                            <svg 
                                data-eye="open" 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="w-5 h-5" fill="none" viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" 
                                />
                                <path 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" 
                                />
                            </svg>
                            <svg 
                                data-eye="closed" 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="w-5 h-5 hidden" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" 
                                />
                            </svg>
                        </button>
                    </div>
                    <span class="text-xs text-gray">6 or more characters</span>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="password_confirm" class="text-sm font-semibold">Confirm password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirm"
                            name="password_confirm"
                            required
                            minlength="6"
                            class="w-full border border-gray rounded-lg px-3 py-2 pr-10 bg-offwhite focus:outline-none focus:border-primary"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password_confirm', this)" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
                        >
                            <svg 
                                data-eye="open" 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="w-5 h-5" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor"
                            >
                                <path 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" 
                                />
                                <path 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round"
                                    stroke-width="2" 
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" 
                                />
                            </svg>
                            <svg 
                                data-eye="closed" 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="w-5 h-5 hidden" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor">
                                <path 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    stroke-width="2" 
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" 
                                />
                            </svg>
                        </button>
                    </div>
                    <span class="text-xs text-error" id="confirm-password-error"></span>
                </div>

                <button
                    type="submit"
                    class="bg-primary font-semibold py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer mt-2"
                >
                    Sign up
                </button>

            </form>

            <p class="text-sm text-center text-(--gray-color)">
                Har du redan ett konto? 
                <a href="login.php" class="text-(--text-color) font-semibold underline">
                    Logga in
                </a>
            </p>

        </div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
