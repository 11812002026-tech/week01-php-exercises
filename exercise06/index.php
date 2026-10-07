<?php
declare(strict_types=1);

// Decision: every rule appends to $errors instead of returning early, so the user sees ALL problems at once.
function validateProfile(array $profile): array
{
    $errors = [];

    $name = trim((string)($profile['name'] ?? ''));
    if ($name === '')          { $errors[] = 'Name is required.'; }
    elseif (strlen($name) < 3) { $errors[] = 'Name must contain at least three characters.'; }

    $age = $profile['age'] ?? null;
    if (!is_int($age) || $age < 0 || $age > 120) { $errors[] = 'Age must be between 0 and 120.'; }

    if (trim((string)($profile['dzongkhag'] ?? '')) === '') { $errors[] = 'Dzongkhag is required.'; }

    if (strlen((string)($profile['cid'] ?? '')) !== 11) { $errors[] = 'CID must contain exactly 11 characters.'; }

    if (!in_array($profile['status'] ?? null, ['Active', 'Inactive'], true)) {
        $errors[] = 'Status must be Active or Inactive.';
    }

    return ['isValid' => $errors === [], 'errors' => $errors];
}

$tests = [
    'Valid profile'     => ['name' => 'Pema Dorji', 'age' => 24, 'dzongkhag' => 'Thimphu', 'cid' => '10000000001', 'status' => 'Active'],
    'Invalid profile A' => ['name' => '', 'age' => 150, 'dzongkhag' => 'Paro', 'cid' => '1234', 'status' => 'Active'],
    'Invalid profile B' => ['name' => 'Al', 'age' => -1, 'dzongkhag' => '  ', 'cid' => '10000000002', 'status' => 'active'],
    'Invalid profile C (age as text)' => ['name' => 'Tashi', 'age' => '30', 'dzongkhag' => 'Haa', 'cid' => '10000000003', 'status' => 'Pending'],
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 6 - Validation</title>
<style>body{font-family:sans-serif;margin:2rem}.ok{color:#0a7a2f;font-weight:bold}.bad{color:#b00020}</style></head>
<body><h1>Exercise 6: Profile Validation Engine</h1>
<?php foreach ($tests as $title => $profile): $r = validateProfile($profile); ?>
<h2><?= htmlspecialchars($title) ?></h2>
<?php if ($r['isValid']): ?><p class="ok">Profile is valid</p>
<?php else: ?><ul class="bad"><?php foreach ($r['errors'] as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul><?php endif; ?>
<?php endforeach; ?>
</body></html>
