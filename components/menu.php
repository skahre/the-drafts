<?php
$posts = get_posts_by_user($blogger_id); ?>
<nav class="bg-white rounded-2xl p-4 flex flex-col gap-2">
    <h2 class="font-bold text-center">Posts</h2>
    <ul class="flex flex-col gap-1 list-none">
        <?php foreach ($posts as $post_item): ?>
            <li>
                <a href="<?= BASE ?>/blog.php?id=<?= htmlspecialchars(
    $blogger_id,
) ?>&post_id=<?= htmlspecialchars(
    $post_item["id"],
) ?>" class="flex justify-between items-center gap-2 border border-gray rounded-lg px-3 py-3 hover:bg-offwhite transition-colors">
                    <span class="truncate"><?= htmlspecialchars(
                        $post_item["title"],
                    ) ?></span>
                    <span class="text-xs text-gray shrink-0"><?= date(
                        "Y-m-d",
                        strtotime($post_item["created_at"]),
                    ) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
