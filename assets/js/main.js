
/* Calculator logic — faithful to original rules */
const moduleDurations = {
  "Basic Excel": 8,
  "Basic SQL": 8,
  "Power BI": 10,
  "Basic Python": 10,
  "Web BI Portfolio": 6
};
const packages = {
  "Specialize": ["Power BI", "Web BI Portfolio"],
  "Regular": ["Basic Excel", "Power BI", "Web BI Portfolio"],
  "Advance": ["Basic Excel", "Basic SQL", "Power BI", "Basic Python", "Web BI Portfolio"]
};
const scenarios = {
  "Private (1-on-1)": { participants: 1, discount: 0.00 },
  "Group of 3":      { participants: 3, discount: 0.10 },
  "Group of 5":      { participants: 5, discount: 0.20 },
  "Group of 10":     { participants: 10, discount: 0.25 }
};
const rate = 95000; // Rp per hour per person

function bindCalculator(){
  const pkgSel = document.getElementById('package');
  const scnSel = document.getElementById('scenario');
  const list = document.getElementById('modules-list');
  const out = document.getElementById('output');

  function renderModules(){
    const selected = packages[pkgSel.value] || [];
    list.innerHTML = selected.map(m => `<li>${m} — <span class="muted">${moduleDurations[m]} jam</span></li>`).join('');
  }
  function calculate(){
    const selected = packages[pkgSel.value] || [];
    const totalHours = selected.reduce((s,m)=> s + moduleDurations[m], 0);
    const grossPerPerson = totalHours * rate;
    const {participants, discount} = scenarios[scnSel.value];
    const discountedPerPerson = Math.round(grossPerPerson * (1 - discount));
    const totalGroup = discountedPerPerson * participants;

    out.innerHTML = `
      <div class="card">
        <h3>Ringkasan</h3>
        <ul class="list">
          <li>Total Durasi: <b>${totalHours} jam</b></li>
          <li>Biaya per Orang (sebelum diskon): <b>Rp ${grossPerPerson.toLocaleString()}</b></li>
          <li>Diskon: <b>${(discount*100).toFixed(0)}%</b></li>
          <li>Harga Akhir per Orang: <b>Rp ${discountedPerPerson.toLocaleString()}</b></li>
          <li>Total untuk ${participants} peserta: <b>Rp ${totalGroup.toLocaleString()}</b></li>
        </ul>
      </div>`;
  }

  pkgSel.addEventListener('change', () => { renderModules(); calculate(); });
  scnSel.addEventListener('change', calculate);

  renderModules(); calculate();
}
document.addEventListener('DOMContentLoaded', bindCalculator);
