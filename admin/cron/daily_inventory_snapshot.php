<?php
// Daily inventory snapshot cron job
// This script should be run daily via cron job to capture inventory snapshots

// Set the working directory
chdir(dirname(__FILE__) . '/../..');

// Include necessary files
require_once('config.php');
require_once('classes/Master.php');

// Create Master instance and capture snapshot
$Master = new Master();
$result = $Master->capture_inventory_snapshot();

// Log the result
$logFile = 'admin/cron/inventory_snapshot.log';
$logMessage = date('Y-m-d H:i:s') . ' - ' . $result . PHP_EOL;
file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

// Output result for cron job logs
echo date('Y-m-d H:i:s') . " - Daily inventory snapshot completed\n";
?>
