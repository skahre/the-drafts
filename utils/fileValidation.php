<?php
function validate_image_type($file)
{
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file["tmp_name"]);

    $allowed_types = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
    ];

    if (!array_key_exists($mime, $allowed_types)) {
        return false;
    }

    return $allowed_types[$mime];
} ?> 