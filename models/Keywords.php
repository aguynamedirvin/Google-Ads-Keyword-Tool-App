<?php
// models/UploadKeywords.php

class Keywords {

    private $db;

    public function __construct($db = null) {
        if ($db === null) {
            // Assuming the Database class's getConnection() method returns a PDO connection
            $this->db = (new Database())->getConnection();
        } else {
            $this->db = $db;
        }
    }


    /**
     * Process the uploaded file.
     * 
     * This function reads a CSV file, removes unnecessary rows, reorders the columns, and stores the processed data in a new CSV file.
     * It performs the following steps:
     * 1. Checks if the file exists.
     * 2. Reads the file into an array.
     * 3. Removes the first two and last three rows.
     * 4. Prints the headers row for debugging purposes.
     * 5. Defines the correct order of columns.
     * 6. Checks if the required columns exist.
     * 7. Reorders the data and inserts blank columns as necessary.
     * 8. Writes the processed data to a new CSV file.
     * 9. Returns the reordered data.
     *
     * @param string $file The path to the uploaded file.
     * @return array The reordered data.
     */
    public function process($file) {
        if (!file_exists($file)) {
            echo "File does not exist.";
            exit;
        }

        // Read file into array
        $file = fopen($file, "r");
        $data = [];
        
        while (($row = fgetcsv($file)) !== FALSE) {
            $data[] = $row;
        }
        fclose($file);

        // Check if file is empty
        if (empty($data)) {
            echo "File is empty.";
        }

        // Remove first two and last three rows
        array_shift($data); // Remove first row
        array_shift($data); // Remove second row
        array_splice($data, -4); // Remove last three rows

        // Print headers and stop the script for debugging
        //print_r($data[0]); // Print the headers row
        //exit; // Stop the script execution

        // Define the correct order of columns
        $correctOrder = [
            'Search term' => 'searchTerm',
            'Match type' => 'matchType',
            'Added/Excluded' => 'addedExcluded',
            'Campaign' => 'campaignName',
            'Ad group' => 'adGroupName',
            'Keyword' => 'keyword',
            'Currency code' => 'currencyCode',
            'Cost' => 'cost',
            'Impr.' => 'impressions',
            'Clicks' => 'clicks',
            'CTR' => 'clickThroughRate',
            'Avg. CPC' => 'averageCPC',
            'Conversions' => 'conversions',
            'Cost / conv.' => 'costPerConversion',
            'Conv. rate' => 'conversionRate',
            //'Booked' => 'booked',
            //'Cost / Booked' => 'costPerBooked',
            //'Sales' => 'sales',
            //'Cost / Sale' => 'costPerSale',
            //'Calls' => 'calls'
        ];

        // Find out the current order and map it to the correct order
        $header = array_map('trim', $data[0]);
        $columnOrder = array_flip($header);

        // Check if required columns exist
        $requiredColumns = ['Search term', 'Campaign', 'Ad group', 'Cost', 'Conversions', 'Clicks'];
        foreach ($requiredColumns as $requiredColumn) {
            if (!isset($columnOrder[$requiredColumn])) {
                echo "Spreadsheet rejected: missing required column '$requiredColumn'.";
                exit;
            }
        }

        // Reorder and insert blank columns as necessary
        $reorderedData = [];
        $newHeader = array_values($correctOrder); // New header row with renamed columns

        foreach ($data as $rowIndex => $row) {
            if ($rowIndex === 0) { // Skip the old header row
                continue;
            }
            $newRow = [];
            foreach ($correctOrder as $oldColumnName => $newColumnName) {
                $newRow[] = $row[$columnOrder[$oldColumnName]] ?? ''; // Insert blank if column doesn't exist
            }
            $reorderedData[] = $newRow;
        }

        // Write to new CSV file
        if ($file = fopen('uploads/processed.csv', "w")) {
            fputcsv($file, $newHeader); // Write the new header row

            foreach ($reorderedData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
            
            echo "File processed, columns sorted and stored as 'processed.csv'";
        } else {
            echo "Error: Unable to write to file";
        }

        if (!empty($data)) {
            echo "File processed successfully.";
        } else {
            echo "Failed to process file.";
        }

        return $reorderedData;
    }


    /** 
     * Get the search term data from the processed
     * search term CSV file.
     * 
     * @param string $fileName
     * @param array $additionalFields
     * @return array
     */
    public function getSearchTermData($fileName, $additionalFields = []) {
        $fileName = 'uploads/processed.csv';

        if (!file_exists($fileName)) {
            return "Error: File not found.";
        }
    
        $data = [];
        // Default fields to always return
        $defaultFields = ['searchTerm', 'cost', 'clicks'];
        // Combine default fields with additional fields if any
        $allFields = array_unique(array_merge($defaultFields, $additionalFields));
    
        if (($handle = fopen($fileName, "r")) === FALSE) {
            return "Error: Unable to open file.";
        }
    
        // Get the header row
        $headers = fgetcsv($handle);
        if ($headers === FALSE) {
            fclose($handle);
            return "Error: Unable to read the header row.";
        }
    
        // Create an associative array for header index mapping
        $headerIndex = array_flip($headers);
    
        // Filter out only the required fields' indices
        $requiredIndices = array_intersect_key($headerIndex, array_flip($allFields));
    
        while (($row = fgetcsv($handle)) !== FALSE) {
            // Start with a blank array for our row
            $rowData = array_fill_keys($allFields, null);
            
            // Collect only the required data based on the indices
            foreach ($requiredIndices as $fieldName => $index) {
                // If the index does not exist in this row, we skip
                if (!isset($row[$index])) {
                    continue;
                }
                $rowData[$fieldName] = $row[$index];
            }
    
            // Add the row to our data array
            $data[] = $rowData;
        }
        fclose($handle);
    
        return $data;
    }

    /**
     * Store a new campaign.
     * 
     * @param int $userId The ID of the user creating the campaign.
     * @param string $campaignName The name of the campaign.
     * @return int The ID of the created campaign.
     */
    public function storeCampaigns($campaignData, $userId) {
        $sql = "INSERT INTO campaigns (user_id, campaign_name) VALUES (:user_id, :campaign_name)";
        $stmt = $this->db->prepare($sql);

        $mergedCampaigns = [];
        foreach ($campaignData as $row) {
            $campaignName = $row['campaignName'];

            // Merge campaign names
            if (!in_array($campaignName, $mergedCampaigns)) {
                $mergedCampaigns[] = $campaignName;
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':campaign_name', $campaignName);
                $stmt->execute();
            }
        }
        
        echo "Campaign data stored successfully.";
        return ['success' => true, 'message' => 'Campaign data stored successfully.'];
        //return $this->db->lastInsertId();
    }

