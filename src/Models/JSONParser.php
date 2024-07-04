<?php

namespace App\Models;

use App\Interfaces\FileParserInterface;

class JSONParser implements FileParserInterface
{
    /**
     * Parse the JSON file and return data as an array
     * 
     * @param string $file The file path
     * @return array The parsed data
     */
    public function parse($file)
    {
        // Read the file contents
        $data = file_get_contents($file);
        // Decode the JSON data to an associative array and return the data
        return json_decode($data, true);
    }
}
