<div class="bg-white rounded-2xl p-6 flex flex-col gap-4">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full overflow-hidden bg-offwhite border border-gray flex items-center justify-center text-lg font-bold shrink-0">
            <?php if (!empty($info_user["profile_image"])): ?>
                <img src="<?= BASE ?>/uploads/<?= htmlspecialchars(
    $info_user["profile_image"],
) ?>" alt="Profile" class="w-full h-full object-cover">
            <?php else: ?>
                <?php echo strtoupper(substr($info_user["name"], 0, 1)); ?>
            <?php endif; ?>
        </div>
        <div>
            <p class="font-bold"><?php echo htmlspecialchars(
                $info_user["name"],
            ); ?></p>
            <p class="text-xs text-gray">@<?php echo htmlspecialchars(
                $info_user["username"],
            ); ?></p>
        </div>
    </div>
    <p class="text-sm text-gray"><?php echo htmlspecialchars(
        $info_user["bio"] ?? "No description yet.",
    ); ?></p>
</div>
