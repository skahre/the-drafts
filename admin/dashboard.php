<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="../css/output.css">
    <title>Admin Dashboard | The Drafts</title>
</head>
<body>

    <?php
    require_once "../components/header.php";
    require_once "../db/db.php";

    if (!isset($_SESSION["username"])) {
        header("Location: /welcome.php");
        exit();
    }

    $posts = get_posts_by_user($_SESSION["user_id"]);
    ?>

    <main class="flex flex-row p-4 gap-4 flex-1 bg-offwhite">

        <aside class="flex flex-col gap-4 w-72 shrink-0">

            <?php
            $info_user = [
                "name" => $_SESSION["name"] ?? $_SESSION["username"],
                "username" => $_SESSION["username"],
                "bio" => null,
                "id" => $_SESSION["user_id"],
            ];
            require_once "../components/info.php";
            ?>

            <div class="bg-white rounded-2xl p-6 flex flex-col gap-4">
                <h2 class="font-bold">
                    Settings
                </h2>
                <form method="POST" class="flex flex-col gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold">
                            Display name
                        </label>
                        <input
                            type="text"
                            name="display_name"
                            class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary text-sm"
                        >
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold">
                            Description
                        </label>
                        <textarea
                            name="bio"
                            rows="3"
                            class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary text-sm resize-none"
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        class="bg-primary font-semibold py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer text-sm"
                    >
                        Save
                    </button>
                </form>
            </div>

        </aside>

        <div class="flex flex-1 flex-col gap-4">

            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold">
                    My posts
                </h1>
                <a 
                href="<?= BASE ?>/admin/new-post.php" 
                class="bg-primary font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer text-sm"
                >
                    + New post
                </a>
            </div>

            <div class="flex flex-col gap-3">

                <?php foreach ($posts as $post): ?>
                    <div class="bg-white rounded-2xl p-5 flex items-center justify-between gap-4">
                        <div class="flex flex-col gap-1 flex-1 min-w-0">
                            <p class="font-semibold truncate"><?= htmlspecialchars(
                                $post["title"],
                            ) ?></p>
                            <p class="text-sm truncate-overflow blog-text"><?= htmlspecialchars(
                                $post["content"],
                            ) ?>...</p>
                            <p class="text-xs text-gray"><?= $post[
                                "created_at"
                            ] ?></p>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a 
                            href="<?= BASE ?>/admin/edit-post.php?id=<?= $post[
    "id"
] ?>" 
                            class="px-3 py-1.5 rounded-lg border border-gray text-sm hover:bg-offwhite transition-colors"
                            >
                                Edit
                            </a>
                            <form method="POST">
                                <input type="hidden" name="delete_id" value="<?= $post[
                                    "id"
                                ] ?>">
                                <button 
                                    type="submit" 
                                    class="px-3 py-1.5 rounded-lg border border-gray text-red-600 text-sm hover:bg-red-50 transition-colors cursor-pointer"
                                >
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>

                <div class="bg-white rounded-2xl p-8 text-center text-gray text-sm">
                    No more posts
                    <a 
                        href="<?= BASE ?>/admin/new-post.php" 
                        class="font-semibold underline text-black"
                    >
                        Create a new post
                    </a>
                </div>

            </div>

        </div>

    </main>

    <?php require_once "../components/footer.php"; ?>

</body>
</html>
