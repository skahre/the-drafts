<?php
require_once "../utils/bases.php";
require_once "../components/icons.php";
require_once "../db/db.php";
require_once "../utils/file-validation.php";

// Redirect to welcome page if not logged in
if (!isset($_SESSION["username"], $_SESSION["user_id"])) {
    header("Location: /welcome.php");
    exit();
}

$redirect_url = BASE . "/admin/dashboard.php";

if ($_GET["id"]) {
    $post_id = $_GET["id"];
    $post = get_post_by_id($post_id);
    if (!$post) {
        header("Location: $redirect_url");
        exit();
    }
    if ($post["user_id"] !== $_SESSION["user_id"]) {
        header("Location: $redirect_url");
        exit();
    }
} else {
    header("Location: $redirect_url");
    exit();
}

$is_editing = $_SESSION["editing"] ?? false;
unset($_SESSION["editing"]);

$current_image = get_image_by_post($post_id)["filename"] ?? null;
$current_title = $post["title"];
$current_content = $post["content"];

// Initialize variables for form data and errors
$image_error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
$saved = $_SESSION["form"] ?? [];
unset($_SESSION["form"]);

if ($_POST) {
    if (isset($_POST["delete_id"])) {
        $_SESSION["delete_id"] = $_POST["delete_id"];

        header("Location: ../utils/delete-post.php");
        exit();
    }

    $title = $_POST["title"] ?? "";
    $image = $_FILES["image-input"] ?? null;
    $content = $_POST["content"] ?? "";

    if ($image && $image["error"] === 0) {
        try {
            $filename = upload_image($image, __DIR__ . "/../uploads/");
            delete_image($current_image, __DIR__ . "/../uploads/");
        } catch (RuntimeException $e) {
            $_SESSION["error"] = $e->getMessage();
            $_SESSION["form"] = ["title" => $title, "content" => $content];
            header("Location: edit-post.php?id=" . $_GET["id"]);
            exit();
        }
    } elseif ($image && $image["error"] !== 4) {
        $upload_errors = [
            0 => "No error, the file uploaded successfully.",
            1 => "Image too big (PHP limit).",
            2 => "Image too big (HTML form limit).",
            3 => "Image only partially uploaded.",
            4 => "No file selected.",
            6 => "Temporary folder missing.",
            7 => "Failed to write to disk.",
            8 => "Uppladdningen stoppad.",
        ];
        $_SESSION["error"] = "Something went wrong while uploading image.";

        error_log(
            $upload_errors[$image["error" ?? 0]] ?? "Unknown upload error.",
        );

        header("Location: edit-post.php?id=" . $_GET["id"]);
        exit();
    }

    if ($title !== "" && $content !== "") {
        $post_id = update_post($_GET["id"], $title, $content);
        if ($post_id instanceof Exception) {
            $_SESSION["error"] = "Something went wrong while saving the post.";
            header("Location: edit-post.php?id=" . $_GET["id"]);
            exit();
        }
        if ($image && $image["error"] === 0) {
            update_post_image($_GET["id"], $filename);
        }
        header("Location: edit-post.php?id=" . $_GET["id"]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="../css/output.css">
    <title>Edit Post | The Drafts</title>
</head>
<body>

    <?php require_once "../components/header.php"; ?>

    <main class="flex flex-1 items-start justify-center p-8 bg-offwhite">
        <div class="w-full max-w-2xl flex flex-col gap-4">

            <div class="w-full flex flex-row justify-between items-center">
                <a 
                href="<?= BASE ?>/admin/dashboard.php" 
                class="flex items-center justify-center gap-1.5 text-sm text-gray hover:text-black transition-colors">
                    <?= icon("arrow-left", "w-4 h-4") ?>
                    Back to dashboard
                </a>
                <form method="POST">
                    <input type="hidden" name="delete_id" value="<?= $post[
                        "id"
                    ] ?>">
                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to delete this post? This cannot be undone.')"
                        class="px-3 py-1.5 rounded-lg bg-error text-white text-sm hover:opacity-90 transition-colors cursor-pointer"
                    >
                        Remove
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl p-8 flex flex-col gap-6">

            <form
                id="edit-form"
                method="POST"
                enctype="multipart/form-data"
                class="flex flex-col gap-4"
            >
                <div id="title-view" class="<?= $is_editing
                    ? "hidden"
                    : "flex" ?> items-center gap-2">
                    <h1 class="text-2xl font-bold"><?= htmlspecialchars(
                        $saved["title"] ?? $current_title,
                    ) ?></h1>
                    <button type="button" id="edit-title-btn" class="p-1 text-gray hover:text-black transition-colors cursor-pointer">
                        <?= icon("pencil", "w-4 h-4") ?>
                    </button>
                </div>

                <div id="title-edit" class="<?= $is_editing
                    ? "flex"
                    : "hidden" ?> flex-col gap-1">
                    <div class="flex flex-row gap-1.5">
                        <input
                            id="title-input"
                            type="text"
                            name="title"
                            required
                            value="<?= htmlspecialchars(
                                $saved["title"] ?? $current_title,
                            ) ?>"
                            class="w-full text-2xl font-bold bg-transparent border-b border-gray focus:outline-none focus:border-primary pb-0.5"
                        >
                        <div class="flex gap-2">
                            <button type="button" id="save-title-btn" class="text-xs font-semibold px-2.5 py-1 rounded-md bg-primary hover:opacity-90 transition-opacity cursor-pointer">Save</button>
                            <button type="button" id="cancel-title-btn" class="text-xs px-2.5 py-1 rounded-md border border-gray hover:bg-offwhite transition-colors cursor-pointer">Cancel</button>
                        </div>
                    </div>
                    <p id="title-error" class="hidden text-xs text-error">Title cannot be empty.</p>
                </div>

                <script src="../javascript/validation.js"></script>
                <script>
                    (function () {
                        const view  = document.getElementById('title-view');
                        const edit  = document.getElementById('title-edit');
                        const input = document.getElementById('title-input');
                        const h1    = view.querySelector('h1');
                        const titleError = document.getElementById('title-error');

                        let originalValue = input.value;

                        function enterEdit() {
                            originalValue = input.value;
                            view.classList.add('hidden');
                            view.classList.remove('flex');
                            edit.classList.remove('hidden');
                            edit.classList.add('flex');
                            input.focus();
                        }

                        function exitEdit(save) {
                            if (save) {
                                h1.textContent = input.value;
                            } else {
                                input.value = originalValue;
                            }
                            titleError.classList.add('hidden');
                            edit.classList.add('hidden');
                            edit.classList.remove('flex');
                            view.classList.remove('hidden');
                            view.classList.add('flex');
                        }

                        document.getElementById('edit-title-btn').addEventListener('click', enterEdit);
                        document.getElementById('save-title-btn').addEventListener('click', () => {
                            if (isEmpty(input)) {
                                titleError.classList.remove('hidden');
                                return;
                            }
                            exitEdit(true);
                        });
                        document.getElementById('cancel-title-btn').addEventListener('click', () => exitEdit(false));
                    })();
                </script>

                <div class="flex flex-col gap-1">
                    <div class="relative rounded-lg overflow-hidden bg-offwhite aspect-video">
                        <img
                            id="image-preview-img"
                            src="<?= $current_image
                                ? htmlspecialchars(
                                    BASE . "/uploads/" . $current_image,
                                )
                                : "" ?>"
                            data-original-src="<?= $current_image
                                ? htmlspecialchars(
                                    BASE . "/uploads/" . $current_image,
                                )
                                : "" ?>"
                            alt="Post image"
                            class="w-full h-full object-cover<?= $current_image
                                ? ""
                                : " hidden" ?>"
                        >
                        <div id="image-placeholder" class="<?= $current_image
                            ? "hidden"
                            : "flex" ?> w-full h-full flex-col items-center justify-center gap-2 text-gray">
                            <?= icon("image", "w-8 h-8") ?>
                            <span class="text-xs">No image</span>
                        </div>
                        <button
                            type="button"
                            id="image-x-btn"
                            class="<?= $current_image
                                ? ""
                                : "hidden" ?> absolute top-2 right-2 p-1.5 bg-white rounded-lg border border-gray hover:bg-offwhite transition-colors cursor-pointer shadow-sm"
                        >
                            <?= icon("x", "w-4 h-4") ?>
                        </button>
                        <label class="absolute bottom-2 right-2 p-2 bg-white rounded-lg border border-gray hover:bg-offwhite transition-colors cursor-pointer shadow-sm">
                            <?= icon("camera", "w-4 h-4") ?>
                            <input
                                id="image-input"
                                type="file"
                                accept="image/*"
                                class="sr-only"
                                name="image-input"
                            >
                        </label>
                    </div>
                    <p id="new-file-info" class="hidden text-xs text-gray"></p>
                    <button
                        type="button"
                        id="cancel-image-btn"
                        class="hidden self-start text-xs text-gray hover:text-black transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <input type="hidden" id="delete-image-flag" name="delete-image" value="">
                    <div id="image-error" class="<?= $image_error
                        ? "flex"
                        : "hidden" ?> items-center gap-1.5 text-xs text-error">
                        <?= icon("alert-circle", "w-4 h-4 shrink-0") ?>
                        <span id="error-text"><?= htmlspecialchars(
                            $image_error,
                        ) ?></span>
                    </div>
                </div>

                <script>
                    (function () {
                        const input       = document.getElementById('image-input');
                        const previewImg  = document.getElementById('image-preview-img');
                        const placeholder = document.getElementById('image-placeholder');
                        const xBtn        = document.getElementById('image-x-btn');
                        const newFileInfo = document.getElementById('new-file-info');
                        const cancelBtn   = document.getElementById('cancel-image-btn');
                        const deleteFlagEl = document.getElementById('delete-image-flag');
                        const errorEl     = document.getElementById('image-error');
                        const errorText   = document.getElementById('error-text');
                        const originalSrc = previewImg.dataset.originalSrc;
                        const maxBytes    = 3 * 1024 * 1024;
                        const allowed     = ['image/jpeg', 'image/png'];

                        let deleted = false;

                        function fmt(bytes) {
                            return bytes >= 1024 * 1024
                                ? (bytes / (1024 * 1024)).toFixed(1) + ' MB'
                                : Math.round(bytes / 1024) + ' KB';
                        }

                        function setError(msg) {
                            errorEl.classList.toggle('hidden', !msg);
                            errorEl.classList.toggle('flex', !!msg);
                            errorText.textContent = msg ?? '';
                        }

                        function showImg(src) {
                            previewImg.src = src;
                            previewImg.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                            placeholder.classList.remove('flex');
                        }

                        function showPlaceholder() {
                            previewImg.classList.add('hidden');
                            placeholder.classList.remove('hidden');
                            placeholder.classList.add('flex');
                        }

                        function syncControls() {
                            const hasPending = !!input.files[0];
                            xBtn.classList.toggle('hidden', !hasPending && (deleted || !originalSrc));
                            cancelBtn.classList.toggle('hidden', !hasPending && !deleted);
                        }

                        input.addEventListener('change', () => {
                            const file = input.files[0];
                            if (!file) return;

                            const err = file.size > maxBytes
                                ? 'Image must be under 3 MB.'
                                : !allowed.includes(file.type)
                                    ? 'Only JPG and PNG images are allowed.'
                                    : null;

                            if (!err) showImg(URL.createObjectURL(file));

                            newFileInfo.textContent = file.name + ' · ' + fmt(file.size);
                            newFileInfo.classList.remove('hidden');
                            deleted = false;
                            deleteFlagEl.value = '';
                            setError(err);
                            syncControls();
                        });

                        xBtn.addEventListener('click', () => {
                            input.value = '';
                            newFileInfo.classList.add('hidden');
                            if (originalSrc) {
                                deleted = true;
                                deleteFlagEl.value = '1';
                            }
                            showPlaceholder();
                            setError(null);
                            syncControls();
                        });

                        cancelBtn.addEventListener('click', () => {
                            input.value = '';
                            newFileInfo.classList.add('hidden');
                            deleted = false;
                            deleteFlagEl.value = '';
                            originalSrc ? showImg(originalSrc) : showPlaceholder();
                            setError(null);
                            syncControls();
                        });

                        document.getElementById('edit-form').addEventListener('submit', e => {
                            if (input.files[0]) {
                                const f = input.files[0];
                                const err = f.size > maxBytes
                                    ? 'Image must be under 3 MB.'
                                    : !allowed.includes(f.type)
                                        ? 'Only JPG and PNG images are allowed.'
                                        : null;
                                if (err) { e.preventDefault(); setError(err); }
                            }
                        });
                    })();
                </script>

                <div id="content-view" class="<?= $is_editing
                    ? "hidden"
                    : "flex" ?> flex-col gap-1">
                    <div class="flex items-start gap-2">
                        <p id="content-display" class="text-sm whitespace-pre-wrap flex-1"><?= htmlspecialchars(
                            $saved["content"] ?? $current_content,
                        ) ?></p>
                        <button type="button" id="edit-content-btn" class="p-1 text-gray hover:text-black transition-colors cursor-pointer shrink-0">
                            <?= icon("pencil", "w-4 h-4") ?>
                        </button>
                    </div>
                </div>

                <div id="content-edit" class="<?= $is_editing
                    ? "flex"
                    : "hidden" ?> flex-col gap-1">
                    <textarea
                        id="content-input"
                        name="content"
                        rows="12"
                        required
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary resize-none"
                    ><?= htmlspecialchars(
                        $saved["content"] ?? $current_content,
                    ) ?></textarea>
                    <div class="flex gap-2">
                        <button type="button" id="save-content-btn" class="self-start text-xs font-semibold px-2.5 py-1 rounded-md bg-primary hover:opacity-90 transition-opacity cursor-pointer">Save</button>
                        <button type="button" id="cancel-content-btn" class="self-start text-xs px-2.5 py-1 rounded-md border border-gray hover:bg-offwhite transition-colors cursor-pointer">Cancel</button>
                    </div>
                    <p id="content-error" class="hidden text-xs text-error">Content cannot be empty.</p>
                </div>

                <script>
                    (function () {
                        const view    = document.getElementById('content-view');
                        const edit    = document.getElementById('content-edit');
                        const input   = document.getElementById('content-input');
                        const display = document.getElementById('content-display');
                        const contentError = document.getElementById('content-error');

                        let originalValue = input.value;

                        function enterEdit() {
                            originalValue = input.value;
                            view.classList.add('hidden');
                            view.classList.remove('flex');
                            edit.classList.remove('hidden');
                            edit.classList.add('flex');
                            input.focus();
                        }

                        function exitEdit(save) {
                            if (save) {
                                display.textContent = input.value;
                            } else {
                                input.value = originalValue;
                            }
                            contentError.classList.add('hidden');
                            edit.classList.add('hidden');
                            edit.classList.remove('flex');
                            view.classList.remove('hidden');
                            view.classList.add('flex');
                        }

                        document.getElementById('edit-content-btn').addEventListener('click', enterEdit);
                        document.getElementById('save-content-btn').addEventListener('click', () => {
                            if (isEmpty(input)) {
                                contentError.classList.remove('hidden');
                                return;
                            }
                            exitEdit(true);
                        });
                        document.getElementById('cancel-content-btn').addEventListener('click', () => exitEdit(false));
                    })();
                </script>

                <script>
                    (function () {
                        let submitting = false;
                        const originalTitle   = document.getElementById('title-input').value;
                        const originalContent = document.getElementById('content-input').value;

                        document.getElementById('edit-form').addEventListener('submit', () => {
                            submitting = true;
                        });

                        window.addEventListener('beforeunload', (e) => {
                            if (submitting) return;
                            const titleChanged   = document.getElementById('title-input').value !== originalTitle;
                            const contentChanged = document.getElementById('content-input').value !== originalContent;
                            if (titleChanged || contentChanged) {
                                e.preventDefault();
                                e.returnValue = '';
                            }
                        });
                    })();
                </script>

                <div class="flex gap-3 justify-end">
                    <a 
                        href="<?= BASE ?>/admin/post.php?id=<?= htmlspecialchars(
    $post_id,
) ?>" 
                        class="px-4 py-2 rounded-lg border border-gray text-sm hover:bg-offwhite transition-colors"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="bg-primary font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer text-sm"
                    >
                        Save changes
                    </button>
                </div>

            </form>

            </div>
            
        </div>
    </main>

    <?php require_once "../components/footer.php"; ?>

</body>
</html>
