<?php
declare(strict_types=1);

// Decision: each counting function loops once over the records; they share a tiny helper only for the tally.
function tally(array $records, string $field): array
{
    $counts = [];
    foreach ($records as $r) {
        $counts[$r[$field]] = ($counts[$r[$field]] ?? 0) + 1;
    }
    return $counts;
}
function countByStatus(array $records): array     { return tally($records, 'status'); }
function countByDzongkhag(array $records): array  { return tally($records, 'dzongkhag'); }
function countByRecordType(array $records): array { return tally($records, 'type'); }
function percentage(int $part, int $whole): float { return $whole > 0 ? ($part / $whole) * 100 : 0.0; }
function mostCommon(array $counts): string
{
    $top = '';
    $max = 0;
    foreach ($counts as $k => $v) { if ($v > $max) { $top = (string)$k; $max = $v; } }
    return $top;
}

$records = [
    ['name' => 'Pema Dorji',      'dzongkhag' => 'Thimphu',  'age' => 24, 'status' => 'Active',   'type' => 'Citizenship'],
    ['name' => 'Sonam Choden',    'dzongkhag' => 'Paro',     'age' => 45, 'status' => 'Inactive', 'type' => 'Birth'],
    ['name' => 'Tashi Dorji',     'dzongkhag' => 'Thimphu',  'age' => 19, 'status' => 'Active',   'type' => 'Citizenship'],
    ['name' => 'Karma Wangmo',    'dzongkhag' => 'Bumthang', 'age' => 62, 'status' => 'Active',   'type' => 'Residence'],
    ['name' => 'Dechen Om',       'dzongkhag' => 'Wangdue',  'age' => 28, 'status' => 'Inactive', 'type' => 'Birth'],
    ['name' => 'Kinley Wangchuk', 'dzongkhag' => 'Thimphu',  'age' => 37, 'status' => 'Active',   'type' => 'Citizenship'],
    ['name' => 'Yeshey Pelden',   'dzongkhag' => 'Haa',      'age' => 15, 'status' => 'Active',   'type' => 'Birth'],
    ['name' => 'Lhamo Zangmo',    'dzongkhag' => 'Paro',     'age' => 71, 'status' => 'Active',   'type' => 'Residence'],
    ['name' => 'Ugyen Tenzin',    'dzongkhag' => 'Punakha',  'age' => 33, 'status' => 'Inactive', 'type' => 'Citizenship'],
    ['name' => 'Choki Lhaki',     'dzongkhag' => 'Thimphu',  'age' => 8,  'status' => 'Active',   'type' => 'Birth'],
    ['name' => 'Rinzin Penjor',   'dzongkhag' => 'Trongsa',  'age' => 60, 'status' => 'Active',   'type' => 'Residence'],
    ['name' => 'Namgay Om',       'dzongkhag' => 'Paro',     'age' => 52, 'status' => 'Active',   'type' => 'Citizenship'],
];

$total   = count($records);
$status  = countByStatus($records);
$active  = $status['Active'] ?? 0;
$inactive = $status['Inactive'] ?? 0;
$adults = 0; $seniors = 0;
foreach ($records as $r) {
    if ($r['age'] >= 60)      { $seniors++; }
    elseif ($r['age'] >= 18)  { $adults++; }
}
$byDz   = countByDzongkhag($records);
$byType = countByRecordType($records);
$activePct = percentage($active, $total);
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 9 - Records Report</title>
<style>body{font-family:sans-serif;margin:2rem}table{border-collapse:collapse;margin-bottom:1.5rem}th,td{border:1px solid #bbb;padding:.4rem .8rem;text-align:left}th{background:#f0f0f0}</style></head>
<body><h1>Exercise 9: B-PIS Records Report</h1>
<h2>Summary</h2>
<table>
<tr><th>Total records</th><td><?= $total ?></td></tr>
<tr><th>Active</th><td><?= $active ?></td></tr>
<tr><th>Inactive</th><td><?= $inactive ?></td></tr>
<tr><th>Active percentage</th><td><?= number_format($activePct, 1) ?>%</td></tr>
<tr><th>Adults (18-59)</th><td><?= $adults ?></td></tr>
<tr><th>Senior citizens (60+)</th><td><?= $seniors ?></td></tr>
</table>
<h2>Records per dzongkhag</h2>
<table><tr><th>Dzongkhag</th><th>Records</th></tr>
<?php foreach ($byDz as $k => $v): ?><tr><td><?= htmlspecialchars($k) ?></td><td><?= $v ?></td></tr><?php endforeach; ?></table>
<h2>Records per record type</h2>
<table><tr><th>Type</th><th>Records</th></tr>
<?php foreach ($byType as $k => $v): ?><tr><td><?= htmlspecialchars($k) ?></td><td><?= $v ?></td></tr><?php endforeach; ?></table>
<h2>Interpretation</h2>
<p><?= htmlspecialchars(mostCommon($byDz)) ?> has the most records (<?= $byDz[mostCommon($byDz)] ?> of <?= $total ?>),
and <?= htmlspecialchars(mostCommon($byType)) ?> is the most frequent record type
(<?= $byType[mostCommon($byType)] ?> of <?= $total ?>). About <?= number_format($activePct, 0) ?>% of all records are active.</p>
</body></html>
