<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Current schools in database:\n";
$schools = App\Models\School::all();
foreach($schools as $school) {
    echo "ID: {$school->id} - {$school->name} (domain: {$school->domain})\n";
}
?>
