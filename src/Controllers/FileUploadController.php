<?php

namespace App\Controllers;

use App\Models\CSVParser;
use App\Models\JSONParser;
use App\Models\XMLParser;

class FileUploadController
{
    private $config;
    private $mockParsers = [];

    /**
     * Constructor to initialize configuration
     * 
     * @param array $config Configuration array
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Handle file upload and parsing
     * 
     * @param array $file Uploaded file information
     * @return array Result containing data or error message
     */
    public function upload($file)
    {
        // Check if the file is uploaded and there are no upload errors
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'No file uploaded or an upload error occurred. Please upload a valid file.'];
        }

        // Check the file size
        if ($file['size'] > $this->config['max_file_size']) {
            return [
                'error' => [
                    'The file is too large.',
                    'Please upload a file smaller than 1 MB.'
                ]
            ];
        }

        // Get the file extension
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION); 

        if (!in_array($extension, $this->config['allowed_formats'])) {
            // Check if the file format is allowed

            return ['error' => 'Unsupported file format'];
        }
        // Get the appropriate parser for the file format
        $parser = $this->getParser($extension); 

        if ($parser === null) {
            // If no parser is found, return an error
            return ['error' => 'No parser available for this file format'];
        }
        // Parse the file and return the result
        $data = $parser->parse($file['tmp_name']); 

        return ['data' => $data];
    }

    /**
     * Get the appropriate parser based on the file extension
     * 
     * @param string $extension File extension
     * @return mixed Parser object or null if no parser is found
     */
    private function getParser($extension)
    {
        if (isset($this->mockParsers[$extension])) {
            return $this->mockParsers[$extension];
        }

        switch ($extension) {
            case 'csv':
                return new CSVParser();
            case 'json':
                return new JSONParser();
            case 'xml':
                return new XMLParser();
            default:
                return null;
        }
    }
    public function setMockParser($extension, $parser)
    {
        $this->mockParsers[$extension] = $parser;
    }
}