    /**
     * Store a new campaign and new ad groups.
     * 
     * @param int $userId The ID of the user creating the campaign.
     * @paraam array $campaignData The campaign data.
     */
    public function storeAdGroups($adGroupData, $userId) {
        /** 
         * 
         * Temporary drop tables for testing
         * 
         * */
        // Disable foreign key checks
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 0');

        // Truncate the table
        $sql = "TRUNCATE TABLE adgroups";
        $this->db->exec($sql);

        $sql = "TRUNCATE TABLE campaigns";
        $this->db->exec($sql);

        // Enable foreign key checks
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');

        // Find the campaigns of the userId and match ad groups to the campaigns
        $sql = "SELECT * FROM campaigns WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $databaseCampaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /** 
         * Creates a structured array that groups the data by 
         * campaignName and then lists the adGroupName under each campaign. 
         * 
         * 1. Initialize a new array: This will be used to store the organized data.
         * 2. Iterate through the provided array: Loop through each element of your existing array.
         * 3. Check and group by campaignName: For each element, check if its campaignName already exists as 
         *    a key in your new array. If not, create a new key with this campaignName.
         * 4. Add adGroupName to the respective campaignName: Under each campaignName, add the adGroupName if it's not already present.
         * 5. Result: You will end up with an array where each campaignName is a key, and the value is a list of unique adGroupNames associated with that campaign.
         * 
         */
        $organizedArray = [];
        foreach ($adGroupData as $adGroup) {
            $campaignName = $adGroup['campaignName'];
            $adGroupName = $adGroup['adGroupName'];

            // Initialize an array for this campaign if it doesn't exist
            if (!isset($organizedArray[$campaignName])) {
                $organizedArray[$campaignName] = [];
            }

            // Add the ad group if it's not already in the array
            if (!in_array($adGroupName, $organizedArray[$campaignName])) {
                $organizedArray[$campaignName][] = $adGroupName;
            }

        }

        // Begin transaction
        $this->db->beginTransaction();

        try {
            foreach ($organizedArray as $campaignName => $adGroups) {
                // Check if the campaign exists in the database
                $campaignId = null;
                foreach ($databaseCampaigns as $dbCampaign) {
                    if ($dbCampaign['campaign_name'] == $campaignName) {
                        $campaignId = $dbCampaign['campaign_id'];
                        break;
                    }
                }

                // If campaign does not exist, insert it
                if (!$campaignId) {
                    $insertCampaignSql = "INSERT INTO campaigns (user_id, campaign_name) VALUES (:user_id, :campaign_name)";
                    $stmt = $this->db->prepare($insertCampaignSql);
                    $stmt->execute([':user_id' => $userId, ':campaign_name' => $campaignName]);
                    $campaignId = $this->db->lastInsertId();
                }

                // Insert ad groups
                foreach ($adGroups as $adGroupName) {
                    $insertAdGroupSql = "INSERT INTO adgroups (campaign_id, adgroup_name) VALUES (:campaign_id, :adgroup_name)";
                    $stmt = $this->db->prepare($insertAdGroupSql);
                    $stmt->execute([':campaign_id' => $campaignId, ':adgroup_name' => $adGroupName]);
                }
            }

            // Commit transaction
            $this->db->commit();
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            throw $e;
        }

    }

