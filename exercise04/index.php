<?php
declare(strict_types=1);

$requests = ["Thimphu", "Paro", "Thimphu", "Punakha", "Paro", "Thimphu"];

// Decision: counters are built dynamically in one associative array keyed by dzongkhag,
// so new dzongkhags need no new variables.
$counts = [];
foreach ($requests as $dzongkhag) {
    if (!array_key_exists($dzongkhag, $counts)) {
        $counts[$dzongkhag] = 0;
    }
    $counts[$dzongkhag]++;
}

$total = count($requests);
$topName = '';
$topCount = 0;
foreach ($counts as $name => $c) {
    if ($c > $topCount) { $topName = $name; $topCount = $c; }
}
$thimphuPct = $total > 0 ? (($counts['Thimphu'] ?? 0) / $total) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 4 - Service Counter</title>
<style>body{font-family:sans-serif;margin:2rem}</style></head>
<body><h1>Exercise 4: Dzongkhag Service Counter</h1>
<h2>Requests</h2>
<ul><?php foreach ($requests as $i => $r): ?><li>Request #<?= $i + 1 ?>: <?= htmlspecialchars($r) ?></li><?php endforeach; ?></ul>
<h2>Requests per dzongkhag</h2>
<ul><?php foreach ($counts as $name => $c): ?><li><?= htmlspecialchars($name) ?>: <?= $c ?></li><?php endforeach; ?></ul>
<p>Total requests: <strong><?= $total ?></strong></p>
<p>Highest: <strong><?= htmlspecialchars($topName) ?></strong> with <?= $topCount ?> requests</p>
<p>Thimphu share: <strong><?= number_format($thimphuPct, 1) ?>%</strong></p>
</body></html>
