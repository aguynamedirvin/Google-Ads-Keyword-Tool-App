<?php
// controllers/SettingsController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Keywords.php';
require_once __DIR__ . '/../models/CampaignDataProcessor.php';

class AppKeywordsController {


    public function indexView() {
        // Check if user is logged in
        $user = new User();
        if (!$user->isLoggedIn()) {
            header('Location: /login');
            exit();
        }

        
        $data = new Keywords();
        $retriveKeywords = $data->retrieveKeywords();

        if ($retriveKeywords) {
            $jsonData = json_encode($retriveKeywords);
        } else {
            echo "Failed to get data from the database.";
        }


        $retriveCampaigns = $data->retrieveCampaigns();
        
        if ($retriveCampaigns) {
            $campaignsJsonData = json_encode($retriveCampaigns);
        } else {
            echo "Failed to get data from the database.";
        }

        $retrieveAdGroups = $data->retrieveAdGroups();
        if ($retrieveAdGroups) {
            $adGroupsJsonData = json_encode($retrieveAdGroups);
        } else {
            echo "Failed to get data from the database.";
        }

        //$uploadKeywords = new Keywords();
        //$filePath = 'uploads/keywords.csv';
        /**$getSearchTermData = $uploadKeywords->getSearchTermData($filePath, [
            // List all columns that you want to retrieve
            'matchType', 'addedExcluded', 'keyword', 'conversions', 
            'impressions', 'averageCPC', 'clickThroughRate', 'conversionRate'
        ]); // Get the data from the database
        
        if ($getSearchTermData) {
            //print_r($getSearchTermData);
            $jsonData = json_encode($getSearchTermData);
        } else {
            echo "Failed to get data from the database.";
        }**/

        // Render the dashboard view
        include 'views/dashboard/keywords/keywords.php';

    }

    // Upload keywords view
    public function uploadView() {

        // Check if user is logged in
        $user = new User();
        if (!$user->isLoggedIn()) {
            header('Location: /login');
            exit();
        }

        // Check if the request method is POST and the file is set
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];

            $keywords = new Keywords();

            // Upload the file and store in server
            $uploadKeywords = new File();
            $fileUpload = $uploadKeywords->upload($file); // Upload the file

            if ($fileUpload['success']) {
                // Get the file path
                $filePath = $fileUpload['data']['destination'];

                // Process the file
                $processedData = $keywords->process($filePath);

                // Get search term data from new processed CSV file
                $getSearchTermData = $keywords->getSearchTermData($filePath,[
                    // List all columns that you want to retrieve
                    'searchTerm', 'campaignName', 'adGroupName', 'matchType', 'addedExcluded', 'keyword', 'conversions', 
                    'impressions', 'averageCPC', 'clickThroughRate', 'conversionRate', 'costPerConversion'
                ]); 

                // Store all data at once
                $storeCampaignData = new CampaignDataProcessor();
                $storeCampaignData->storeAllAtOnce($user->id(), $getSearchTermData);


                $getCampaignData = $keywords->getSearchTermData($filePath,[
                    // List all columns that you want to retrieve
                    'searchTerm', 'campaignName', 'adGroupName', 'matchType', 'addedExcluded', 'keyword', 'conversions', 
                    'impressions', 'averageCPC', 'clickThroughRate', 'conversionRate', 'costPerConversion'
                ]);

                // If able to get data, store to datase
                if ($getSearchTermData) {
                    //print_r($getSearchTermData);
                    //$keywords->storeKeywords($getSearchTermData);

                    $jsonData = json_encode($getSearchTermData);
                } else {
                    echo "Failed to get data from the database.";
                }
            } else {
                echo $fileUpload['message'];
            }

            
        }

        // Render the dashboard view
        include 'views/dashboard/keywords/uploadKeywords.php';
    }
}
