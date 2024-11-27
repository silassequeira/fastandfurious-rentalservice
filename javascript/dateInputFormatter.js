// Function to configure date inputs with default values
function configureDateInputs() {
    // Get date input elements
    const startDateInput = document.getElementById('datainicio');
    const endDateInput = document.getElementById('datafim');

    // Function to get today's date in YYYY-MM-DD format
    function getTodayDate() {
        const today = new Date();
        return today.toISOString().split('T')[0];
    }

    // Function to get date 7 days from now in YYYY-MM-DD format
    function getSevenDaysLater() {
        const today = new Date();
        const sevenDaysLater = new Date(today);
        sevenDaysLater.setDate(today.getDate() + 7);
        return sevenDaysLater.toISOString().split('T')[0];
    }

    // Set initial default values
    function setDefaultDates() {
        // Set start date to today
        startDateInput.value = getTodayDate();

        // Set end date to 7 days later
        endDateInput.value = getSevenDaysLater();

        // Update constraints based on default dates
        updateDateConstraints();
    }

    // Function to validate and constrain date inputs
    function updateDateConstraints() {
        // Ensure end date is not before start date
        endDateInput.min = startDateInput.value;

        // Calculate max date (3 months after start date)
        if (startDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const maxDate = new Date(startDate);
            maxDate.setMonth(startDate.getMonth() + 3);

            // Format max date to YYYY-MM-DD for input constraint
            const maxDateString = maxDate.toISOString().split('T')[0];
            endDateInput.max = maxDateString;
        }
    }

    // Add event listeners for dynamic updates
    startDateInput.addEventListener('change', () => {
        // Reset end date if it becomes invalid
        if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
            // Set end date to 7 days after new start date
            const newStartDate = new Date(startDateInput.value);
            const newEndDate = new Date(newStartDate);
            newEndDate.setDate(newStartDate.getDate() + 7);
            endDateInput.value = newEndDate.toISOString().split('T')[0];
        }
        updateDateConstraints();
    });

    // Additional validation on end date change
    endDateInput.addEventListener('change', () => {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        // Validate end date against start date
        if (endDate < startDate) {
            alert('Return date must be after the start date');
            // Reset to 7 days after start date
            const defaultEndDate = new Date(startDate);
            defaultEndDate.setDate(startDate.getDate() + 7);
            endDateInput.value = defaultEndDate.toISOString().split('T')[0];
            return;
        }

        // Validate end date against 3-month limit
        const maxDate = new Date(startDate);
        maxDate.setMonth(startDate.getMonth() + 3);

        if (endDate > maxDate) {
            alert('Return date cannot exceed 3 months from the start date');
            // Reset to max allowed date
            endDateInput.value = maxDate.toISOString().split('T')[0];
        }
    });

    // Initial setup with default dates
    setDefaultDates();
}

// Call the configuration when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', configureDateInputs);

// Call the configuration when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', configureDateInputs);
const dateInput = document.getElementById('date-input');
dateInput.addEventListener('click', () => {
    dateInput.showPicker && dateInput.showPicker();
});