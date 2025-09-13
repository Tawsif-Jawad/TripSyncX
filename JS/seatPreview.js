// On click, select seat and change color
document.addEventListener('DOMContentLoaded', function() {
    // Use event delegation on document body to handle dynamically loaded content
    document.body.addEventListener('click', function(e) {
        let seatDiv = null;
        let targetElement = e.target;
        
        // If clicking on the seat div itself
        if (targetElement.classList.contains('seat')) {
            seatDiv = targetElement;
        }
        // If clicking on the td containing the seat
        else if (targetElement.tagName === 'TD' && targetElement.querySelector('.seat')) {
            seatDiv = targetElement.querySelector('.seat');
        }
        
        // Only proceed if we found a seat and it's not unavailable
        if (seatDiv && !seatDiv.classList.contains('unavailable')) {
            // Prevent event bubbling
            e.preventDefault();
            e.stopPropagation();
            
            // If the seat is currently selected, unselect it
            if (seatDiv.classList.contains('selected')) {
                seatDiv.classList.remove('selected');
                seatDiv.classList.add('available');
                console.log('Seat unselected:', seatDiv.textContent);
            }
            // If the seat is available, select it (allow multi-select now)
            else if (seatDiv.classList.contains('available')) {
                seatDiv.classList.remove('available');
                seatDiv.classList.add('selected');
                console.log('Seat selected:', seatDiv.textContent);
            }
        }
    });
});

        function cancelBooking() {
            if (confirm('Are you sure you want to cancel your booking?')) {
                // Reset form
                document.querySelector('form').reset();
                // Reset seat selection
                document.querySelectorAll('.seat.selected').forEach(seat => {
                    seat.classList.remove('selected');
                    seat.classList.add('available');
                });
                alert('Booking cancelled');
            }
        }

        function closeSeatPreview() {
            // Clear the seat preview container
            const seatPreview = document.getElementById('seatPreview');
            if (seatPreview) {
                seatPreview.innerHTML = '';
                seatPreview.style.display = 'none';
            }
            
            // Reset the active ticket row state
            document.querySelectorAll('.ticket-row').forEach(row => {
                row.classList.remove('active');
            });
            
            // Reset the currentActiveTicketId in the parent window
            if (window.parent && window.parent.currentActiveTicketId !== undefined) {
                window.parent.currentActiveTicketId = null;
            }
        }

        function continueBooking() {
            const selectedSeats = document.querySelectorAll('.seat.selected');
            if (selectedSeats.length === 0) {
                alert('Please select at least one seat to continue');
                return;
            }
            
            const firstName = document.querySelector('input[placeholder="First Name"]').value;
            const lastName = document.querySelector('input[placeholder="Last Name"]').value;
            
            if (!firstName || !lastName) {
                alert('Please fill in passenger details to continue');
                return;
            }
            
            const seatNumbers = Array.from(selectedSeats).map(seat => seat.textContent).join(', ');
            alert(`Proceeding with booking for ${firstName} ${lastName}, Seats: ${seatNumbers}`);
        }