    /**
     * Store the processed data in the database.
     * 
     * @param array $processedData The processed data.
     * @return void
     */
    public function storeKeywords($processedData) {

        if (empty($processedData)) {
            return ['error' => true, 'message' => 'No data to store.'];
            exit;
            //echo "No data to store.";
        }

        /** 
         * 
         * Temporary drop tables for testing
         * 
         * */
        // Disable foreign key checks
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 0');

        // Truncate the table
        $sql = "TRUNCATE TABLE keyworddata";
        $this->db->exec($sql);

        // Enable foreign key checks
        $this->db->exec('SET FOREIGN_KEY_CHECKS = 1');

        // Prepare the SQL statement
        $sql = "INSERT INTO keyworddata (keyword, impressions, clicks, conversions, cost) VALUES (:keyword, :impressions, :clicks, :conversions, :cost)";
        $stmt = $this->db->prepare($sql);

        // Insert only the first 10 processed data rows into the database
        $count = 0;
        foreach ($processedData as $row) {
            if ($count >= 10000) {
                break;
            }

            $keyword = $row['searchTerm'];
            $impressions = $row['impressions'];
            $clicks = $row['clicks'];
            $conversions = $row['conversions'];
            $cost = $row['cost'];

            $stmt->bindParam(':keyword', $keyword);
            $stmt->bindParam(':impressions', $impressions);
            $stmt->bindParam(':clicks', $clicks);
            $stmt->bindParam(':conversions', $conversions);
            $stmt->bindParam(':cost', $cost);
            $stmt->execute();

            $count++;
        }

        echo "Data stored successfully.";
        return ['success' => true, 'message' => 'Data stored successfully.'];
        
    }

    function retrieveCampaigns() {
        $sql = "SELECT * FROM campaigns";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            echo "Failed to retrieve data.";
            return false;
        }
    }

    function retrieveAdGroups() {
        $sql = "SELECT * FROM adgroups";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            echo "Failed to retrieve data.";
            return false;
        }
    }

    /**
     * Retrieve the keywords from the database.
     * 
     * @return array|bool The keywords or false on failure.
     */
    function retrieveKeywords() {
        $sql = "SELECT * FROM keyworddata";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            echo "Failed to retrieve data.";
            return false;
        }
    }


}