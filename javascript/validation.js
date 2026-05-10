function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  const isHidden = input.type === "password";
  input.type = isHidden ? "text" : "password";
  btn.querySelector("[data-eye='open']").classList.toggle("hidden", isHidden);
  btn
    .querySelector("[data-eye='closed']")
    .classList.toggle("hidden", !isHidden);
}

function validatePassword(form) {
  const password = form.password.value.trim();
  const confirmPassword = form["password_confirm"].value.trim();

  if (password !== confirmPassword) {
    document.getElementById("confirm-password-error").textContent =
      "Passwords do not match";
    return false;
  } else {
    return true;
  }
}

function validateFile(file) {
  const maxBytes = 3 * 1024 * 1024;
  const allowedTypes = ["image/jpeg", "image/png"];
  if (!file) return null;
  if (file.size > maxBytes) return "Image must be under 3 MB.";
  if (!allowedTypes.includes(file.type))
    return "Only JPG and PNG images are allowed.";
  return null;
}
