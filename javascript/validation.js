function validatePassword(form) {
  const password = form.password.value.trim();
  const confirmPassword = form["password_confirm"].value.trim();

  if (password !== confirmPassword) {
    document.getElementById("confirm-password-error").textContent =
      "Lösenorden matchar inte.";
    return false;
  } else {
    return true;
  }
}
