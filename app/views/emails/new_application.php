<?php
declare(strict_types=1);
?>
<h2>New application</h2>
<ul>
  <li><b>Name:</b> <?= htmlspecialchars((string)($full_name ?? '')) ?></li>
  <li><b>Phone:</b> <?= htmlspecialchars((string)($phone ?? '')) ?></li>
  <li><b>City:</b> <?= htmlspecialchars((string)($city ?? '')) ?></li>
  <li><b>Vehicle:</b> <?= htmlspecialchars((string)($vehicle_type ?? '')) ?></li>
  <li><b>Locale:</b> <?= htmlspecialchars((string)($locale ?? '')) ?></li>
</ul>