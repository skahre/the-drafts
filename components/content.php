<!-- PLACEHOLDER: Get post content from database -->
<article class="bg-white rounded-2xl p-8 flex flex-col gap-6">
    <h1 class="text-2xl font-bold"><?= htmlspecialchars($post["title"]) ?></h1>
    <p class="text-sm text-gray">2026-05-05</p>
    <div class="relative rounded-lg overflow-hidden bg-offwhite aspect-video">
        <?php if ($image): ?>
            <img
                src="<?= BASE .
                    "/uploads/" .
                    htmlspecialchars($image["filename"]) ?>"
                alt="Post image"
                class="w-full h-full object-cover"
            >
        <?php endif; ?>
    </div>
    <p class="text-sm whitespace-pre-wrap"><?= htmlspecialchars(
        $post["content"],
    ) ?></p>
</article>
