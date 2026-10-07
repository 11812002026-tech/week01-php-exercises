<?php
declare(strict_types=1);

/* ---------- Reusable functions ---------- */

// Decision: one function lists problems; both the warnings and the quality score reuse it,
// so the rules and their 25-point penalties can never drift apart.
function findProblems(array $p): array
{
    $problems = [];
    if (trim((string)($p['name'] ?? '')) === '') { $problems[] = 'Missing name'; }
    $age = $p['age'] ?? null;
    if (!is_int($age) || $age < 0 || $age > 120) { $problems[] = 'Invalid age'; }
    if (trim((string)($p['dzongkhag'] ?? '')) === '') { $problems[] = 'Missing dzongkhag'; }
    if (!in_array($p['status'] ?? null, ['Active', 'Inactive'], true)) { $problems[] = 'Invalid status'; }
    return $problems;
}
function qualityScore(array $p): int { return 100 - 25 * count(findProblems($p)); }

function filterProfiles(array $profiles, string $dzongkhag, string $status): array
{
    $out = [];
    foreach ($profiles as $p) {
        if (($dzongkhag === 'All' || $p['dzongkhag'] === $dzongkhag) && ($status === 'All' || $p['status'] === $status)) {
            $out[] = $p;
        }
    }
    return $out;
}

function canPerformAction(array $permissions, string $role, string $action): bool
{
    $role = strtolower(trim($role));
    return array_key_exists($role, $permissions) && in_array(strtolower(trim($action)), $permissions[$role], true);
}

function renderDashboard(string $title, array $profiles, string $fDz, string $fStatus, string $role, string $action, array $permissions): void
{
    $active = 0; $inactive = 0; $ageSum = 0; $ageCount = 0; $youngest = null; $oldest = null;
    $scoreSum = 0; $imperfect = 0; $worst = null; $worstScore = 101;

    foreach ($profiles as $p) {
        $status = $p['status'] ?? '';
        if ($status === 'Active') { $active++; } elseif ($status === 'Inactive') { $inactive++; }

        if (is_int($p['age'] ?? null) && $p['age'] >= 0 && $p['age'] <= 120) {
            $ageSum += $p['age']; $ageCount++;
            if ($youngest === null || $p['age'] < $youngest['age']) { $youngest = $p; }
            if ($oldest === null   || $p['age'] > $oldest['age'])   { $oldest = $p; }
        }
        $score = qualityScore($p);
        $scoreSum += $score;
        if ($score < 100) { $imperfect++; }
        if ($score < $worstScore) { $worstScore = $score; $worst = $p; }
    }
    $n = count($profiles);
    $filtered = filterProfiles($profiles, $fDz, $fStatus);
    $allowed = canPerformAction($permissions, $role, $action);

    echo '<section><h2>' . htmlspecialchars($title) . '</h2>';
    echo "<p>Total profiles: <strong>$n</strong> | Active: <strong>$active</strong> | Inactive: <strong>$inactive</strong></p>";
    echo '<p>Youngest: <strong>' . ($youngest ? htmlspecialchars($youngest['name'] . " ({$youngest['age']})") : 'n/a') . '</strong> | ';
    echo 'Oldest: <strong>' . ($oldest ? htmlspecialchars($oldest['name'] . " ({$oldest['age']})") : 'n/a') . '</strong> | ';
    echo 'Average age: <strong>' . ($ageCount > 0 ? number_format($ageSum / $ageCount, 1) : 'n/a') . '</strong></p>';

    echo '<h3>All profiles with data-quality score</h3><table><tr><th>#</th><th>Name</th><th>Dzongkhag</th><th>Age</th><th>Status</th><th>Score</th><th>Warnings</th></tr>';
    foreach ($profiles as $i => $p) {
        $w = findProblems($p);
        echo '<tr><td>' . ($i + 1) . '</td><td>' . htmlspecialchars((string)$p['name']) . '</td><td>' . htmlspecialchars((string)$p['dzongkhag'])
           . '</td><td>' . htmlspecialchars((string)$p['age']) . '</td><td>' . htmlspecialchars((string)$p['status'])
           . '</td><td>' . qualityScore($p) . '</td><td class="warn">' . htmlspecialchars(implode('; ', $w)) . '</td></tr>';
    }
    echo '</table>';
    echo '<p>Average quality score: <strong>' . number_format($scoreSum / $n, 1) . '</strong> | Imperfect profiles: <strong>' . $imperfect . '</strong></p>';
    if ($imperfect > 0 && $worst !== null) {
        $label = trim((string)$worst['name']) !== '' ? $worst['name'] : '(no name) CID ' . $worst['cid'];
        echo '<p>Needs most correction: <strong>' . htmlspecialchars((string)$label) . "</strong> (score $worstScore)</p>";
    }

    echo '<h3>Filter: ' . htmlspecialchars($fDz) . ' / ' . htmlspecialchars($fStatus) . ' (' . count($filtered) . ' found)</h3>';
    if ($filtered === []) { echo '<p class="warn">No matching records found</p>'; }
    else { echo '<ul>'; foreach ($filtered as $p) { echo '<li>' . htmlspecialchars((string)$p['name']) . ' - ' . htmlspecialchars((string)$p['dzongkhag']) . ', ' . htmlspecialchars((string)$p['status']) . '</li>'; } echo '</ul>'; }

    echo '<h3>Permission check</h3><p class="' . ($allowed ? 'ok' : 'warn') . '">'
       . htmlspecialchars($allowed ? "$role may $action records." : "$role may NOT $action records.") . '</p></section>';
}

