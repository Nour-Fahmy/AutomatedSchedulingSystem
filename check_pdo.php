<?php

echo "Available PDO Drivers:\n";
$drivers = PDO::getAvailableDrivers();
if (empty($drivers)) {
    echo "  ✗ No PDO drivers found!\n";
} else {
    foreach ($drivers as $driver) {
        echo "  ✓ $driver\n";
    }
}

echo "\n";
echo "SQLite support: " . (in_array('sqlite', $drivers) ? "✓ Available" : "✗ NOT AVAILABLE") . "\n";
echo "MySQL support: " . (in_array('mysql', $drivers) ? "✓ Available" : "✗ NOT AVAILABLE") . "\n";

