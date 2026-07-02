<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::find(1);

if (!$user) {
    echo "User ID 1 not found!\n";
    exit;
}

echo "=== User ID 1 Info ===\n";
echo "Name: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Role (column): {$user->role}\n";
echo "\n=== Roles (Spatie) ===\n";
$roles = $user->roles;
if ($roles->count() > 0) {
    foreach ($roles as $role) {
        echo "- {$role->name}\n";
    }
} else {
    echo "NO ROLES ASSIGNED!\n";
}

echo "\n=== Permissions ===\n";
$permissions = $user->getAllPermissions();
echo "Total permissions: {$permissions->count()}\n";

echo "\n=== Can Checks ===\n";
echo "Can dashboard_view: " . ($user->can('dashboard_view') ? 'YES' : 'NO') . "\n";
echo "Can product_view: " . ($user->can('product_view') ? 'YES' : 'NO') . "\n";

echo "\n=== Helper Methods ===\n";
echo "isAdmin(): " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
echo "isSuperAdmin(): " . ($user->isSuperAdmin() ? 'YES' : 'NO') . "\n";

echo "\n=== model_has_roles table ===\n";
$modelHasRoles = DB::table('model_has_roles')->where('model_id', 1)->where('model_type', 'App\\Models\\User')->get();
if ($modelHasRoles->count() > 0) {
    foreach ($modelHasRoles as $mhr) {
        $roleName = DB::table('roles')->where('id', $mhr->role_id)->value('name');
        echo "- Role ID: {$mhr->role_id} ({$roleName})\n";
    }
} else {
    echo "NO ENTRIES IN model_has_roles!\n";
}
