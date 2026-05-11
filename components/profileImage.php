<div class="w-12 h-12 rounded-full overflow-hidden bg-offwhite border border-gray flex items-center justify-center text-lg font-bold shrink-0">
    <?php if (!empty($post["profile_image"])): ?>
        <img src="uploads/<?= htmlspecialchars(
            $post["profile_image"],
        ) ?>" alt="Profile" class="w-full h-full object-cover">
    <?php else: ?>
        <?= strtoupper(substr($post["blog_title"], 0, 1)) ?>
    <?php endif; ?>
</div>