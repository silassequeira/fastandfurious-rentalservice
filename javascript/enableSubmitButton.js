function disableSubmitUntilFilled(form) {
    const submitButton = form.querySelector('input[type="submit"]');
    const inputs = form.querySelectorAll('input:not([type="submit"])[required]');
    
    function checkFields() {
        submitButton.disabled = Array.from(inputs).some(input => !input.value.trim());
    }
    
    inputs.forEach(input => input.addEventListener('input', checkFields));
    
    checkFields();
}


document.querySelectorAll('form').forEach(form => {
    disableSubmitUntilFilled(form);
});