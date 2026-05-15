<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="../css/output.css">
    <title>Admin Dashboard | The Drafts</title>
</head>
<body>

    <?php
    require_once "../components/header.php";
    require_once "../components/icons.php";
    require_once "../db/db.php";
    require_once "../utils/fileValidation.php";
    require_once "../components/profileImage.php";

    $redirectUrl = BASE . "/welcome.php";

    if (!isset($_SESSION["username"], $_SESSION["user_id"])) {
        header("Location: $redirectUrl");
        exit();
    }

    $avatarError = $_SESSION["avatar_error"] ?? "";
    unset($_SESSION["avatar_error"]);

    if ($_POST) {
        if (
            isset($_FILES["profile_image"]) &&
            $_FILES["profile_image"]["error"] === 0
        ) {
            try {
                $filename = upload_image(
                    $_FILES["profile_image"],
                    __DIR__ . "/../uploads/",
                );
                update_profile_image($_SESSION["user_id"], $filename);
            } catch (RuntimeException $e) {
                $_SESSION["avatar_error"] = $e->getMessage();
            }
        }

        $displayName = $_POST["display_name"] ?? "";
        $bio = $_POST["bio"] ?? "";
        update_user_info($_SESSION["user_id"], $displayName, $bio);

        header("Location: dashboard.php");
        exit();
    }

    $currentUser = get_user_by_id($_SESSION["user_id"]);
    $profileImage = $currentUser["profile_image"] ?? null;

    $posts = get_posts_by_user($_SESSION["user_id"]);
    ?>

    <main class="flex flex-row p-4 gap-4 flex-1 bg-offwhite">

        <aside class="flex flex-col gap-4 w-72 shrink-0">

            <?php
            $info_user = [
                "name" => $currentUser["title"] ?? $currentUser["username"],
                "username" => $currentUser["username"],
                "bio" => $currentUser["presentation"] ?? null,
                "id" => $currentUser["id"],
                "profile_image" => $profileImage,
            ];
            require_once "../components/info.php";
            ?>

            <div class="bg-white rounded-2xl p-6 flex flex-col gap-4">
                <h2 class="font-bold">
                    Settings
                </h2>

                <form method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-semibold">Profile picture</label>
                        <div class="flex items-center gap-3">
                            <div id="avatar-preview-wrap" class="w-14 h-14 rounded-full overflow-hidden bg-offwhite border border-gray flex items-center justify-center text-xl font-bold shrink-0">
                                <?php if ($profileImage): ?>
                                    <img src="<?= BASE ?>/uploads/<?= htmlspecialchars(
    $profileImage,
) ?>" alt="Profile" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?= strtoupper(
                                        substr(
                                            $currentUser["title"] ??
                                                $currentUser["username"],
                                            0,
                                            1,
                                        ),
                                    ) ?>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col gap-1.5 flex-1 min-w-0">
                                <label class="flex items-center justify-center gap-1.5 border border-gray rounded-lg px-3 py-1.5 text-sm font-semibold hover:bg-offwhite transition-colors cursor-pointer">
                                    <?= icon("upload", "w-4 h-4") ?>
                                    Choose photo
                                    <input type="file" name="profile_image" accept="image/jpeg,image/png" class="sr-only" id="avatar-input">
                                </label>
                                <p class="text-xs text-gray text-center">JPG or PNG, max 3 MB</p>
                            </div>
                        </div>
                        <?php if ($avatarError): ?>
                            <div class="flex items-center gap-1.5 text-xs text-error">
                                <?= icon("alert-circle", "w-4 h-4 shrink-0") ?>
                                <span><?= htmlspecialchars(
                                    $avatarError,
                                ) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold">Display name</label>
                        <input
                            type="text"
                            name="display_name"
                            value="<?= htmlspecialchars(
                                $currentUser["title"] ?? "",
                            ) ?>"
                            class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary text-sm"
                        >
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-semibold">Description</label>
                        <textarea
                            name="bio"
                            rows="3"
                            class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary text-sm resize-none"
                        ><?= htmlspecialchars(
                            $currentUser["presentation"] ?? "",
                        ) ?></textarea>
                    </div>
                    <button
                        type="submit"
                        class="bg-primary font-semibold py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer text-sm"
                    >
                        Save
                    </button>
                </form>

                <script>
                    const avatarInput = document.getElementById('avatar-input');
                    const avatarPreviewWrap = document.getElementById('avatar-preview-wrap');
                    avatarInput.addEventListener('change', () => {
                        const file = avatarInput.files[0];
                        if (!file) return;
                        const reader = new FileReader();
                        reader.onload = e => {
                            avatarPreviewWrap.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                        };
                        reader.readAsDataURL(file);
                    });
                </script>
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
                    <div class="relative bg-white rounded-2xl p-5 flex items-center justify-between gap-4">
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
                        <div class="relative z-10 flex gap-2 shrink-0">
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
                        <a class="absolute inset-0 rounded-2xl" href="<?= BASE ?>/admin/post.php?id=<?= $post[
    "id"
] ?>"></a>
                    </div>
                    <?php endforeach; ?>

                <div class="flex flex-col bg-white rounded-2xl p-8 text-center text-gray text-sm">
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
