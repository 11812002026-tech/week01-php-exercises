<?php
declare(strict_types=1);

// Decision: permissions are data (an array), not code, so adding a role never means editing logic.
function canPerformAction(array $permissions, string $role, string $action): bool
{
    $role   = strtolower(trim($role));
    $action = strtolower(trim($action));
    if (!array_key_exists($role, $permissions)) {
        return false;   // unknown roles have no permissions
    }
    return in_array($action, $permissions[$role], true);
}

$permissions = [
    "administrator" => ["create", "read", "update", "delete", "export"],
    "data officer"  => ["create", "read", "update"],
    "viewer"        => ["read"],
];

$tests = [
    ['Administrator', 'export'], ['ADMINISTRATOR', 'Delete'], ['Data Officer', 'update'],
    ['data officer', 'delete'],  ['Viewer', 'read'],          ['viewer', 'create'],
    ['Guest', 'read'],           ['Data Officer', 'EXPORT'],  ['Viewer', 'Export'],
];
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><title>Exercise 8 - Permissions</title>
<style>body{font-family:sans-serif;margin:2rem}table{border-collapse:collapse}th,td{border:1px solid #bbb;padding:.4rem .8rem;text-align:left}th{background:#f0f0f0}.yes{color:#0a7a2f}.no{color:#b00020}</style></head>
<body><h1>Exercise 8: Role Permission Checker</h1>
<table><tr><th>Role</th><th>Action</th><th>Result</th></tr>
<?php foreach ($tests as [$role, $action]):
    $ok = canPerformAction($permissions, $role, $action);
    $known = array_key_exists(strtolower(trim($role)), $permissions);
    $msg = $ok ? "Allowed: $role may $action records."
         : ($known ? "Denied: $role is not permitted to $action records."
                   : "Denied: \"$role\" is an unknown role and has no permissions."); ?>
<tr><td><?= htmlspecialchars($role) ?></td><td><?= htmlspecialchars($action) ?></td>
<td class="<?= $ok ? 'yes' : 'no' ?>"><?= htmlspecialchars($msg) ?></td></tr>
<?php endforeach; ?>
</table></body></html>
