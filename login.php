<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>BLOGGEN</title>
</head>
<body>

    <?php require_once "components/header.php"; ?>

    <main class="flex flex-1 items-center justify-center p-4 bg-offwhite">
        <div class="bg-white rounded-2xl p-8 w-full max-w-sm flex flex-col gap-6">

            <h1 class="text-2xl font-bold text-center">Logga in</h1>

            <form method="POST" action="" class="flex flex-col gap-4">

                <div class="flex flex-col gap-1">
                    <label for="username" class="text-sm font-semibold">Användarnamn</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary"
                    >
                </div>

                <div class="flex flex-col gap-1">
                    <label for="password" class="text-sm font-semibold">Lösenord</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="6"
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary"
                    >
                </div>

                <button
                    type="submit"
                    class="bg-primary font-semibold py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer mt-2"
                >
                    Logga in
                </button>

            </form>

            <p class="text-sm text-center text-gray">
                Inget konto? <a href="register.php" class="text-black font-semibold underline">Registrera dig</a>
            </p>

        </div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
