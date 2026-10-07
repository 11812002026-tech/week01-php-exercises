<?php
declare(strict_types=1);

// Decision: stripos() makes matching case insensitive without altering the stored names.
function searchProfiles(array $profiles, string $term): array
{
    $term = trim($term);
    if ($term === '') { return []; }   // an empty term would otherwise match everything
    $matches = [];
    foreach ($profiles as $p) {
        if (stripos($p['name'], $term) !== false) { $matches[] = $p; }
    }
    return $matches;
}

function renderTable(array $rows): void
{
    echo '<table><tr><th>Name</th><th>Dzongkhag</th><th>Age</th><th>Status</th></tr>';
    foreach ($rows as $p) {
        echo '<tr><td>' . htmlspecialchars($p['name']) . '</td><td>' . htmlspecialchars($p['dzongkhag'])
           . '</td><td>' . $p['age'] . '</td><td>' . htmlspecialchars($p['status']) . '</td></tr>';
    }
    echo '</table>';
}

$profiles = [
    ["name" => "Pema Dorji",      "dzongkhag" => "Thimphu",  "age" => 24, "status" => "Active"],
    ["name" => "Pema Lhamo",      "dzongkhag" => "Paro",     "age" => 31, "status" => "Active"],
    ["name" => "Sonam Choden",    "dzongkhag" => "Punakha",  "age" => 45, "status" => "Inactive"],
    ["name" => "Tashi Dorji",     "dzongkhag" => "Thimphu",  "age" => 19, "status" => "Active"],
    ["name" => "Karma Wangmo",    "dzongkhag" => "Bumthang", "age" => 62, "status" => "Active"],
    ["name" => "Dechen Om",       "dzongkhag" => "Wangdue",  "age" => 28, "status" => "Inactive"],
    ["name" => "Kinley Wangchuk", "dzongkhag" => "Trongsa",  "age" => 37, "status" => "Active"],
    ["name" => "Yeshey Pelden",   "dzongkhag" => "Haa",      "age" => 15, "status" => "Active"],
];
$searchTerms = ['pema' => 'several matches', 'KARMA' => 'one match', 'zzz' => 'no matches'];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 5 - Record Search</title>
<style>body{font-family:sans-serif;margin:2rem}table{border-collapse:collapse;margin-bottom:1rem}th,td{border:1px solid #bbb;padding:.4rem .8rem;text-align:left}th{background:#f0f0f0}.warn{color:#b00020}</style></head>
<body><h1>Exercise 5: B-PIS Record Search</h1>
<h2>All profiles (<?= count($profiles) ?>)</h2>
<?php renderTable($profiles); ?>
<?php foreach ($searchTerms as $term => $scenario): $found = searchProfiles($profiles, (string)$term); ?>
<h2>Search "<?= htmlspecialchars((string)$term) ?>" <small>(<?= $scenario ?>)</small></h2>
<p>Matching profiles: <strong><?= count($found) ?></strong></p>
<?php if ($found === []): ?><p class="warn">No matching records found</p><?php else: renderTable($found); endif; ?>
<?php endforeach; ?>
</body></html>
