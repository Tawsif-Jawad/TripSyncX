let currentActiveTicketId = null;

function initializeSeatSelection() {
  // Remove any existing listeners to prevent duplicates
  const seatPreview = document.getElementById('seatPreview');
  if (seatPreview) {
    seatPreview.addEventListener('click', function(e) {
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
        
        // Toggle the seat selection (no need to unselect others)
        if (seatDiv.classList.contains('selected')) {
          // Unselect this seat
          seatDiv.classList.remove('selected');
          seatDiv.classList.add('available');
        }
        else if (seatDiv.classList.contains('available')) {
          // Select this seat (keep others selected)
          seatDiv.classList.remove('available');
          seatDiv.classList.add('selected');
        }
      }
    });
  }
}

function closeSeatPreview() {
  const seatPreview = document.getElementById('seatPreview');
  if (seatPreview) {
    seatPreview.innerHTML = '';
    seatPreview.style.display = 'none';
  }
  
  // Remove active styling from all rows
  document.querySelectorAll('.ticket-row').forEach(r => r.classList.remove('active'));
  currentActiveTicketId = null;
}

document.addEventListener('DOMContentLoaded', function() {
  setMinimumDate();
  const departureDateInput = document.getElementById('departure_date');
  if (departureDateInput) departureDateInput.addEventListener('change', validateDate);

  const seatPreviewContainer = document.getElementById('seatPreview');
  document.querySelectorAll('.ticket-row').forEach(function(row) {
    row.addEventListener('click', function() {
      const time = row.getAttribute('data-time');
      const date = row.getAttribute('data-date');
      const from = row.getAttribute('data-from');
      const to = row.getAttribute('data-to');
      const type = row.getAttribute('data-type');
      const fare = row.getAttribute('data-fare');
      const params = new URLSearchParams({ fragment:'1', time, date, from, to, type, fare });

      // Toggle behavior: close if already active
      if (row.classList.contains('active')) {
        row.classList.remove('active');
        if (seatPreviewContainer) { seatPreviewContainer.innerHTML=''; seatPreviewContainer.style.display='none'; }
        return;
      }
      // Clear previous active
      document.querySelectorAll('.ticket-row.active').forEach(r=>r.classList.remove('active'));
      row.classList.add('active');
      if (seatPreviewContainer) {
        seatPreviewContainer.style.display='block';
        seatPreviewContainer.innerHTML = '<div style="padding:12px;">Loading seats...</div>';
        fetch('seatPreview.php?' + params.toString())
          .then(r=>r.text())
          .then(html=>{
            seatPreviewContainer.innerHTML = html;
            // Rebind seat click logic for newly injected content
            initializeSeatSelection();
          })
          .catch(()=>{
            seatPreviewContainer.innerHTML = '<div style="color:#c00;padding:12px;">Failed to load seat preview.</div>';
          });
      }
    });
  });
});

function setMinimumDate() {
  const departureDateInput = document.getElementById('departure_date');
  if (departureDateInput) {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const todayString = `${year}-${month}-${day}`;
    
    departureDateInput.setAttribute('min', todayString);
    departureDateInput.value = todayString; // Set default to today
  }
}

function validateDate() {
  const departureDateInput = document.getElementById('departure_date');
  if (departureDateInput) {
    const selectedDate = new Date(departureDateInput.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time to start of day
    
    if (selectedDate < today) {
      alert('Departure date must be today or a future date.');
      departureDateInput.value = '';
      return false;
    }
  }
  return true;
}