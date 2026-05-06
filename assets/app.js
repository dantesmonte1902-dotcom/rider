(function () {
  function qs(sel, root) { return (root || document).querySelector(sel); }
  function qsa(sel, root) { return Array.from((root || document).querySelectorAll(sel)); }

  // Smooth scroll to form (mobile CTA)
  const cta = qs('[data-cta="scroll-to-form"]');
  if (cta) {
    cta.addEventListener('click', function () {
      const el = qs('#apply-form');
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  // Modal open/close
  function openModal(modal) {
    if (!modal) return;
    modal.classList.add('open');
    document.body.classList.add('modal-open');
    const input = qs('input[data-modal-search]', modal);
    if (input) setTimeout(() => input.focus(), 50);
  }
  function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('open');
    document.body.classList.remove('modal-open');
  }

  qsa('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', () => openModal(qs(btn.getAttribute('data-modal-open'))));
  });

  qsa('[data-modal-close]').forEach(btn => {
    btn.addEventListener('click', () => closeModal(btn.closest('.modal')));
  });

  qsa('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal(modal);
    });
  });

  // City picker
  const cityInput = qs('input[name="city_text"]');
  const cityHidden = qs('input[name="city"]');
  const cityModal = qs('#modal-city');

  function setCity(value) {
    if (!cityInput || !cityHidden) return;
    cityInput.value = value;
    cityHidden.value = value;

    // enable vehicle after city chosen
    const vehicleInput = qs('input[name="vehicle_text"]');
    const vehicleHidden = qs('input[name="vehicle_type"]');
    const vehicleBtn = qs('[data-modal-open="#modal-vehicle"]');

    if (vehicleInput) vehicleInput.removeAttribute('disabled');
    if (vehicleBtn) vehicleBtn.removeAttribute('disabled');
    if (vehicleHidden) vehicleHidden.value = '';
    if (vehicleInput) vehicleInput.value = '';
  }

  qsa('[data-city-option]').forEach(item => {
    item.addEventListener('click', () => {
      setCity(item.getAttribute('data-city-option'));
      closeModal(cityModal);
    });
  });

  // City search filter
  const citySearch = qs('#modal-city input[data-modal-search]');
  if (citySearch) {
    citySearch.addEventListener('input', () => {
      const v = citySearch.value.trim().toLowerCase();
      qsa('#modal-city [data-city-option]').forEach(opt => {
        const text = opt.getAttribute('data-city-option').toLowerCase();
        opt.style.display = text.includes(v) ? '' : 'none';
      });
    });
  }

  // Vehicle picker
  const vehicleInput = qs('input[name="vehicle_text"]');
  const vehicleHidden = qs('input[name="vehicle_type"]');
  const vehicleModal = qs('#modal-vehicle');

  function setVehicle(value, label) {
    if (!vehicleInput || !vehicleHidden) return;
    vehicleHidden.value = value;
    vehicleInput.value = label || value;
  }

  qsa('[data-vehicle-option]').forEach(item => {
    item.addEventListener('click', () => {
      setVehicle(item.getAttribute('data-vehicle-option'), item.getAttribute('data-vehicle-label'));
      closeModal(vehicleModal);
    });
  });

  // If vehicle modal button is disabled, prevent open
  const vehicleOpenBtn = qs('[data-modal-open="#modal-vehicle"]');
  if (vehicleOpenBtn) {
    vehicleOpenBtn.addEventListener('click', (e) => {
      if (vehicleOpenBtn.hasAttribute('disabled')) {
        e.preventDefault();
        e.stopPropagation();
      }
    });
  }
})();