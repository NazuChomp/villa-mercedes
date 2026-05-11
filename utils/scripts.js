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
