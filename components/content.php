<article class="bg-white rounded-2xl p-8 flex flex-col gap-6">
    <h1 class="text-2xl font-bold"><?= htmlspecialchars(
        $post_info["title"],
    ) ?></h1>
    <p class="text-sm text-gray"><?= date(
        "Y-m-d",
        strtotime($post_info["created_at"]),
    ) ?></p>
    <?php if ($post_info["image"]): ?>
        <div class="relative rounded-lg overflow-hidden bg-offwhite aspect-video">
            <img
                src="<?= BASE .
                    "/uploads/" .
                    htmlspecialchars($post_info["image"]["filename"]) ?>"
                alt="Post image"
                class="w-full h-full object-cover"
            >
        </div>
    <?php endif; ?>
    <p class="text-sm whitespace-pre-wrap"><?= htmlspecialchars(
        $post_info["content"],
    ) ?></p>
</article>
