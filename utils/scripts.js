const cards = document.querySelectorAll('.facility-card');
const select = document.getElementById('facility');

cards.forEach(card => {
  card.addEventListener('click', () => {
    const value = card.getAttribute('data-value');
    select.value = value;

    cards.forEach(c => c.classList.remove('active'));
    card.classList.add('active');
  });
});
