<?php
require_once __DIR__ . "/icons.php";

// Renders a password input field with a toggle button to show/hide the password.
// The function can be reused for both login and registration
function password_input($id, $label, $hint = null, $error_id = null)
{
    ?>
<div class="flex flex-col gap-1">
    <label for="<?php echo $id; ?>" class="text-sm font-semibold"><?php echo $label; ?></label>
    <div class="relative">
        <input
            type="password"
            id="<?php echo $id; ?>"
            name="<?php echo $id; ?>"
            required
            minlength="6"
            class="w-full border border-gray rounded-lg px-3 py-2 pr-10 bg-offwhite focus:outline-none focus:border-primary"
        >
        <button
            type="button"
            onclick="togglePassword('<?php echo $id; ?>', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"
        >
            <?= icon("eye", "w-5 h-5", ["data-eye" => "open"]) ?>
            <?= icon("eye-off", "w-5 h-5 hidden", ["data-eye" => "closed"]) ?>
        </button>
    </div>
    <?php if ($hint): ?>
        <span class="text-xs text-gray"><?php echo $hint; ?></span>
    <?php endif; ?>
    <?php if ($error_id): ?>
        <span class="text-xs text-error" id="<?php echo $error_id; ?>"></span>
    <?php endif; ?>
</div>
<?php
}
