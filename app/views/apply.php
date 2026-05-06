<?php
declare(strict_types=1);

$errors = v('errors', []);
$old = v('old', []);

$cities = [
  'Sarajevo','Banja Luka','Tuzla','Zenica','Mostar','Bihać'
];

$vehicles = [
  'bicycle' => t('vehicle_bicycle'),
  'scooter' => t('vehicle_scooter'),
  'car' => t('vehicle_car'),
];

$oldCity = (string)($old['city'] ?? '');
$oldVehicle = (string)($old['vehicle_type'] ?? '');
$vehicleDisabled = $oldCity === '';
?>
<section class="hero">
  <div class="container">
    <div class="grid">
      <div class="card">
        <div class="card-pad">
          <h1 class="h1"><?= htmlspecialchars(t('apply_title')) ?></h1>
          <p class="p"><?= htmlspecialchars(t('apply_subtitle')) ?></p>
          <div style="height:18px"></div>
          <p class="p" style="font-size:14px"><?= htmlspecialchars(t('apply_note')) ?></p>
        </div>
      </div>

      <div class="card apply-card">
        <div class="card-pad">
          <h2><?= htmlspecialchars(t('apply_form_title')) ?></h2>

          <?php if (!empty($errors['rate'])): ?>
            <div class="alert"><?= htmlspecialchars($errors['rate']) ?></div>
          <?php endif; ?>

          <form id="apply-form" method="post" action="<?= htmlspecialchars(BASE_PATH) ?>/apply" autocomplete="off">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>" />

            <div class="field">
              <label class="label"><?= htmlspecialchars(t('full_name')) ?></label>
              <input class="input" name="full_name" value="<?= htmlspecialchars((string)($old['full_name'] ?? '')) ?>" placeholder="<?= htmlspecialchars(t('ph_full_name')) ?>">
              <?php if (!empty($errors['full_name'])): ?><div class="error"><?= htmlspecialchars($errors['full_name']) ?></div><?php endif; ?>
            </div>

            <div class="field">
              <label class="label"><?= htmlspecialchars(t('phone')) ?></label>
              <input class="input" name="phone" value="<?= htmlspecialchars((string)($old['phone'] ?? '')) ?>" placeholder="+387...">
              <?php if (!empty($errors['phone'])): ?><div class="error"><?= htmlspecialchars($errors['phone']) ?></div><?php endif; ?>
            </div>

            <!-- City (readonly input + modal) -->
            <div class="field">
              <label class="label"><?= htmlspecialchars(t('city')) ?></label>

              <input type="hidden" name="city" value="<?= htmlspecialchars($oldCity) ?>">
              <button type="button" class="textlike" data-modal-open="#modal-city">
                <input name="city_text" value="<?= htmlspecialchars($oldCity) ?>" placeholder="<?= htmlspecialchars(t('ph_city')) ?>" readonly>
                <span class="chev">▾</span>
              </button>

              <?php if (!empty($errors['city'])): ?><div class="error"><?= htmlspecialchars($errors['city']) ?></div><?php endif; ?>
            </div>

            <!-- Vehicle (disabled until city chosen) -->
            <div class="field">
              <label class="label"><?= htmlspecialchars(t('vehicle_type')) ?></label>

              <input type="hidden" name="vehicle_type" value="<?= htmlspecialchars($oldVehicle) ?>">
              <button
                type="button"
                class="textlike <?= $vehicleDisabled ? 'disabled' : '' ?>"
                data-modal-open="#modal-vehicle"
                <?= $vehicleDisabled ? 'disabled' : '' ?>
              >
                <input
                  name="vehicle_text"
                  value="<?= htmlspecialchars($vehicles[$oldVehicle] ?? '') ?>"
                  placeholder="<?= htmlspecialchars(t('ph_vehicle')) ?>"
                  readonly
                  <?= $vehicleDisabled ? 'disabled' : '' ?>
                >
                <span class="chev">▾</span>
              </button>

              <?php if (!empty($errors['vehicle_type'])): ?><div class="error"><?= htmlspecialchars($errors['vehicle_type']) ?></div><?php endif; ?>
            </div>

            <button class="btn" type="submit"><?= htmlspecialchars(t('submit')) ?></button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <div class="modal" id="modal-city" aria-hidden="true">
    <div class="modal-card">
      <div class="modal-head">
        <div class="modal-title"><?= htmlspecialchars(t('ph_city')) ?></div>
        <button class="modal-close" type="button" data-modal-close>✕</button>
      </div>
      <div class="modal-body">
        <input class="modal-search" type="text" data-modal-search placeholder="<?= htmlspecialchars(t('search')) ?>">
        <?php foreach ($cities as $c): ?>
          <div class="option" data-city-option="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="modal" id="modal-vehicle" aria-hidden="true">
    <div class="modal-card">
      <div class="modal-head">
        <div class="modal-title"><?= htmlspecialchars(t('ph_vehicle')) ?></div>
        <button class="modal-close" type="button" data-modal-close>✕</button>
      </div>
      <div class="modal-body">
        <?php foreach ($vehicles as $k => $label): ?>
          <div class="option" data-vehicle-option="<?= htmlspecialchars($k) ?>" data-vehicle-label="<?= htmlspecialchars($label) ?>">
            <?= htmlspecialchars($label) ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</section>

<!-- Mobile sticky CTA -->
<div class="mobile-cta">
  <button class="btn" type="button" data-cta="scroll-to-form" style="background: var(--green); color: var(--yellow);">
    <?= htmlspecialchars(t('cta_apply')) ?>
  </button>
</div>