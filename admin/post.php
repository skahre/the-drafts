<?php
require_once "../components/header.php";
require_once "../components/icons.php";
require_once "../db/db.php";

// Redirect to welcome page if not logged in
if (!isset($_SESSION["username"], $_SESSION["user_id"])) {
    header("Location: /blog.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="../css/output.css">
    <title><?= htmlspecialchars(
        $saved["title"] ?? "View Post",
    ) ?> | The Drafts</title>
</head>
<body>

    <main class="flex flex-1 items-start justify-center p-8 bg-offwhite">
        <div class="w-full max-w-2xl flex flex-col gap-4">

            <div class="flex items-center justify-between">
                <a href="<?= BASE ?>/admin/dashboard.php" class="flex items-center gap-1.5 text-sm text-gray hover:text-black transition-colors">
                    <?= icon("arrow-left", "w-4 h-4") ?>
                    Back to dashboard
                </a>
                <a href="<?= BASE ?>/admin/edit-post.php" class="flex items-center gap-1.5 text-sm font-semibold px-3 py-1.5 rounded-lg bg-primary hover:opacity-90 transition-opacity">
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
