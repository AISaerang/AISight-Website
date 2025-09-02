
(() => {
  const revealables = [...document.querySelectorAll('.reveal, section, .card, h1, h2, h3, .btn')];
  revealables.forEach(el => el.classList.add('reveal'));
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in-view'); io.unobserve(e.target); } });
  }, { threshold: 0.15 });
  revealables.forEach(el => io.observe(el));
})();
