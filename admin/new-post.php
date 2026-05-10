<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="../css/output.css">
    <title>New Post | The Drafts</title>
</head>
<body>

    <?php
    require_once "../components/header.php";
    require_once "../components/icons.php";
    require_once "../db/db.php";
    require_once "../utils/fileValidation.php";

    if (!isset($_SESSION["username"])) {
        header("Location: /welcome.php");
        exit();
    }

    $imageError = $_SESSION["error"] ?? "";
    unset($_SESSION["error"]);

    $saved = $_SESSION["form"] ?? [];
    unset($_SESSION["form"]);

    if ($_POST) {
        $title = $_POST["title"] ?? "";
        $image = $_FILES["image-input"] ?? null;
        $content = $_POST["content"] ?? "";

        if ($image && $image["error"] === 0) {
            $maxBytes = 3 * 1024 * 1024;
            if ($image["size"] > $maxBytes) {
                $_SESSION["error"] = "Image must be under 3 MB.";
                $_SESSION["form"] = ["title" => $title, "content" => $content];
                header("Location: new-post.php");
                exit();
            } else {
                $fileExtension = validate_image_type($image);
                if ($fileExtension === false) {
                    $_SESSION["error"] = "Only JPG and PNG images are allowed.";

                    $_SESSION["form"] = [
                        "title" => $title,
                        "content" => $content,
                    ];

                    header("Location: new-post.php");

                    exit();
                }
            }

            $tmp_file = $image["tmp_name"];
            $upload_dir = __DIR__ . "/../uploads/";
            $filename = bin2hex(random_bytes(16)) . "." . $fileExtension;
            if (!move_uploaded_file($tmp_file, $upload_dir . $filename)) {
                $_SESSION["error"] =
                    "Something went wrong while uploading the file.";
                header("Location: new-post.php");

                exit();
            }
        }
        if ($title !== "" && $content !== "") {
            $postId = add_post($_SESSION["user_id"], $title, $content);
            if ($postId instanceof Exception) {
                $_SESSION["error"] =
                    "Something went wrong while saving the post.";
                header("Location: new-post.php");
                exit();
            }
            if ($image && $image["error"] === 0) {
                add_image($postId, $filename);
            }
            header("Location: dashboard.php");
            exit();
        } else {
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

            header("Location: new-post.php");
            exit();
        }
    }
    ?>

    <main class="flex flex-1 items-start justify-center p-8 bg-offwhite">
        <div class="bg-white rounded-2xl p-8 w-full max-w-2xl flex flex-col gap-6">

            <h1 class="text-2xl font-bold">New post</h1>

            <form 
                method="POST" 
                enctype="multipart/form-data" 
                class="flex flex-col gap-4"
            >

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold">
                        Title<span class="text-error">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        required
                        value="<?= htmlspecialchars($saved["title"] ?? "") ?>"
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary"
                    >
                </div>

                <div class="flex flex-col gap-1">
                    <span class="text-sm font-semibold">
                        Image
                    </span>
                    <div
                        id="drop-zone"
                        class="flex flex-col items-center gap-3 border-2 border-dashed border-gray rounded-lg px-4 py-6 bg-offwhite transition-colors text-center"
                    >
                        <?= icon("upload", "w-6 h-6 text-black") ?>
                        <div>
                            <p class="text-sm text-black">
                                Choose file or drag & drop it here
                            </p>
                            <p class="text-xs text-gray">
                                JPG and PNG formats up to 3 MB
                            </p>
                        </div>
                        <label class="shrink-0 flex items-center gap-2 bg-primary font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer text-sm">
                            Browse files
                            <input
                                id="image-input"
                                type="file"
                                accept="image/*"
                                class="sr-only"
                                name="image-input"
                            >
                        </label>
                    </div>
                    <div id="file-preview" class="hidden items-center gap-3 border rounded-lg px-4 py-3 bg-white">
                        <?= icon("image", "w-8 h-8 text-gray shrink-0", [
                            "stroke-width" => "1.5",
                        ]) ?>
                        <div class="flex-1 min-w-0">
                            <p id="preview-name" class="text-sm font-semibold truncate"></p>
                            <p id="preview-size" class="text-xs text-gray"></p>
                        </div>
                        <button
                            type="button"
                            id="remove-file"
                            class="shrink-0 p-2 rounded-lg border border-gray hover:bg-offwhite transition-colors cursor-pointer"
                        >
                            <?= icon("trash", "w-4 h-4 text-gray") ?>
                        </button>
                    </div>
                    <div id="image-error" class="<?= $imageError
                        ? "flex"
                        : "hidden" ?> items-center gap-1.5 text-xs text-error">
                        <?= icon("alert-circle", "w-4 h-4 shrink-0") ?>
                        <span id="error-text"><?= htmlspecialchars(
                            $imageError,
                        ) ?></span>
                    </div>
                </div>

                <script src="../javascript/validation.js"></script>
                <script>
                    const zone = document.getElementById('drop-zone');
                    const input = document.getElementById('image-input');
                    const filePreview = document.getElementById('file-preview');
                    const errorEl = document.getElementById('image-error');
                    const errorText = document.getElementById('error-text');

                    function formatSize(bytes) {
                        if (bytes >= 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
                        return Math.round(bytes / 1024) + ' KB';
                    }

                    function showError(msg) {
                        errorText.textContent = msg;
                        errorEl.classList.remove('hidden');
                        errorEl.classList.add('flex');
                    }

                    function hideError() {
                        errorEl.classList.add('hidden');
                        errorEl.classList.remove('flex');
                    }

                    function setFile(file) {
                        if (!file) {
                            filePreview.classList.add('hidden');
                            filePreview.classList.remove('flex');
                            hideError();
                            return;
                        }
                        document.getElementById('preview-name').textContent = file.name;
                        document.getElementById('preview-size').textContent = formatSize(file.size);
                        filePreview.classList.remove('hidden');
                        filePreview.classList.add('flex');
                        const error = validateFile(file);
                        if (error) {
                            filePreview.classList.add('border-error');
                            filePreview.classList.remove('border-gray');
                            showError(error);
                        } else {
                            filePreview.classList.remove('border-error');
                            filePreview.classList.add('border-gray');
                            hideError();
                        }
                    }

                    zone.addEventListener('dragover', e => {
                        e.preventDefault();
                        zone.classList.add('border-primary', 'bg-white');
                    });
                    zone.addEventListener('dragleave', () => {
                        zone.classList.remove('border-primary', 'bg-white');
                    });
                    zone.addEventListener('drop', e => {
                        e.preventDefault();
                        zone.classList.remove('border-primary', 'bg-white');
                        const file = e.dataTransfer.files[0];
                        if (!file) return;
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        input.files = dt.files;
                        setFile(file);
                    });

                    input.addEventListener('change', () => setFile(input.files[0]));

                    document.getElementById('remove-file').addEventListener('click', () => {
                        input.value = '';
                        setFile(null);
                    });

                    document.querySelector('form').addEventListener('submit', e => {
                        const error = validateFile(input.files[0]);
                        if (error) {
                            e.preventDefault();
                            setFile(input.files[0]);
                        }
                    });
                </script>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold">Content<span class="text-error">*</span></label>
                    <textarea
                        name="content"
                        rows="12"
                        required
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary resize-none"
                    ><?= htmlspecialchars($saved["content"] ?? "") ?></textarea>
                </div>

                <div class="flex gap-3 justify-end">
                    <a 
                        href="<?= BASE ?>/admin/dashboard.php" 
                        class="px-4 py-2 rounded-lg border border-gray text-sm hover:bg-offwhite transition-colors"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="bg-primary font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer text-sm"
                    >
                        Publish
                    </button>
                </div>

            </form>

        </div>
    </main>

    <?php require_once "../components/footer.php"; ?>

</body>
</html>
