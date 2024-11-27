function setCurrentLocalDate(button) {
    const today = new Date();
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    const formattedDate = today.toLocaleDateString('en-GB', options); // Format: DD/MM/YYYY

    button.innerHTML = formattedDate;
}

function switchToDateInput(clickedButton) {
    const dateInput = document.createElement('input');
    dateInput.type = 'date';
    dateInput.className = 'date-input';
    dateInput.name = clickedButton.name; // Keep the same name
    dateInput.classList.add('input');

    const today = new Date();
    const localDate = today.toISOString().split('T')[0];
    dateInput.min = localDate;

    clickedButton.parentNode.replaceChild(dateInput, clickedButton);

    dateInput.focus();

    dateInput.addEventListener('blur', () => {
        if (!dateInput.value) {
            revertToButton(dateInput);
        }
    });

    function revertToButton(dateInput) {
        const newButton = document.createElement('button');
        newButton.className = 'date-input-btn';
        newButton.classList.add('input');
        newButton.name = dateInput.name; // Keep the same name
        setCurrentLocalDate(newButton); // Set the button text to the current local date

        dateInput.parentNode.replaceChild(newButton, dateInput);

        newButton.addEventListener('click', (event) => {
            event.preventDefault();
            switchToDateInput(newButton);
        });
    }
}

// Initialize all date input buttons
document.querySelectorAll('.date-input-btn').forEach((button) => {
    setCurrentLocalDate(button);
    button.addEventListener('click', (event) => {
        event.preventDefault();
        switchToDateInput(event.target);
    });
});
