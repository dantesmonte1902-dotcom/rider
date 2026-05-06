(function () {
  function qs(sel, root) { return (root || document).querySelector(sel); }

  // Smooth scroll to form (mobile CTA)
  const cta = qs('[data-cta="scroll-to-form"]');
  if (cta) {
    cta.addEventListener('click', function () {
      const el = qs('#apply-form');
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }
})();
