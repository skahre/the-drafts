<?php
require_once "utils/bases.php";
require_once "db/db.php";

$redirectUrl = BASE . "/welcome.php";

if ($_GET["id"] && $_GET["post_id"]) {
    $bloggerId = $_GET["id"];
    $postId = $_GET["post_id"];
    $post = get_post_by_id($postId);
    $blogger = get_user_by_id($bloggerId);
    if (!$post || !$blogger || $post["user_id"] != $bloggerId) {
        header("Location: $redirectUrl");
        exit();
    }
    $image = get_image_by_post($postId);
} else {
    header("Location: $redirectUrl");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title><?= htmlspecialchars(
        $blogger["title"] ?? ($blogger["username"] ?? "Blog"),
    ) ?>  | The Drafts</title>
</head>
<body>
    <?php require_once "components/header.php"; ?>
    <main class="flex p-4 gap-4 flex-1 bg-offwhite">
        <aside class="flex flex-col gap-4 flex-1 min-w-0">
            <?php
            // PLACEHOLDER: Fetch blogger information from database
            $info_user = [
                "profile_image" => $blogger["profile_image"] ?? null,
                "name" => $blogger["title"] ?? "Unknown Blogger",
                "username" => $blogger["username"] ?? "place_holder",
                "bio" => $blogger["presentation"] ?? null,
            ];

            // Include same info component as in profile page
            // Gets necessary data from $info_user array
            include "components/info.php";

            include "components/menu.php";
            ?>
        </aside>
        <section class="w-2xl">
            <?php require_once "components/content.php"; ?>
        </section>
        <div class="flex-1"></div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
