<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>Log In | The Drafts</title>
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
        $username = $_POST["username"];

        $user = get_user($username);

        if (
            $user !== null &&
            password_verify($_POST["password"], $user["password"])
        ) {
            $_SESSION["username"] = $username;
            header("Location: admin/dashboard.php");
            $_SESSION["error"] = "Login failed";
            exit();
        }

        $_SESSION["error"] = "Incorrect username or password";
        header("Location: login.php");
        exit();
    }
    ?>

    <main class="flex flex-1 items-center justify-center p-4 bg-offwhite">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm flex flex-col gap-6">

            <h1 class="text-2xl font-bold text-center">Log in</h1>

            <form method="POST" class="flex flex-col gap-4">

                <div class="flex flex-col gap-1">
                    <label for="username" class="text-sm font-semibold">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary"
                    >
                </div>

                <div class="flex flex-col gap-1">
                    <label for="password" class="text-sm font-semibold">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="6"
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary"
                    >
                </div>
                <span class="text-xs text-error"><?php echo $error; ?></span>

                <button
                    type="submit"
                    class="bg-primary font-semibold py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer mt-2"
                >
                    Log in
                </button>

            </form>

            <p class="text-sm text-center text-gray">
                Don't have an account? <a href="register.php" class="text-black font-semibold underline">Sign up</a>
            </p>

        </div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
