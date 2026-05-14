<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>Blog | The Drafts</title>
</head>
<body>

    <?php require_once "components/header.php"; ?>

    <main class="flex p-4 gap-4 flex-1 bg-offwhite">
        <aside class="flex flex-col gap-4 flex-1 min-w-0">
            <?php
            // PLACEHOLDER: Fetch blogger information from database
            $info_user = [
                "profile_image" => $blogger["profile_image"] ?? null,
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
        <section class="">
            <?php require_once "components/content.php"; ?>
        </section>
        <div class="flex-1"></div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
