const inputs = document.querySelectorAll(".input-field");
const continueButton = document.querySelector(".continue-button-inactive");

function checkInputs() {
    let hasText = Array.from(inputs).some(input => input.value.trim() !== "");
    continueButton.classList.toggle("continue-button-active", hasText);
    continueButton.classList.toggle("continue-button-inactive", !hasText);
}

inputs.forEach(input => input.addEventListener("input", checkInputs));


