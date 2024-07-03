<?php

namespace App\Models;

class CSVParser
{
    /**
     * Parse the CSV file and return data as an array
     * 
     * @param string $file The file path
     * @return array The parsed data
     */
    public function parse($file)
    {
        $data = [];
        if (($handle = fopen($file, "r")) !== FALSE) {
            // Get the header row
            $header = fgetcsv($handle, 1000, ",");
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Combine the header and row values into an associative array
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}
