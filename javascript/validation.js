function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  const isHidden = input.type === "password";
  input.type = isHidden ? "text" : "password";
  btn.querySelector("[data-eye='open']").classList.toggle("hidden", isHidden);
  btn.querySelector("[data-eye='closed']").classList.toggle("hidden", !isHidden);
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
