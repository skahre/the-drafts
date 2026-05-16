<?php

// Display profile image if available, otherwise show the first letter of the user's name
function display_profile_image($image, $name)
{
    ?>
<div class="w-12 h-12 rounded-full overflow-hidden bg-offwhite border border-gray flex items-center justify-center text-lg font-bold shrink-0">
    <?php if ($image): ?>
        <img src="<?= BASE ?>/uploads/<?= htmlspecialchars(
            $image,
        ) ?>" alt="Profile" class="w-full h-full object-cover">
    <?php else: ?>
        <?= strtoupper(substr($name, 0, 1)) ?>
    <?php endif; ?>
</div>
<?php
}
