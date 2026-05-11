<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>The Drafts</title>
</head>
<body>

    <?php
    require_once "components/header.php";
    require_once "components/icons.php";
    require_once "db/db.php";

    $posts = get_all_posts();
    ?>

    <main class="flex flex-row p-4 gap-4 flex-1 bg-offwhite">
        <div class="flex flex-1 flex-col items-center gap-8 p-8">
            <h1 class="text-2xl font-bold">NEWS</h1>
            <div class="w-full flex flex-col items-center gap-4">
                <?php foreach ($posts as $post): ?>
                    <div class="w-full flex flex-col bg-white rounded-2xl p-8 gap-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-offwhite border border-gray flex items-center justify-center text-lg font-bold shrink-0">
                                    <?= strtoupper(substr($post["blog_title"], 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="text-sm text-gray">Posted by <strong><?= htmlspecialchars($post["blog_title"]) ?></strong></p>
                                    <p class="text-xs text-gray">@<?= htmlspecialchars($post["username"]) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 text-sm text-gray shrink-0">
                                <?= icon("calendar", "w-4 h-4") ?>
                                <?= date("Y-m-d", strtotime($post["created_at"])) ?>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold"><?= htmlspecialchars($post["title"]) ?></h2>
                        <p class="truncate-overflow blog-text text-sm"><?= htmlspecialchars($post["content"]) ?>...</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="flex shrink-0 bg-white w-1/5 h-[80lvh] rounded-2xl">
            <ul class="list-none p-2 w-full flex flex-col gap-1">
                <li class="border border-gray px-2 py-4 rounded-lg flex justify-center"><a href="blog.php">Example Blogger</a></li>
                <li class="border border-gray px-2 py-4 rounded-lg flex justify-center">Sandra Kåhre</li>
                <li class="border border-gray px-2 py-4 rounded-lg flex justify-center">Hell YEZZ</li>
            </ul>
        </div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
