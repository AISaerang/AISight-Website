// AISight Theme & Palette Manager (robust minimal)
(() => {
  const ORDER = ['teal','violet','orange'];
  const PALETTES = {
    teal:   { accent:'#14B8A6', strong:'#0F766E', ring:'#22D3EE' },
    violet: { accent:'#7C3AED', strong:'#5B21B6', ring:'#8B5CF6' },
    orange: { accent:'#FB923C', strong:'#C2410C', ring:'#FDBA74' }
  };

  function applyPalette(name) {
    const p = PALETTES[name] || PALETTES.teal;
    document.documentElement.style.setProperty('--accent', p.accent);
    document.documentElement.style.setProperty('--accent-strong', p.strong);
    document.documentElement.style.setProperty('--ring', p.ring);
    localStorage.setItem('palette', name);
    document.querySelectorAll('.swatch').forEach(el => {
      el.classList.toggle('active', el.getAttribute('data-palette') === name);
    });
  }

  function toggleTheme() {
    const light = document.body.classList.toggle('theme-light');
    localStorage.setItem('theme', light ? 'light' : 'dark');
  }

  // Delegated events: works even if header is injected later
  let CURRENT = localStorage.getItem('palette') || 'teal';
  document.addEventListener('click', (e) => {
    const tgl = e.target.closest('[data-toggle-theme]');
    if (tgl) { e.preventDefault(); toggleTheme(); return; }
    const sw = e.target.closest('[data-palette]');
    if (sw)  { e.preventDefault(); CURRENT = sw.getAttribute('data-palette'); applyPalette(CURRENT); return; }
    const pbtn = e.target.closest('[data-palette-control]');
    if (pbtn) { e.preventDefault(); CURRENT = ORDER[(ORDER.indexOf(CURRENT)+1)%ORDER.length]; applyPalette(CURRENT); return; }
  });

  document.addEventListener('keydown', (e) => {
    const t = e.target.closest('[data-toggle-theme]');
    if (t && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); toggleTheme(); }
  });

  // Restore on load
  document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('theme') === 'light') document.body.classList.add('theme-light');
    CURRENT = localStorage.getItem('palette') || 'teal';
    applyPalette(CURRENT);
  });
})();