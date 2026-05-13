// Toggles visability of password input and updates the eye icon accordingly
function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  const isHidden = input.type === "password";
  input.type = isHidden ? "text" : "password";
  btn.querySelector("[data-eye='open']").classList.toggle("hidden", isHidden);
  btn
    .querySelector("[data-eye='closed']")
    .classList.toggle("hidden", !isHidden);
}
