<?php

// Path to local db_credentials.php
$localPath = __DIR__ . "/db_credentials.php";

// Path to db_credentials.php on the web server
$serverPath = "/var/private/kahsan5/db_credentials.php";

// If the script is running on the web server, use $serverPath
// Otherwise, use $localPath to read local database credentials
if (file_exists($serverPath)) {
    require_once $serverPath;
} elseif (file_exists($localPath)) {
    require_once $localPath;
} else {
    die("Could not find db_credentials.php.");
}

function connect()
{
    static $connection = null;

    // If connection already exists, reuse it
    if ($connection !== null) {
        return $connection;
    }

    // Create a new connection to db
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    // If connection fails, terminate
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    mysqli_set_charset($connection, "utf8mb4");

    return $connection;
}

function get_result($stmt)
{
    $rows = [];

    // Gets the result object from the prepared statement
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        // Loop through all rows
        // Each row is added to the $rows array
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }

    // Returns an array with all rows
    // If no rows are found, an empty array [] is returned
    return $rows;
}

// Adds a new user to the database
function add_user($username, $hashedPassword)
{
    $connection = connect();

    // Using placeholders to protect against SQL injection
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);

    try {
        mysqli_stmt_execute($stmt);
    } catch (mysqli_sql_exception $e) {
        mysqli_stmt_close($stmt);
        return $e;
    }

    // Gets auto-incremented id
    $newId = mysqli_insert_id($connection);

    mysqli_stmt_close($stmt);

    return $newId; // Return the new user's id
}

// Changes a user's username based on their id
function change_username($id, $newUsername)
{
    $connection = connect();

    $sql = "UPDATE users SET username = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "si", $newUsername, $id);
    mysqli_stmt_execute($stmt);

    $affectedRows = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // Returns amount of affected rows
    // 1+ = something was updated
    // 0  = nothing was changed (e.g., wrong id or same value)
    return $affectedRows;
}

// Deletes a user based on their id
function delete_user($id)
{
    $connection = connect();

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $affectedRows = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // Returns amount of affected rows:
    // 1+ = something was updated
    // 0  = nothing was changed (e.g., wrong id or same value)
    return $affectedRows;
}

// Retrieves a user from the database by their username
function get_user_by_name($username)
{
    $connection = connect();

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    // Gets the result as associative array
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $row; // Returns an associative array (or null)
}

// Retrieves a user from the database by their id
function get_user_by_id($id)
{
    $connection = connect();

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // Gets the result as associative array
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $row; // Returns an associative array (or null)
}

// Retrieves all users from the database, sorted by creation date (oldest first)
function get_users()
{
    $connection = connect();

    // ORDER BY created_atsorts users by their creation date, oldest first
    $sql = "SELECT * FROM users ORDER BY created_at";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_execute($stmt);

    $rows = get_result($stmt);

    mysqli_stmt_close($stmt);

    // Returns all users as an array of associative arrays, sorted by creation date (oldest first)
    return $rows;
}

// Adds a new post to the database, linked to a user by their id
function add_post($user_id, $title, $content)
{
    $connection = connect();

    // Using placeholders to protect against SQL injection
    // using user_id as foreign key to link post to its author
    $sql = "INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "iss", $user_id, $title, $content);

    try {
        mysqli_stmt_execute($stmt);
    } catch (mysqli_sql_exception $e) {
        mysqli_stmt_close($stmt);
        return $e;
    }

    $newId = mysqli_insert_id($connection);

    mysqli_stmt_close($stmt);

    // Returns the id of the new post
    return $newId;
}

// Adds a new image to the database, linked to a post by its id
function add_image($post_id, $filename)
{
    $connection = connect();

    // Using placeholders to protect against SQL injection
    // using post_id as foreign key to link image to its post
    $sql = "INSERT INTO images (post_id, filename) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "is", $post_id, $filename);

    try {
        mysqli_stmt_execute($stmt);
    } catch (mysqli_sql_exception $e) {
        mysqli_stmt_close($stmt);
        return $e;
    }

    $newId = mysqli_insert_id($connection);

    mysqli_stmt_close($stmt);

    // Returns the id of the new image
    return $newId;
}

// Retrieves all posts by a specific user, sorted by creation date (newest first)
function get_posts_by_user($user_id)
{
    $connection = connect();

    // Gets all posts by a specific user, sorted by creation date (newest first)
    $sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $rows = get_result($stmt);

    mysqli_stmt_close($stmt);

    return $rows; // Returns an array of associative arrays (or empty array if no posts)
}

// Updates a user's profile image based on their id
function update_profile_image($user_id, $filename)
{
    $connection = connect();

    // Using placeholders to protect against SQL injection
    $sql = "UPDATE users SET profile_image = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "si", $filename, $user_id);
    mysqli_stmt_execute($stmt);

    $affectedRows = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // Returns amount of affected rows:
    // 1+ = something was updated
    // 0  = nothing was changed (e.g., wrong id or same value)
    return $affectedRows;
}

// Updates a user's display name and bio based on their id
function update_user_info($user_id, $display_name, $bio)
{
    $connection = connect();

    // Using placeholders to protect against SQL injection
    $sql = "UPDATE users SET title = ?, presentation = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "ssi", $display_name, $bio, $user_id);
    mysqli_stmt_execute($stmt);

    $affectedRows = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // Returns amount of affected rows:
    // 1+ = something was updated
    // 0  = nothing was changed (e.g., wrong id or same value)
    return $affectedRows;
}

// Retrieves all posts with their authors' usernames and profile images, sorted by creation date (newest first)
function get_all_posts()
{
    $connection = connect();

    // COALESCE(users.title, users.username) AS blog_title returns the user's title if it exists, otherwise their username
    $sql =
        "SELECT posts.*, users.username, users.profile_image, COALESCE(users.title, users.username) AS blog_title FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_execute($stmt);

    $rows = get_result($stmt);

    mysqli_stmt_close($stmt);

    return $rows; // Returns an array of associative arrays (or empty array if no posts)
}

function get_post_by_id($id)
{
    $connection = connect();

    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // Gets the result as associative array
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $row; // Returns an associative array (or null)
}

function get_image_by_post($post_id)
{
    $connection = connect();

    $sql = "SELECT * FROM images WHERE post_id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);

    // Gets the result as associative array
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $row; // Returns an associative array (or null)
}

function update_post($post_id, $title, $content)
{
    $connection = connect();

    // Using placeholders to protect against SQL injection
    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $post_id);
    mysqli_stmt_execute($stmt);

    $affectedRows = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // Returns amount of affected rows:
    // 1+ = something was updated
    // 0  = nothing was changed (e.g., wrong id or same value)
    return $affectedRows;
}

function update_post_image($post_id, $filename)
{
    $connection = connect();

    // Using placeholders to protect against SQL injection
    $sql = "UPDATE images SET filename = ? WHERE post_id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "si", $filename, $post_id);
    mysqli_stmt_execute($stmt);

    $affectedRows = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    // Returns amount of affected rows:
    // 1+ = something was updated
    // 0  = nothing was changed (e.g., wrong id or same value)
    return $affectedRows;
}
