<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>Sign Up | The Drafts</title>
</head>
<body>

    <?php
    require_once "components/header.php";
    require_once "components/password-input.php";
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

                <?php password_input('password', 'Password', '6 or more characters'); ?>
                <?php password_input('password_confirm', 'Confirm password', null, 'confirm-password-error'); ?>

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
