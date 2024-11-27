// Utility function to format date to YYYY-MM-DD
const formatDate = (date) => date.toISOString().split('T')[0];

// Create a clean date (remove time component)
const cleanDate = (date) => {
    const cleaned = new Date(date);
    cleaned.setHours(0, 0, 0, 0);
    return cleaned;
};

// Configuration for date range constraints
const createDateRangeConfig = () => {
    const today = cleanDate(new Date());
    const maxDate = new Date(today);
    maxDate.setMonth(today.getMonth() + 3);
    return {
        minDate: today,
        maxDate: maxDate
    };
};

// Validate and adjust date input
const validateDateInput = (input, config) => {
    const inputDate = cleanDate(new Date(input.value));
    const { minDate, maxDate } = config;

    if (inputDate < minDate || inputDate > maxDate) {
        alert("Selected rental date is out of range. Please select a date within the next 3 months.");
        input.value = "";
    }
};

// Configure date inputs with constraints and validation
const configureDateInputs = () => {
    const startDateInput = document.getElementById('datainicio');
    const endDateInput = document.getElementById('datafim');
    const rentalDateInputs = document.querySelectorAll('input[type="date"]');
    
    const dateRangeConfig = createDateRangeConfig();

    // Set default dates
    const setDefaultDates = () => {
        const today = new Date();
        startDateInput.value = formatDate(today);
        
        const sevenDaysLater = new Date(today);
        sevenDaysLater.setDate(today.getDate() + 7);
        endDateInput.value = formatDate(sevenDaysLater);
    };

    // Update date constraints based on start date
    const updateDateConstraints = () => {
        const startDate = cleanDate(new Date(startDateInput.value));
        
        // Ensure end date is not before start date
        endDateInput.min = formatDate(startDate);

        // Set max end date to 3 months after start date
        const maxEndDate = new Date(startDate);
        maxEndDate.setMonth(startDate.getMonth() + 3);
        endDateInput.max = formatDate(maxEndDate);
    };

    // Validate and adjust end date
    const validateEndDate = () => {
        const startDate = cleanDate(new Date(startDateInput.value));
        const endDate = cleanDate(new Date(endDateInput.value));

        if (endDate < startDate) {
            alert('Return date must be after the start date');
            const defaultEndDate = new Date(startDate);
            defaultEndDate.setDate(startDate.getDate() + 7);
            endDateInput.value = formatDate(defaultEndDate);
            return;
        }

        const maxDate = new Date(startDate);
        maxDate.setMonth(startDate.getMonth() + 3);

        if (endDate > maxDate) {
            alert('Return date cannot exceed 3 months from the start date');
            endDateInput.value = formatDate(maxDate);
        }
    };

    // Setup constraints for all rental date inputs
    const setupRentalDateConstraints = () => {
        rentalDateInputs.forEach(input => {
            input.min = formatDate(dateRangeConfig.minDate);
            input.max = formatDate(dateRangeConfig.maxDate);
            
            input.addEventListener('input', () => validateDateInput(input, dateRangeConfig));
        });
    };

    // Event listeners for dynamic updates
    startDateInput.addEventListener('change', () => {
        const startDate = cleanDate(new Date(startDateInput.value));
        const currentEndDate = cleanDate(new Date(endDateInput.value));

        if (currentEndDate < startDate) {
            const newEndDate = new Date(startDate);
            newEndDate.setDate(startDate.getDate() + 7);
            endDateInput.value = formatDate(newEndDate);
        }
        updateDateConstraints();
    });

    endDateInput.addEventListener('change', validateEndDate);

    // Initial setup
    setDefaultDates();
    updateDateConstraints();
    setupRentalDateConstraints();
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', configureDateInputs);