const cards = document.querySelectorAll('.facility-card');
const select = document.getElementById('facility');

// Card click to update select
cards.forEach(card => {
  card.addEventListener('click', () => {
    const value = card.getAttribute('data-value');
    select.value = value;

    cards.forEach(c => c.classList.remove('active'));
    card.classList.add('active');
  });
});

// Select change to highlight matching card
select.addEventListener('change', () => {
  const value = select.value;

  cards.forEach(card => {
    card.classList.remove('active');
    if (card.getAttribute('data-value') === value) {
      card.classList.add('active');
    }
  });
});


const overlay        = document.getElementById('modal-overlay');
const modalClose     = document.getElementById('modal-close');
const modalImg       = document.getElementById('modal-img');
const modalName      = document.getElementById('modal-name');
const modalDesc      = document.getElementById('modal-description');
const modalRate      = document.getElementById('modal-rate');
const modalCap       = document.getElementById('modal-capacity');
const modalBook      = document.getElementById('modal-book-btn');
const facilitySelect = document.getElementById('facility');

// open modal
document.querySelectorAll('.more-details').forEach(link => {
  link.addEventListener('click', e => {
        e.preventDefault();
        const data = link.dataset;

        modalImg.src                = data.img;
        modalImg.alt                = data.name;
        modalName.textContent       = data.name;
        modalDesc.textContent       = data.description;
        modalRate.textContent       = '₱' + Number(data.rate).toLocaleString() + ' · ' + data.unit;
        modalCap.textContent        = '👥 Up to ' + data.capacity + ' guests';
        modalBook.dataset.facility  = data.name;

        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    });
});

// close modal
function closeModal() {
    overlay.classList.remove('is-open');
    document.body.style.overflow = '';
}

modalClose.addEventListener('click', closeModal);
overlay.addEventListener('click', e => {
    if (e.target === overlay) closeModal();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});

// Book Now — selects the facility in the form and scrolls to it
modalBook.addEventListener('click', () => {
    const name = modalBook.dataset.facility;
    for (let opt of facilitySelect.options) {
        if (opt.value === name) {
            opt.selected = true;
            break;
        }
    }
    closeModal();
    facilitySelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
    facilitySelect.focus();
});

const checkin = document.getElementById('check-in-date');
const checkout = document.getElementById('check-out-date');
const facility = document.getElementById('facility');
const msg = document.getElementById('availability-msg');

async function checkAvailability() {
    if (!checkin.value || !checkout.value || !facility.value) return;

    const res = await fetch(
        `repositories/checkAvailability.php?facility_id=${facility.value}&checkin=${checkin.value}&checkout=${checkout.value}`
    );

    const data = await res.json();

    if (data.available) {
        msg.textContent = "Available ✔";
        msg.className = "show available";
    } else {
        msg.textContent = "Occupied during the selected date ❌";
        msg.className = "show unavailable";
    }
}

checkin.addEventListener('change', checkAvailability);
checkout.addEventListener('change', checkAvailability);
facility.addEventListener('change', checkAvailability);