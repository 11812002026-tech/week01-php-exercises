<?php
declare(strict_types=1);

// Decision: "<" / ">" are used (not "<=") so the FIRST name wins a tie, giving predictable results.
function summariseMembers(array $names): array
{
    $shortest = $names[0];
    $longest  = $names[0];
    $totalLen = 0;
    foreach ($names as $name) {
        $len = strlen($name);
        $totalLen += $len;
        if ($len < strlen($shortest)) { $shortest = $name; }
        if ($len > strlen($longest))  { $longest  = $name; }
    }
    return [
        'count'    => count($names),
        'shortest' => $shortest,
        'longest'  => $longest,
        'average'  => $totalLen / count($names),
    ];
}

$members = ['Pema Dorji', 'Tashi', 'Sonam Choden', 'Dechen Om', 'Kinley Wangchuk', 'Yeshey', 'Lhamo'];
$s = summariseMembers($members);
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 3 - Household</title>
<style>body{font-family:sans-serif;margin:2rem}table{border-collapse:collapse}th,td{border:1px solid #bbb;padding:.4rem .8rem}th{background:#f0f0f0}</style></head>
<body><h1>Exercise 3: Household Member Summary</h1>
<h2>Members</h2>
<ol><?php foreach ($members as $m): ?><li><?= htmlspecialchars($m) ?></li><?php endforeach; ?></ol>
<p>Total members: <strong><?= $s['count'] ?></strong></p>
<p>First: <strong><?= htmlspecialchars($members[0]) ?></strong> | Last: <strong><?= htmlspecialchars($members[count($members) - 1]) ?></strong></p>
<p>Shortest name: <strong><?= htmlspecialchars($s['shortest']) ?></strong> (<?= strlen($s['shortest']) ?> characters)</p>
<p>Longest name: <strong><?= htmlspecialchars($s['longest']) ?></strong> (<?= strlen($s['longest']) ?> characters)</p>
<p>Average name length: <strong><?= number_format($s['average'], 2) ?></strong> characters</p>
<h2>Character counts</h2>
<table><tr><th>Name</th><th>Characters</th></tr>
<?php foreach ($members as $m): ?><tr><td><?= htmlspecialchars($m) ?></td><td><?= strlen($m) ?></td></tr><?php endforeach; ?>
</table></body></html>
