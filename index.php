<?php

/**
 * Receive a request with a tokenId and an image (file)
 * Store the image in the ../ticmarkeplace/images folder
 * Then create a JSON file (metadata for a NFT) with the tokenId and the image path
 * Store the JSON file in the ../ticmarkeplace/tokens folder
 * Also receive a name and a description for the NFT
 *
 * Public path: http://20.115.208.189/ticmarkeplace/
 */

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are allowed',
        'data' => $_SERVER['REQUEST_METHOD']
    ]);
    exit;
}

// Check if the request has a tokenId
if (!isset($_POST['tokenId'])) {
    echo json_encode([
        'success' => false,
        'message' => 'TokenId is required'
    ]);
    exit;
}

// Check if the request has an image
if (!isset($_FILES['image'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Image is required'
    ]);
    exit;
}

// Check if the request has a name
if (!isset($_POST['name'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Name is required'
    ]);
    exit;
}

// Check if the request has a description
if (!isset($_POST['description'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Description is required'
    ]);
    exit;
}

// Extract the file extension from the uploaded image
$imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

// Rename the image to the token name with the extracted extension
$imageName = $_POST['tokenId'] . '.' . $imageExtension;
$imagePath = __DIR__ . '/../images/' . $imageName;
move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

$imageURL = 'http://20.115.208.189/ticmarkeplace/images/' . $imageName;

// Create the JSON file (metadata for a NFT) with the tokenId and the image path
$json = json_encode([
    'tokenId' => $_POST['tokenId'],
    'image' => $imageURL,
    'name' => $_POST['name'],
    'description' => $_POST['description']
]);
$jsonPath = __DIR__  . '/../tokens/' . $_POST['tokenId'] . '.json';
file_put_contents($jsonPath, $json);

// Send a response
echo json_encode([
    'success' => true,
    'message' => 'NFT created successfully'
]);
