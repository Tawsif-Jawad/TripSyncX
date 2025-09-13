document.addEventListener("DOMContentLoaded", function() {
    const logo = document.querySelector(".logo");
    logo.addEventListener("click", function() {
        window.location.href = "index.html";
    });
});
        document.addEventListener('DOMContentLoaded', function() {
            const departureDate = document.getElementById('departure_date');
            const returnDate = document.getElementById('return_date');
            const today = new Date().toISOString().split('T')[0];
            
            departureDate.min = today;
            returnDate.min = today;
            
            departureDate.addEventListener('change', function() {
                if (this.value) {
                    returnDate.min = this.value;
                    if (returnDate.value && returnDate.value < this.value) {
                        returnDate.value = '';
                    }
                }
            });
            
            returnDate.addEventListener('change', function() {
                if (this.value && departureDate.value && this.value < departureDate.value) {
                    alert('Return date cannot be before departure date.');
                    this.value = '';
                }
            });
        });