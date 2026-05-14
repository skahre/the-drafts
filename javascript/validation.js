function isEmpty(input) {
  return input.value.trim() === "";
}

// Client-side validation for password confirmation
function validatePassword(form, errorId) {
  const password = form.password.value.trim();
  const confirmPassword = form["password_confirm"].value.trim();

  if (password !== confirmPassword) {
    document.getElementById(errorId).textContent = "Passwords do not match";
    return false;
  } else {
    return true;
  }
}

// Client-side validation for image upload
function validateFile(file) {
  const maxBytes = 3 * 1024 * 1024;
  const allowedTypes = ["image/jpeg", "image/png"];

  // Returns null if no file is uploaded (optional upload)
  if (!file) return null;

  // Checks file size and type, and returns potential error messages
  if (file.size > maxBytes) return "Image must be under 3 MB.";
  if (!allowedTypes.includes(file.type))
    return "Only JPG and PNG images are allowed.";

  return null;
}
