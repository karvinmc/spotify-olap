<?php
// Database connection and helper functions
require_once '../vendor/autoload.php';

// Connect to MongoDB with authentication
try {
  $client = new MongoDB\Client('mongodb://localhost:27017', [
    'username' => 'mongo',
    'password' => 'mongo',
    'authSource' => 'admin'
  ]);

  $collection = $client->spotify->spotify;
} catch (Exception $e) {
  die("Database error: " . $e->getMessage());
}