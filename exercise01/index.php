<?php
declare(strict_types=1);

// Decision: one function builds every row, so the HTML layout lives in a single place (challenge).
function displayProfileField(string $label, string $value): string
{
    return '<tr><th>' . htmlspecialchars($label) . '</th><td>' . htmlspecialchars($value) . '</td></tr>';
}

function showProfile(array $p): void
{
    $statusText = $p['active'] ? 'Active' : 'Inactive';
    $cidLength  = strlen($p['cid']);

    echo '<table>';
    echo displayProfileField('Full name (uppercase)', strtoupper($p['name']));
    echo displayProfileField('Fictional CID', $p['cid']);
    echo displayProfileField('CID length', $cidLength . ' characters');
    echo displayProfileField('Date of birth', $p['dob']);
    echo displayProfileField('Dzongkhag', $p['dzongkhag']);
    echo displayProfileField('Gewog', $p['gewog']);
    echo displayProfileField('Occupation', $p['occupation']);
    echo displayProfileField('Status', $statusText);
    echo '</table>';

    if ($cidLength !== 11) {
        echo '<p class="warn">Warning: CID must contain exactly 11 characters (found ' . $cidLength . ').</p>';
    }
}

$profiles = [
    'Valid profile' => [
        'name' => 'Pema Dorji', 'cid' => '10000000001', 'dob' => '2001-04-12',
        'dzongkhag' => 'Thimphu', 'gewog' => 'Chang', 'occupation' => 'Teacher', 'active' => true,
    ],
    'Invalid CID, inactive' => [
        'name' => 'Sonam Choden', 'cid' => '12345', 'dob' => '1985-11-30',
        'dzongkhag' => 'Paro', 'gewog' => 'Doteng', 'occupation' => 'Farmer', 'active' => false,
    ],
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 1 - Citizen Profile</title>
<style>body{font-family:sans-serif;margin:2rem}table{border-collapse:collapse;margin-bottom:.5rem}th,td{border:1px solid #bbb;padding:.4rem .8rem;text-align:left}th{background:#f0f0f0}.warn{color:#b00020;font-weight:bold}</style></head>
<body><h1>Exercise 1: Fictional Citizen Profile</h1>
<?php foreach ($profiles as $title => $profile): ?>
<h2><?= htmlspecialchars($title) ?></h2>
<?php showProfile($profile); ?>
<?php endforeach; ?>
</body></html>
