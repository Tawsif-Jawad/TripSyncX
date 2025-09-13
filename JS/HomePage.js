document.addEventListener("DOMContentLoaded", function() {
    const logo = document.querySelector(".logo");
    logo.addEventListener("click", function() {
        window.location.href = "index.html";
    });
});
// Additional client-side validation to complement PHP validation
        document.addEventListener('DOMContentLoaded', function() {
            const departureDate = document.getElementById('departure_date');
            const returnDate = document.getElementById('return_date');
            const today = new Date().toISOString().split('T')[0];
            
            // Set minimum date to today for both fields
            departureDate.min = today;
            returnDate.min = today;
            
            // Update return date minimum when departure date changes
            departureDate.addEventListener('change', function() {
                if (this.value) {
                    returnDate.min = this.value;
                    // Clear return date if it's before new departure date
                    if (returnDate.value && returnDate.value < this.value) {
                        returnDate.value = '';
                    }
                }
            });
            
            // Validate that return date is not before departure date
            returnDate.addEventListener('change', function() {
                if (this.value && departureDate.value && this.value < departureDate.value) {
                    alert('Return date cannot be before departure date.');
                    this.value = '';
                }
            });
        });