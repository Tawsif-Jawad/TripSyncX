let currentActiveTicketId = null;

function initializeSeatSelection() {
  const seatPreview = document.getElementById('seatPreview');
  if (seatPreview) {
    seatPreview.addEventListener('click', function(e) {
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
        }
        else if (seatDiv.classList.contains('available')) {
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

      if (row.classList.contains('active')) {
        row.classList.remove('active');
        if (seatPreviewContainer) { seatPreviewContainer.innerHTML=''; seatPreviewContainer.style.display='none'; }
        return;
      }
      document.querySelectorAll('.ticket-row.active').forEach(r=>r.classList.remove('active'));
      row.classList.add('active');
      if (seatPreviewContainer) {
        seatPreviewContainer.style.display='block';
        seatPreviewContainer.innerHTML = '<div style="padding:12px;">Loading seats...</div>';
        fetch('seatPreview.php?' + params.toString())
          .then(r=>r.text())
          .then(html=>{
            seatPreviewContainer.innerHTML = html;
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
    departureDateInput.value = todayString; 
  }
}

function validateDate() {
  const departureDateInput = document.getElementById('departure_date');
  if (departureDateInput) {
    const selectedDate = new Date(departureDateInput.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); 
    
    if (selectedDate < today) {
      alert('Departure date must be today or a future date.');
      departureDateInput.value = '';
      return false;
    }
  }
  return true;
}