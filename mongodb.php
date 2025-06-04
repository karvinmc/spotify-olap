<?php
// Database connection and helper functions
require_once 'vendor/autoload.php';

// Connect to MongoDB with authentication
try {
  $client = new MongoDB\Client('mongodb://localhost:27017', [
    'username' => 'mongo',
    'password' => 'mongo',
    'authSource' => 'admin'
  ]);

  $resto = $client->restaurant->restaurants;
} catch (Exception $e) {
  die("Database error: " . $e->getMessage());
}

function getDistinctBoroughs()
{
  global $resto;
  return $resto->distinct('borough');
}

function getDistinctCuisines()
{
  global $resto;
  return $resto->distinct('cuisine');
}

function getFilteredRestaurants($borough = null, $cuisine = null, $maxScore = null, $limit = 20, $skip = 0)
{
  global $resto;

  $filter = [];

  // Add borough filter if specified
  if (!empty($borough) && $borough !== 'all') {
    $filter['borough'] = $borough;
  }

  // Add cuisine filter if specified (using regex for keyword search)
  if (!empty($cuisine)) {
    $filter['cuisine'] = ['$regex' => $cuisine, '$options' => 'i'];
  }

  // Add grade score filter if specified
  if (!empty($maxScore) && is_numeric($maxScore)) {
    $filter['grades'] = [
      '$not' => [
        '$elemMatch' => [
          'score' => ['$gte' => (int)$maxScore]
        ]
      ]
    ];
  }

  // Set options for the query
  $options = [
    'projection' => [
      '_id' => 0,
      'name' => 1,
      'borough' => 1,
      'cuisine' => 1,
      'address' => 1,
      'grades' => 1
    ],
    'limit' => $limit,
    'skip' => $skip
  ];

  // Execute and return the query
  return $resto->find($filter, $options);
}

function countFilteredRestaurants($borough = null, $cuisine = null, $maxScore = null)
{
  global $resto;

  $filter = [];

  if (!empty($borough) && $borough !== 'all') {
    $filter['borough'] = $borough;
  }

  if (!empty($cuisine)) {
    $filter['cuisine'] = ['$regex' => $cuisine, '$options' => 'i'];
  }

  if (!empty($maxScore) && is_numeric($maxScore)) {
    $filter['grades'] = [
      '$not' => [
        '$elemMatch' => [
          'score' => ['$gte' => (int)$maxScore]
        ]
      ]
    ];
  }

  return $resto->countDocuments($filter);
}

function formatRestaurantData($restaurant)
{
  $formattedData = [
    'name' => $restaurant['name'] ?? 'Unknown',
    'borough' => $restaurant['borough'] ?? 'Unknown',
    'cuisine' => $restaurant['cuisine'] ?? 'Unknown',
    'address' => formatAddress($restaurant['address'] ?? []),
    'latest_grade' => getLatestGrade($restaurant['grades'] ?? [])
  ];

  return $formattedData;
}

function formatAddress($address)
{
  if (empty($address)) {
    return 'No address available';
  }

  $addressParts = [];
  if (!empty($address['building'])) {
    $addressParts[] = $address['building'];
  }
  if (!empty($address['street'])) {
    $addressParts[] = $address['street'];
  }
  if (!empty($address['zipcode'])) {
    $addressParts[] = 'ZIP: ' . $address['zipcode'];
  }

  return implode(', ', $addressParts);
}

function getLatestGrade($grades)
{
  if (empty($grades)) {
    return ['grade' => 'N/A', 'score' => 'N/A', 'date' => 'N/A'];
  }

  $gradesArray = [];
  foreach ($grades as $grade) {
    $gradesArray[] = $grade;
  }

  if (empty($gradesArray)) {
    return ['grade' => 'N/A', 'score' => 'N/A', 'date' => 'N/A'];
  }

  // Sort grades by date (newest first)
  usort($gradesArray, function ($a, $b) {
    if (!isset($a['date']) || !isset($b['date'])) {
      return 0;
    }
    return $b['date']->toDateTime()->getTimestamp() - $a['date']->toDateTime()->getTimestamp();
  });

  // Return the latest grade
  $latestGrade = $gradesArray[0];

  return [
    'grade' => $latestGrade['grade'] ?? 'N/A',
    'score' => $latestGrade['score'] ?? 'N/A',
    'date' => isset($latestGrade['date']) ? $latestGrade['date']->toDateTime()->format('Y-m-d') : 'N/A'
  ];
}
