<?php
// Displays user information such as profile picture, name, username, and bio
// Information is passed in the $info_user variable and can be resued across different pages

require_once __DIR__ . "/profileImage.php"; ?>

<div class="bg-white rounded-2xl p-6 flex flex-col gap-4">
    <div class="flex items-center gap-3">
        <?php display_profile_image(
            $info_user["profile_image"],
            $info_user["name"],
        ); ?>
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
