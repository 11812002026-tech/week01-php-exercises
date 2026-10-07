<?php
declare(strict_types=1);

// Decision: ages 18+ (adults and seniors) qualify, because a senior citizen is also an adult.
function classifyCitizen(int $age): array
{
    if ($age < 0 || $age > 120) {
        return ['group' => 'Invalid', 'eligible' => false,
                'explanation' => "Age $age is invalid: it must be between 0 and 120."];
    }
    if ($age < 13)      { $group = 'Child'; }
    elseif ($age < 18)  { $group = 'Teenager'; }
    elseif ($age < 60)  { $group = 'Adult'; }
    else                { $group = 'Senior Citizen'; }

    $eligible = $age >= 18;
    $explanation = $eligible
        ? "Qualifies: aged $age ($group), which meets the adult-only minimum of 18."
        : "Does not qualify: aged $age ($group), but the service requires age 18 or above.";

    return ['group' => $group, 'eligible' => $eligible, 'explanation' => $explanation];
}

$name  = 'Karma Wangmo';
$tests = [8, 15, 18, 59, 60, 130, -3];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 2 - Age and Eligibility</title>
<style>body{font-family:sans-serif;margin:2rem}table{border-collapse:collapse}th,td{border:1px solid #bbb;padding:.4rem .8rem;text-align:left}th{background:#f0f0f0}.no{color:#b00020}.yes{color:#0a7a2f}</style></head>
<body><h1>Exercise 2: Age and Service Eligibility</h1>
<p>Citizen: <strong><?= htmlspecialchars($name) ?></strong></p>
<table><tr><th>Age</th><th>Age group</th><th>Eligible?</th><th>Explanation</th></tr>
<?php foreach ($tests as $age): $r = classifyCitizen($age); ?>
<tr><td><?= $age ?></td><td><?= htmlspecialchars($r['group']) ?></td>
<td class="<?= $r['eligible'] ? 'yes' : 'no' ?>"><?= $r['eligible'] ? 'Yes' : 'No' ?></td>
<td><?= htmlspecialchars($r['explanation']) ?></td></tr>
<?php endforeach; ?>
</table></body></html>
