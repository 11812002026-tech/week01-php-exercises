<?php
declare(strict_types=1);

const PROCESSING_CHARGE = 20.00;

// Decision: the function returns every step of the sum, so the page only formats and never calculates.
function calculateServiceFee(array $fees, string $service, int $quantity): array
{
    if (!array_key_exists($service, $fees)) {
        return ['error' => "Unknown service: \"$service\"."];
    }
    if ($quantity < 1) {
        return ['error' => 'Quantity must be at least 1.'];
    }
    $unit     = (float)$fees[$service];
    $cost     = $unit * $quantity;
    $discount = $quantity >= 5 ? $cost * 0.10 : 0.0;
    $final    = $cost - $discount + PROCESSING_CHARGE;

    return ['error' => null, 'service' => $service, 'unit' => $unit, 'quantity' => $quantity,
            'cost' => $cost, 'discount' => $discount, 'processing' => PROCESSING_CHARGE, 'final' => $final];
}

function nu(float $amount): string { return 'Nu. ' . number_format($amount, 2); }

$serviceFees = ["Certificate" => 100, "Verification" => 50, "Replacement" => 200, "Correction" => 75];
$orders = [
    ['Certificate', 2],    // no discount
    ['Replacement', 5],    // discount boundary
    ['Verification', 12],  // discount
    ['Passport', 1],       // unknown service
    ['Correction', 0],     // invalid quantity
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 7 - Fee Calculator</title>
<style>body{font-family:sans-serif;margin:2rem}table{border-collapse:collapse;margin-bottom:1rem}th,td{border:1px solid #bbb;padding:.4rem .8rem;text-align:left}th{background:#f0f0f0}.bad{color:#b00020;font-weight:bold}</style></head>
<body><h1>Exercise 7: Service Fee Calculator</h1>
<?php foreach ($orders as [$service, $qty]): $r = calculateServiceFee($serviceFees, $service, $qty); ?>
<h2><?= htmlspecialchars($service) ?> &times; <?= $qty ?></h2>
<?php if ($r['error'] !== null): ?><p class="bad">Error: <?= htmlspecialchars($r['error']) ?></p>
<?php else: ?>
<table>
<tr><th>Service</th><td><?= htmlspecialchars($r['service']) ?></td></tr>
<tr><th>Unit fee</th><td><?= nu($r['unit']) ?></td></tr>
<tr><th>Quantity</th><td><?= $r['quantity'] ?></td></tr>
<tr><th>Cost before discount</th><td><?= nu($r['cost']) ?></td></tr>
<tr><th>Discount</th><td>- <?= nu($r['discount']) ?></td></tr>
<tr><th>Processing charge</th><td><?= nu($r['processing']) ?></td></tr>
<tr><th>Final amount</th><td><strong><?= nu($r['final']) ?></strong></td></tr>
</table>
<?php endif; endforeach; ?>
</body></html>
