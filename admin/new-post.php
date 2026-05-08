<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="../css/output.css">
    <title>New Post | The Drafts</title>
</head>
<body>

    <?php require_once "../components/header.php"; ?>

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
                        Title
                    </label>
                    <input
                        type="text"
                        name="title"
                        required
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
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            class="w-6 h-6 text-gray" 
                            viewBox="0 0 24 24" fill="none" 
                            stroke="currentColor" 
                            stroke-width="2" 
                            stroke-linecap="round" 
                            stroke-linejoin="round"
                        >
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <p class="text-sm text-gray">
                            Drag and drop an image here, or
                        </p>
                        <label class="shrink-0 flex items-center gap-2 bg-primary font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity cursor-pointer text-sm">
                            Choose file
                            <input
                                id="image-input"
                                type="file"
                                name="image"
                                accept="image/*"
                                class="sr-only"
                                onchange="setFile(this.files[0])"
                            >
                        </label>
                        <span id="file-name" class="text-sm text-gray truncate">
                            No file chosen
                        </span>
                    </div>
                    <span class="text-xs text-gray">
                        Optional
                    </span>
                </div>
                <script>
                    const zone = document.getElementById('drop-zone');
                    function setFile(file) {
                        document.getElementById('file-name').textContent = file ? file.name : 'No file chosen';
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
                        const input = document.getElementById('image-input');
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        input.files = dt.files;
                        setFile(file);
                    });
                </script>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold">Content</label>
                    <textarea
                        name="content"
                        rows="12"
                        required
                        class="border border-gray rounded-lg px-3 py-2 bg-offwhite focus:outline-none focus:border-primary resize-none"
                    ></textarea>
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
