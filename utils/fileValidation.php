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
}

// Validates and moves an uploaded image file to $upload_dir.
// Returns the saved filename on success, throws RuntimeException on failure.
function upload_image(array $file, string $upload_dir): string
{
    if ($file["size"] > 3 * 1024 * 1024) {
        throw new RuntimeException("Image must be under 3 MB.");
    }

    $fileExtension = validate_image_type($file);
    if ($fileExtension === false) {
        throw new RuntimeException("Only JPG and PNG images are allowed.");
    }

    $filename = bin2hex(random_bytes(16)) . "." . $fileExtension;
    if (!move_uploaded_file($file["tmp_name"], $upload_dir . $filename)) {
        throw new RuntimeException("Something went wrong while uploading the file.");
    }

    return $filename;
}