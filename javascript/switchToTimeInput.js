function switchToTimeInput(button) {
    // Create a new time input element
    const timeInput = document.createElement('input');
    timeInput.type = 'time';
    timeInput.className = 'time-input'; // Assign a class for styling
    timeInput.name = button.name; // Use the same name attribute as the button
    timeInput.classList.add('input'); // Add input styling class if needed

    // Replace the button with the time input in the same position
    button.parentNode.replaceChild(timeInput, button);

    // Focus on the time input so the user can start entering the time
    timeInput.focus();

    // Add an event listener for when the input loses focus (user clicks away)
    timeInput.addEventListener('blur', () => {
        // Check if the input is still empty
        if (!timeInput.value) {
            revertToButton(timeInput); // Revert back to button if no time is selected
        }
    });

    // Function to revert the time input back to the original button
    function revertToButton(timeInput) {
        const newButton = document.createElement('button'); // Create a button, not an input
        newButton.className = 'time-input-btn'; // Add class to the new button
        newButton.classList.add('button'); // Add button styling class
        newButton.textContent = 'Select Time'; // Text for the button

        // Replace the time input with the button
        timeInput.parentNode.replaceChild(newButton, timeInput);

        // Reattach the click event listener to the new button
        newButton.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent any default action (like form submission)
            switchToTimeInput(newButton); // Call the function to replace the button with the time input
        });
    }
}

// Initial event listener for all buttons with class .time-input-btn
const timeInputBtns = document.querySelectorAll('.time-input-btn'); // Select all buttons by class name
timeInputBtns.forEach((timeInputBtn) => {
    timeInputBtn.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent any default action (like form submission)
        switchToTimeInput(timeInputBtn); // Call the function to replace the button with the time input
    });
});
