<?php
// Getting all necessary files and data
require_once "utils/bases.php";
require_once "components/icons.php";
require_once "components/profile-image.php";
require_once "db/db.php";

// Fetch all posts from the database
$posts = get_all_posts();
$bloggers = get_users();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>The Drafts</title>
</head>
<body>

    <?php require_once "components/header.php"; ?>

    <main class="flex flex-row p-4 gap-4 flex-1 bg-offwhite">
        <!-- Sidebar with all bloggers -->
        <div class="flex flex-1 min-w-0 bg-white h-[80lvh] rounded-2xl">
            <ul class="list-none p-2 w-full flex flex-col gap-1">
                <?php foreach ($bloggers as $user): ?>
                <li class="border border-gray rounded-lg">
                    <a href="blog.php?id=<?= $user[
                        "id"
                    ] ?>" class="px-2 py-4 flex justify-start gap-4 items-center">
                    <?php display_profile_image(
                        $user["profile_image"],
                        $user["username"],
                    ); ?>
                    <p class="truncate"><?= htmlspecialchars(
                        $user["title"] ?? $user["username"],
                    ) ?></p>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="w-4xl flex flex-col items-center gap-8 p-8">

            <h1 class="text-2xl font-bold">NEWS</h1>

            <div class="w-full flex flex-col items-center gap-4">

                <!-- Loop through posts and display them -->
                <?php foreach ($posts as $post): ?>
                    <div class="relative w-full flex flex-col bg-white rounded-2xl p-8 gap-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <?php display_profile_image(
                                    $post["profile_image"],
                                    $post["username"],
                                ); ?>
                                <div>
                                    <p class="text-sm text-gray">Posted by <strong><?= htmlspecialchars(
                                        $post["blog_title"],
                                    ) ?></strong></p>
                                    <p class="text-xs text-gray">@<?= htmlspecialchars(
                                        $post["username"],
                                    ) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 text-sm text-gray shrink-0">
                                <?= icon("calendar", "w-4 h-4") ?>
                                <?= date(
                                    "Y-m-d",
                                    strtotime($post["created_at"]),
                                ) ?>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold"><?= htmlspecialchars(
                            $post["title"],
                        ) ?></h2>
                        <p class="truncate-overflow blog-text text-sm"><?= htmlspecialchars(
                            $post["content"],
                        ) ?></p>
                        <a class="absolute inset-0 rounded-2xl" href="<?= BASE ?>/blog.php?id=<?= htmlspecialchars(
    $post["user_id"],
) ?>&post_id=<?= htmlspecialchars($post["id"]) ?>"></a>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
        <div class="flex-1"></div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
