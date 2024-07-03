<?php

// Require the autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\FileUploadController;

// Load configuration
$config = require __DIR__ . '/../config/config.php';

// Initialize the controller with configuration
$controller = new FileUploadController($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Handle file upload
    $result = $controller->upload($_FILES['file']);
    if (isset($result['error'])) {
        // If there is an error, set the error message
        $error = $result['error'];
    } else {
        // If successful, set the data
        $data = $result['data'];
    }
}

// Load the view
require_once __DIR__ . '/../src/Views/form.php';
