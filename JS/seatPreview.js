document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        let seatDiv = null;
        let targetElement = e.target;
        
        if (targetElement.classList.contains('seat')) {
            seatDiv = targetElement;
        }
        else if (targetElement.tagName === 'TD' && targetElement.querySelector('.seat')) {
            seatDiv = targetElement.querySelector('.seat');
        }
        
        if (seatDiv && !seatDiv.classList.contains('unavailable')) {
            e.preventDefault();
            e.stopPropagation();
            
            if (seatDiv.classList.contains('selected')) {
                seatDiv.classList.remove('selected');
                seatDiv.classList.add('available');
                console.log('Seat unselected:', seatDiv.textContent);
            }
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
                document.querySelector('form').reset();
                document.querySelectorAll('.seat.selected').forEach(seat => {
                    seat.classList.remove('selected');
                    seat.classList.add('available');
                });
                alert('Booking cancelled');
            }
        }

        function closeSeatPreview() {
            const seatPreview = document.getElementById('seatPreview');
            if (seatPreview) {
                seatPreview.innerHTML = '';
                seatPreview.style.display = 'none';
            }
            
            document.querySelectorAll('.ticket-row').forEach(row => {
                row.classList.remove('active');
            });
            
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