/* ---------- Data ---------- */
$permissions = [
    "administrator" => ["create", "read", "update", "delete", "export"],
    "data officer"  => ["create", "read", "update"],
    "viewer"        => ["read"],
];

$validData = [
    ['name' => 'Pema Dorji',      'cid' => '10000000001', 'dzongkhag' => 'Thimphu',  'age' => 24, 'status' => 'Active'],
    ['name' => 'Sonam Choden',    'cid' => '10000000002', 'dzongkhag' => 'Paro',     'age' => 45, 'status' => 'Inactive'],
    ['name' => 'Tashi Dorji',     'cid' => '10000000003', 'dzongkhag' => 'Thimphu',  'age' => 19, 'status' => 'Active'],
    ['name' => 'Karma Wangmo',    'cid' => '10000000004', 'dzongkhag' => 'Bumthang', 'age' => 62, 'status' => 'Active'],
    ['name' => 'Dechen Om',       'cid' => '10000000005', 'dzongkhag' => 'Wangdue',  'age' => 28, 'status' => 'Inactive'],
    ['name' => 'Kinley Wangchuk', 'cid' => '10000000006', 'dzongkhag' => 'Thimphu',  'age' => 37, 'status' => 'Active'],
    ['name' => 'Yeshey Pelden',   'cid' => '10000000007', 'dzongkhag' => 'Haa',      'age' => 15, 'status' => 'Active'],
    ['name' => 'Lhamo Zangmo',    'cid' => '10000000008', 'dzongkhag' => 'Paro',     'age' => 71, 'status' => 'Active'],
];
$flawedData = $validData;
$flawedData[1]['name']      = '';          // missing name
$flawedData[3]['age']       = 150;         // invalid age
$flawedData[4]['dzongkhag'] = '';          // missing dzongkhag
$flawedData[4]['status']    = 'Unknown';   // invalid status (record 5 has two problems)
$flawedData[6]['age']       = 'fifteen';   // invalid age type

/* ---------- Selections (editable via the URL, e.g. ?dzongkhag=Paro&status=Active&role=viewer&action=delete) ---------- */
$selDz     = (string)($_GET['dzongkhag'] ?? 'Thimphu');
$selStatus = (string)($_GET['status'] ?? 'Active');
$selRole   = (string)($_GET['role'] ?? 'Data Officer');
$selAction = (string)($_GET['action'] ?? 'delete');
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 10 - B-PIS Dashboard</title>
<style>body{font-family:sans-serif;margin:2rem}section{border:1px solid #ccc;padding:1rem 1.5rem;margin-bottom:2rem;border-radius:8px}table{border-collapse:collapse;margin-bottom:1rem}th,td{border:1px solid #bbb;padding:.35rem .7rem;text-align:left}th{background:#f0f0f0}.warn{color:#b00020}.ok{color:#0a7a2f;font-weight:bold}</style></head>
<body><h1>Exercise 10: B-PIS Command Dashboard</h1>
<p>Selections: dzongkhag <strong><?= htmlspecialchars($selDz) ?></strong>, status <strong><?= htmlspecialchars($selStatus) ?></strong>,
role <strong><?= htmlspecialchars($selRole) ?></strong>, action <strong><?= htmlspecialchars($selAction) ?></strong>
(change them with URL parameters; use <code>All</code> to skip a filter).</p>
<?php
renderDashboard('Dataset 1: complete valid data', $validData, $selDz, $selStatus, $selRole, $selAction, $permissions);
renderDashboard('Dataset 2: data with validation problems', $flawedData, $selDz, $selStatus, $selRole, $selAction, $permissions);
?>
</body></html>
