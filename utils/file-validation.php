<?php

// Validates the type of an uploaded image file
function validate_image_type($file)
{
    // Getting the MIME type of the file rather than trusting the file extension
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file["tmp_name"]);

    // We only allow JPEG and PNG images
    $allowed_types = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
    ];

    if (!array_key_exists($mime, $allowed_types)) {
        return false;
    }

    // Returning the correct, validated file extension
    return $allowed_types[$mime];
}

// Validates uploaded file server-side and uploads to correct directory
function upload_image($file, $upload_dir)
{
    // Check file size (max 3 MB)
    if ($file["size"] > 3 * 1024 * 1024) {
        throw new RuntimeException("Image must be under 3 MB.");
    }

    // Uses validate_image_type to check the MIME type and get the correct file extension
    $file_extension = validate_image_type($file);
    if ($file_extension === false) {
        throw new RuntimeException("Only JPG and PNG images are allowed.");
    }

    // Generate a unique filename different from the original
    $filename = bin2hex(random_bytes(16)) . "." . $file_extension;
    if (!move_uploaded_file($file["tmp_name"], $upload_dir . $filename)) {
        throw new RuntimeException(
            "Something went wrong while uploading the file.",
        );
    }

    // Return the new filename for database storage
    return $filename;
}

function delete_image($filename, $upload_dir)
{
    $file_path = $upload_dir . $filename;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}
