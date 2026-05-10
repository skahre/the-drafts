<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>The Drafts</title>
</head>
<body>

    <?php
    require_once "components/header.php";
    require_once "db/db.php";

    $posts = get_all_posts();
    ?>

    <main class="flex flex-row p-4 gap-4 flex-1 bg-offwhite">
        <div class="flex flex-1 flex-col items-center gap-8 p-8">
            <h1 class="text-2xl font-bold">NEWS</h1>
            <div class="w-full flex flex-col items-center gap-4">
                <?php foreach ($posts as $post): ?>
                    <div class="w-full flex flex-col bg-white rounded-2xl p-8 gap-4">
                        <h2 class="text-xl font-bold"><?= htmlspecialchars(
                            $post["title"],
                        ) ?></h2>
                        <p class="text-sm text-gray"><?= date(
                            "Y-m-d",
                            strtotime($post["created_at"]),
                        ) ?>
                        </p>
                        <p class="truncate-overflow blog-text text-sm"><?= htmlspecialchars(
                            $post["content"],
                        ) ?>...</p>
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
