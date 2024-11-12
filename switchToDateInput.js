function switchToDateInput() {
    const dateInputBtn = document.querySelector('.date-input-btn'); // Select button by class name

    // Create a new date input element
    const dateInput = document.createElement('input');
    dateInput.type = 'date';
    dateInput.className = 'date-input'; // Assign a class for styling
    dateInput.name = 'pickupDate';
    dateInput.classList.add('input'); // Add button styling class if needed

    // Replace the button with the date input in the same position
    dateInputBtn.parentNode.replaceChild(dateInput, dateInputBtn);

    // Focus on the date input so the user can start entering the date
    dateInput.focus();

    // Add an event listener for when the input loses focus (user clicks away)
    dateInput.addEventListener('blur', () => {
        // Check if the input is still empty
        if (!dateInput.value) {
            revertToButton(); // Revert back to button if no date is selected
        }
    });

    // Function to revert the date input back to the original button
    function revertToButton() {
        const newButton = document.createElement('button');
        newButton.className = 'date-input-btn'; // Add class to the new button
        newButton.classList.add('input'); // Add button styling class
        newButton.textContent = '01/01/2001';

        // Replace the date input with the button
        dateInput.parentNode.replaceChild(newButton, dateInput);

        // Reattach the click event listener to the new button
        newButton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent any default action (like form submission)
            switchToDateInput(); // Call the function to replace the button with the date input
        });
    }
}

// Initial event listener for all buttons with class .date-input-btn
const dateInputBtns = document.querySelectorAll('.date-input-btn'); // Select all buttons by class name
dateInputBtns.forEach((dateInputBtn) => {
    dateInputBtn.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent any default action (like form submission)
        switchToDateInput(); // Call the function to replace the button with the date input
    });
});
