<?php

namespace App\Models;

use App\Interfaces\FileParserInterface;

class XMLParser implements FileParserInterface
{
    /**
     * Parse the XML file and return data as an array
     * 
     * @param string $file The file path
     * @return array The parsed data
     */
    public function parse($file)
    {
        // Load the XML file
        $xml = simplexml_load_file($file);
        // Encode the XML data to JSON
        $json = json_encode($xml);
        // Decode the JSON data to an associative array and return 'item' element
        return json_decode($json, true)['item'];
    }
}
