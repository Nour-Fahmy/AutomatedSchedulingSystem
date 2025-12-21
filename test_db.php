<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking database configuration...\n";
echo "APP_NAME: " . env('APP_NAME', 'not set') . "\n";
echo "DB_CONNECTION: " . env('DB_CONNECTION', 'not set') . "\n";
echo "Default connection config: " . config('database.default') . "\n";
echo "\n";

try {
    $defaultConnection = config('database.default');
    echo "Attempting to connect using: " . $defaultConnection . "\n";
    
    $connection = DB::connection($defaultConnection);
    $pdo = $connection->getPdo();
    echo "✓ Database connection: SUCCESS\n";
    echo "Database driver: " . $connection->getDriverName() . "\n";
    
    // Try to get database name
    try {
        $databaseName = DB::connection()->getDatabaseName();
        echo "Database name: " . $databaseName . "\n";
    } catch (\Exception $e) {
        echo "Database name: Could not retrieve\n";
    }
    
    // Test a simple query
    try {
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
        echo "Tables found: " . count($tables) . "\n";
        foreach ($tables as $table) {
            echo "  - " . $table->name . "\n";
        }
    } catch (\Exception $e) {
        echo "Could not list tables: " . $e->getMessage() . "\n";
    }
    
    // Check if users table exists
    try {
        $userCount = DB::table('users')->count();
        echo "Users in database: " . $userCount . "\n";
    } catch (\Exception $e) {
        echo "Users table: " . $e->getMessage() . "\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Database connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error type: " . get_class($e) . "\n";
}

