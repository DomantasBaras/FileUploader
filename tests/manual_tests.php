<?php

require_once '../src/Interfaces/FileParserInterface.php';
require_once '../src/Controllers/FileUploadController.php';
require_once '../src/Models/CSVParser.php';
require_once '../src/Models/JSONParser.php';
require_once '../src/Models/XMLParser.php';

use App\Controllers\FileUploadController;

$config = [
    'allowed_formats' => ['csv', 'json', 'xml'],
    'max_file_size' => 1048576, // 1 MB
];

$controller = new FileUploadController($config);

function testNoFileUploaded($controller)
{
    $result = $controller->upload(null);
    assert(isset($result['error']), 'Test No File Uploaded: Error should be set');
    assert($result['error'] === 'No file uploaded or an upload error occurred. Please upload a valid file.', 'Test No File Uploaded: Error message mismatch');
    echo "testNoFileUploaded passed.\n";
}

function testFileTooLarge($controller)
{
    $file = [
        'name' => 'largefile.csv',
        'tmp_name' => '/path/to/largefile.csv',
        'size' => 2097152, // 2 MB
        'error' => UPLOAD_ERR_OK,
    ];

    $result = $controller->upload($file);
    echo "Received error message: " . json_encode($result['error']) . "\n";
    assert(isset($result['error']), 'Test File Too Large: Error should be set');
    assert(
        $result['error'] === ['The file is too large.', 'Please upload a file smaller than 1 MB.'],
        'Test File Too Large: Error message mismatch'
    );
    echo "testFileTooLarge passed.\n";
}

function testUnsupportedFileFormat($controller)
{
    $file = [
        'name' => 'file.txt',
        'tmp_name' => '/path/to/file.txt',
        'size' => 512,
        'error' => UPLOAD_ERR_OK,
    ];

    $result = $controller->upload($file);
    assert(isset($result['error']), 'Test Unsupported File Format: Error should be set');
    assert($result['error'] === 'Unsupported file format', 'Test Unsupported File Format: Error message mismatch');
    echo "testUnsupportedFileFormat passed.\n";
}

function testSuccessfulUpload($controller)
{
    $file = [
        'name' => 'file.csv',
        'tmp_name' => '/path/to/file.csv',
        'size' => 512,
        'error' => UPLOAD_ERR_OK,
    ];

    // Mock the parser
    $mockParser = new class {
        public function parse($file)
        {
            return ['data' => 'parsed'];
        }
    };

    // Set the mock parser
    $controller->setMockParser('csv', $mockParser);

    $result = $controller->upload($file);
    assert(isset($result['data']), 'Test Successful Upload: Data should be set');
    assert($result['data'] === ['data' => 'parsed'], 'Test Successful Upload: Data mismatch');
    echo "testSuccessfulUpload passed.\n";
}

// Run tests
testNoFileUploaded($controller);
testFileTooLarge($controller);
testUnsupportedFileFormat($controller);
testSuccessfulUpload($controller);
?>