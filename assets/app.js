(function () {
  function qs(sel, root) { return (root || document).querySelector(sel); }
  function qsa(sel, root) { return Array.from((root || document).querySelectorAll(sel)); }

  // ── Smooth scroll to form (mobile CTA) ─────────────────────────────────────
  const cta = qs('[data-cta="scroll-to-form"]');
  if (cta) {
    cta.addEventListener('click', function () {
      const el = qs('#apply-form');
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  // ── FAQ accordion ──────────────────────────────────────────────────────────
  qsa('.faq-item').forEach(function (item) {
    const btn = qs('.faq-q', item);
    const body = qs('.faq-a', item);
    if (!btn || !body) return;
    btn.addEventListener('click', function () {
      const isOpen = item.classList.contains('open');
      // Close all
      qsa('.faq-item').forEach(function (i) {
        i.classList.remove('open');
        const b = qs('.faq-a', i);
        if (b) b.style.maxHeight = '0';
      });
      // Open clicked (if it wasn't already open)
      if (!isOpen) {
        item.classList.add('open');
        body.style.maxHeight = body.scrollHeight + 'px';
      }
    });
  });

  // ── Phone number masking ───────────────────────────────────────────────────
  const phoneInput = qs('input[name="phone"]');
  if (phoneInput) {
    phoneInput.addEventListener('input', function () {
      let digits = this.value.replace(/\D/g, '').slice(0, 11);
      let masked = '';
      if (digits.length <= 4)       masked = digits;
      else if (digits.length <= 7)  masked = digits.slice(0, 4) + ' ' + digits.slice(4);
      else if (digits.length <= 9)  masked = digits.slice(0, 4) + ' ' + digits.slice(4, 7) + ' ' + digits.slice(7);
      else                           masked = digits.slice(0, 4) + ' ' + digits.slice(4, 7) + ' ' + digits.slice(7, 9) + ' ' + digits.slice(9);
      this.value = masked;
    });
  }

  // ── 2-step form ────────────────────────────────────────────────────────────
  const form      = qs('#apply-form form');
  const step1     = qs('#form-step-1');
  const step2     = qs('#form-step-2');
  const dot1      = qs('#step-dot-1');
  const dot2      = qs('#step-dot-2');
  const btnNext   = qs('#btn-next-step');
  const btnBack   = qs('#btn-prev-step');

  if (form && step1 && step2 && btnNext) {
    btnNext.addEventListener('click', function () {
      // Validate step 1 fields
      let valid = true;
      qsa('input, select', step1).forEach(function (el) {
        if (el.required && el.value.trim() === '') {
          el.style.borderColor = '#ff6b6b';
          valid = false;
        } else {
          el.style.borderColor = '';
        }
      });
      if (!valid) return;

      step1.classList.add('hidden');
      step2.classList.remove('hidden');
      if (dot1) { dot1.classList.remove('active'); dot1.classList.add('done'); }
      if (dot2) dot2.classList.add('active');
      // Scroll to form top
      const formEl = qs('#apply-form');
      if (formEl) formEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  if (btnBack && step1 && step2) {
    btnBack.addEventListener('click', function () {
      step2.classList.add('hidden');
      step1.classList.remove('hidden');
      if (dot2) dot2.classList.remove('active');
      if (dot1) { dot1.classList.remove('done'); dot1.classList.add('active'); }
    });
  }
})();
