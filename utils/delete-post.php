<?php
require_once "bases.php";
require_once "../db/db.php";

if (
    get_post_by_id($_SESSION["delete_id"])["user_id"] === $_SESSION["user_id"]
) {
    $image = get_image_by_post($_SESSION["delete_id"]);
    delete_post($_SESSION["delete_id"]);
    if ($image && $image["filename"]) {
        $filepath = __DIR__ . "/../uploads/" . $image["filename"];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}

unset($_SESSION["delete_id"]);

header("Location: ../admin/dashboard.php");
exit();
?>
