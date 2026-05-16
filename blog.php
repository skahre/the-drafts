<?php
require_once "utils/bases.php";
require_once "db/db.php";

$redirect_url = BASE . "/welcome.php";

if (isset($_GET["id"])) {
    $blogger_id = $_GET["id"];
    $blogger = get_user_by_id($blogger_id);
    if (!$blogger) {
        header("Location: $redirect_url");
        exit();
    }
} else {
    header("Location: $redirect_url");
    exit();
}

$user_posts = get_posts_by_user($blogger_id);

$post_id = $_GET["post_id"] ?? null;

if ($post_id) {
    $display_post = get_post_by_id($post_id);
} else {
    $display_post = $user_posts[0];
    $post_id = $display_post["id"];
}

$info_user = [
    "profile_image" => $blogger["profile_image"] ?? null,
    "name" => $blogger["title"] ?? $blogger["username"],
    "username" => $blogger["username"] ?? "place_holder",
    "bio" => $blogger["presentation"] ?? null,
];

$image = get_image_by_post($post_id);

$post_info = [
    "title" => $display_post["title"],
    "created_at" => $display_post["created_at"],
    "content" => $display_post["content"],
    "image" => $image,
];
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
            // Include same info component as in profile page
            // Gets necessary data from $info_user array
            include "components/user-info.php";

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
