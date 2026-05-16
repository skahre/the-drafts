<?php
// Get all necessary files
require_once "utils/bases.php";
require_once "components/password-input.php";
require_once "db/db.php";

// Redirect to dashboard if already logged in
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"])) {
    header("Location: admin/dashboard.php");
    exit();
}

// Initialize error message for registration
$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);

// If form is submitted, attempt to register the user
if ($_POST) {
    $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $result = add_user(strtolower($_POST["username"]), $hashedPassword);

    // Check if the result is an exception and handle it accordingly
    if ($result instanceof mysqli_sql_exception) {
        if ($result->getCode() === 1062) {
            $_SESSION["error"] = "That username is already taken.";
        } elseif ($result->getCode() === 1406) {
            $_SESSION["error"] = "Username is too long.";
        } else {
            $_SESSION["error"] = "Registration failed. Please try again.";
        }

        // Redirect back to the registration page with the error message
        header("Location: register.php");
        exit();
    } else {
        // Set session variables and redirect to dashboard on successful registration
        $_SESSION["username"] = $_POST["username"];
        $_SESSION["user_id"] = $result;
        header("Location: admin/dashboard.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>Sign Up | The Drafts</title>
</head>
<body>

    <?php require_once "components/header.php"; ?>

    <!-- Include JavaScript validation script -->
    <script src="javascript/validation.js"></script>
    <script src="javascript/utils.js"></script>

    <main class="flex flex-1 items-center justify-center p-4 bg-offwhite">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm flex flex-col gap-6">

            <h1 class="text-2xl font-bold text-center">Create account</h1>

            <!-- Only submit to PHP if JavaScript validation passes -->
            <form method="POST" onsubmit="return validatePassword(this, 'confirm-password-error')" class="flex flex-col gap-4">

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

                <!-- Use the password_input component for both password fields -->
                <?php password_input(
                    "password",
                    "Password",
                    "6 or more characters",
                ); ?>
                <?php password_input(
                    "password_confirm",
                    "Confirm password",
                    null,
                    "confirm-password-error",
                ); ?>

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
