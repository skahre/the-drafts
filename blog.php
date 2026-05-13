<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>Blog | The Drafts</title>
</head>
<body>

    <?php require_once "components/header.php"; ?>

    <main class="flex flex-row gap-4 p-4 flex-1 bg-offwhite">
        <aside class="flex flex-col gap-4 w-72 shrink-0">
            <?php
            // PLACEHOLDER: Fetch blogger information from database
            $info_user = [
                "name" => $blogger["name"] ?? "Unknown Blogger",
                "username" => $blogger["username"] ?? "place_holder",
                "bio" => $blogger["bio"] ?? null,
            ];

            // Include same info component as in profile page
            // Gets necessary data from $info_user array
            include "components/info.php";

            include "components/menu.php";
            ?>
        </aside>
        <section class="flex-1 min-w-0">
            <?php require_once "components/content.php"; ?>
        </section>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
