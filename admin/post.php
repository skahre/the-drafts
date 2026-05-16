<?php
require_once "../utils/bases.php";
require_once "../components/icons.php";
require_once "../db/db.php";

// Redirect to blog page if not logged in
if (!isset($_SESSION["username"], $_SESSION["user_id"])) {
    header("Location: /blog.php");
    exit();
}

$post_redirect_url = BASE . "/admin/dashboard.php";

if ($_GET["id"]) {
    $post_id = $_GET["id"];
    $post = get_post_by_id($post_id);
    if (!$post) {
        header("Location: $post_redirect_url");
        exit();
    }
    if ($post["user_id"] !== $_SESSION["user_id"]) {
        $auth_redirect_url =
            BASE .
            "/blog.php?id=" .
            $post["user_id"] .
            "&post_id=" .
            $post["id"];
        header("Location: $auth_redirect_url");
        exit();
    }
    $image = get_image_by_post($post_id);
} else {
    header("Location: $post_redirect_url");
    exit();
}

$post_info = [
    "title" => $post["title"],
    "created_at" => $post["created_at"],
    "content" => $post["content"],
    "image" => $image,
];
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="../css/output.css">
    <title><?= htmlspecialchars(
        $post["title"] ?? "View Post",
    ) ?> | The Drafts</title>
</head>
<body>
    <?php require_once "../components/header.php"; ?>
    <main class="flex flex-1 items-start justify-center p-8 bg-offwhite">
        <div class="w-full max-w-2xl flex flex-col gap-4">

            <div class="flex items-center justify-between">
                <a href="<?= BASE ?>/admin/dashboard.php" class="flex items-center gap-1.5 text-sm text-gray hover:text-black transition-colors">
                    <?= icon("arrow-left", "w-4 h-4") ?>
                    Back to dashboard
                </a>
                <a href="<?= BASE ?>/admin/edit-post.php?id=<?= $_GET["id"] ??
    "" ?>" class="flex items-center gap-1.5 text-sm font-semibold px-3 py-1.5 rounded-lg bg-primary hover:opacity-90 transition-opacity">
                    <?= icon("pencil", "w-4 h-4") ?>
                    Edit post
                </a>
            </div>

            
            
            <?php include "../components/content.php"; ?>
            
        </div>
    </main>

    <?php require_once "../components/footer.php"; ?>

</body>
</html>
