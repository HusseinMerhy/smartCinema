<?php

echo "=================================\n";
echo "Starting Database Migrations...\n";
echo "=================================\n";

$all_files = glob('*.php');

$migration_files = array_filter($all_files, function($file) {
    return basename($file) !== 'run_migrations.php';
});

sort($migration_files);

if (empty($migration_files)) {
    echo "No migration files found.\n";
    exit;
}

foreach ($migration_files as $file) {
    echo "\n--> Running: " . $file . "\n";
    try {
        require $file;
    } catch (Throwable $e) {
        echo "\n[ERROR] Migration Failed: " . $e->getMessage() . "\n";
        echo "Process halted.\n";
        exit;
    }
}

echo "\n=================================\n";
echo "All migrations completed.\n";
echo "=================================\n";

?>