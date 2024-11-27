function switchToDateInput() {
    const dateInputBtn = document.querySelector('.date-input-btn');

    const dateInput = document.createElement('input');
    dateInput.type = 'date';
    dateInput.className = 'date-input';
    dateInput.name = 'pickupDate';
    dateInput.classList.add('input');

    const today = new Date();
    const localDate = today.toISOString().split('T')[0];
    dateInput.min = localDate;

    dateInputBtn.parentNode.replaceChild(dateInput, dateInputBtn);

    dateInput.focus();

    dateInput.addEventListener('blur', () => {
        if (!dateInput.value) {
            revertToButton();
        }
    });

    function changeToLocalDate(date) {
        const localDate = new Date(date.getTime() - date.getTimezoneOffset() * 60000);
        return localDate.toISOString().split('T')[0];
    }

    function revertToButton() {
        const newButton = document.createElement('button');
        newButton.className = 'date-input-btn';
        newButton.classList.add('input');
        newButton.textContent = '01/01/2001'; // Replace with desired default text

        dateInput.parentNode.replaceChild(newButton, dateInput);

        newButton.addEventListener('click', (event) => {
            event.preventDefault();
            switchToDateInput();
        });
    }
}

const dateInputBtns = document.querySelectorAll('.date-input-btn');
dateInputBtns.forEach((dateInputBtn) => {
    dateInputBtn.addEventListener('click', (event) => {
        event.preventDefault();
        switchToDateInput();
    });

    dateInputBtns.forEach((dateInputBtn) => {
        dateInputBtn.addEventListener('load', (event) => {
            event.preventDefault();
            switchToDateInput();
        });
    });
});
