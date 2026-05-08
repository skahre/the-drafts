<div class="bg-white rounded-2xl p-6 flex flex-col gap-4">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-offwhite border border-gray flex items-center justify-center text-lg font-bold">
            <?php echo strtoupper(substr($info_user["name"], 0, 1)); ?>
        </div>
        <div>
            <p class="font-bold"><?php echo htmlspecialchars(
                $info_user["name"],
            ); ?></p>
            <p class="text-xs text-gray"><?php echo htmlspecialchars(
                $info_user["username"],
            ); ?></p>
        </div>
    </div>
    <p class="text-sm text-gray"><?php echo htmlspecialchars(
        $info_user["bio"] ?? "No description yet.",
    ); ?></p>
</div>
