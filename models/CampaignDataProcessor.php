<?php
// Path: models/CampaignDataProcessor.php

class CampaignDataProcessor {

    private $dbConnection;

    public function __construct() {
        $this->dbConnection = (new Database())->getConnection();
    }

    private function getUserId($userIdentifier) {
        $sql = "SELECT user_id FROM users WHERE user_id = ?";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute([$userIdentifier]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo $row['user_id'];
        return $row ? $row['user_id'] : null;
    }

    private function checkAndInsertCampaign($campaignName, $userId) {
        // Check if campaign already exists
        $sqlCheckCampaign = "SELECT campaign_id FROM campaigns WHERE campaign_name = ? AND user_id = ?";
        $stmt = $this->dbConnection->prepare($sqlCheckCampaign);
        $stmt->execute([$campaignName, $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row['campaign_id'];
        } else {
            $sqlInsertCampaign = "INSERT INTO campaigns (user_id, campaign_name) VALUES (?, ?)";
            $stmt = $this->dbConnection->prepare($sqlInsertCampaign);
            $stmt->execute([$userId, $campaignName]);
            return $this->dbConnection->lastInsertId();
        }
    }

    private function checkAndInsertAdGroup($adGroupName, $campaignId, $userId) {
        // Check if ad group already exists
        $sqlCheckAdGroup = "SELECT adgroup_id FROM adgroups WHERE adgroup_name = ? AND campaign_id = ?";
        $stmt = $this->dbConnection->prepare($sqlCheckAdGroup);
        $stmt->execute([$adGroupName, $campaignId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row['adgroup_id'];
        } else {
            $sqlInsertAdGroup = "INSERT INTO adgroups (campaign_id, adgroup_name) VALUES (?, ?)";
            $stmt = $this->dbConnection->prepare($sqlInsertAdGroup);
            $stmt->execute([$campaignId, $adGroupName]);
            return $this->dbConnection->lastInsertId();
        }
    }

    private function insertKeywordDataAndFinancialMetrics($data, $adGroupId) {
        // Check if keyword already exists
        $sqlCheckKeyword = "SELECT keyworddata_id FROM keyworddata WHERE keyword = ? AND adgroup_id = ?";
        $stmt = $this->dbConnection->prepare($sqlCheckKeyword);
        $stmt->execute([$data['keyword'], $adGroupId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            return $row['keyworddata_id'];
        } else {
            // Insert keyword data
            $sqlInsertKeyword = "INSERT INTO keyworddata (adgroup_id, keyword, impressions, clicks, conversions, cost) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->dbConnection->prepare($sqlInsertKeyword);
            $stmt->execute([$adGroupId, $data['keyword'], $data['impressions'], $data['clicks'], $data['conversions'], $data['cost']]);
            
            $keywordDataId = $this->dbConnection->lastInsertId();
    
            // Insert financial metrics
            /*$sqlInsertMetrics = "INSERT INTO financialmetrics (keyworddata_id, cost_per_click, conversion_rate, cost_per_conversion, revenue) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->dbConnection->prepare($sqlInsertMetrics);
            $stmt->execute([$keywordDataId, $data['averageCPC'], $data['conversionRate'], $data['costPerConversion'], '0']); // Ensure these keys exist in your $data array*/

            return $keywordDataId;
        }
       
        
    }

    private function insertSearchTerms($data, $keywordId) {
        // Check if search term already exists associated with the keyword
        $sqlCheckSearchTerm = "SELECT searchterm_id FROM searchterms WHERE search_term = ? AND match_type = ? AND keyworddata_id = ?";
        $stmt = $this->dbConnection->prepare($sqlCheckSearchTerm);
        $stmt->execute([$data['searchTerm'], $data['matchType'], $keywordId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            // Insert search term data
            $sqlInsertSearchTerm = "INSERT INTO searchterms (keyworddata_id, search_term, match_type, impressions, clicks, conversions, cost) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->dbConnection->prepare($sqlInsertSearchTerm);
            $stmt->execute([$keywordId, $data['searchTerm'], $data['matchType'], $data['impressions'], $data['clicks'], $data['conversions'], $data['cost']]);

            // Update keyword data/metrics with aggregated values
            $sqlUpdateKeywordData = "UPDATE keyworddata SET cost = cost + ?, impressions = impressions + ?, clicks = clicks + ?, conversions = conversions + ? WHERE keyworddata_id = ?";
            $stmt = $this->dbConnection->prepare($sqlUpdateKeywordData);
            $stmt->execute([$data['cost'], $data['impressions'], $data['clicks'], $data['conversions'],  $keywordId]);
        }
    }

    public function storeAllAtOnce($userIdentifier, $dataArray) {
        /**
         * 
         * DELETE TABLES FOR TESTING ONLY 
         * 
         * */
        // Disable foreign key checks
        $this->dbConnection->exec('SET FOREIGN_KEY_CHECKS = 0');
        $sql = "TRUNCATE TABLE adgroups";
        $this->dbConnection->exec($sql);
        $sql = "TRUNCATE TABLE campaigns";
        $this->dbConnection->exec($sql);
        $sql = "TRUNCATE TABLE keyworddata";
        $this->dbConnection->exec($sql);
        $sql = "TRUNCATE TABLE financialmetrics";
        $this->dbConnection->exec($sql);
        $sql = "TRUNCATE TABLE searchterms";
        $this->dbConnection->exec($sql);
        $this->dbConnection->exec('SET FOREIGN_KEY_CHECKS = 1');

        $userId = $this->getUserId($userIdentifier);
    
        foreach ($dataArray as $data) {
            $campaignId = $this->checkAndInsertCampaign($data['campaignName'], $userId);
            $adGroupId = $this->checkAndInsertAdGroup($data['adGroupName'], $campaignId, $userId);
            $keywordId = $this->insertKeywordDataAndFinancialMetrics($data, $adGroupId);
            $this->insertSearchTerms($data, $keywordId);
        }
    }



    public function getMetrics($userId, $filters = []) {
        $baseQuery = "SELECT 
        
                        SUM(kd.cost) AS totalCost, 
                        AVG(fm.cost_per_click) AS avgCPC,
                        AVG(fm.conversion_rate) AS avgConversionRate,
                        AVG(fm.cost_per_conversion) AS avgCostPerConversion,
                        SUM(kd.conversions) AS totalConversions,
                        SUM(kd.clicks) AS totalClicks,
                        SUM(kd.impressions) AS totalImpressions,

                        SUM(kd.cost) / SUM(kd.conversions) AS avgCostPerConversion,
                        SUM(kd.clicks) / SUM(kd.conversions) AS avgConversionRate
                      
                      FROM keyworddata kd
                      INNER JOIN adgroups ag ON kd.adgroup_id = ag.adgroup_id
                      INNER JOIN campaigns c ON ag.campaign_id = c.campaign_id
                      INNER JOIN financialmetrics fm ON kd.keyworddata_id = fm.keyworddata_id
                      WHERE c.user_id = ?";

        $queryParams = [$userId];
        $additionalConditions = [];

        if (!empty($filters['campaign'])) {
            $additionalConditions[] = 'c.campaign_name = ?';
            $queryParams[] = $filters['campaign'];
        }

        if (!empty($filters['adGroup'])) {
            $additionalConditions[] = 'ag.adgroup_name = ?';
            $queryParams[] = $filters['adGroup'];
        }

        if (!empty($filters['keyword'])) {
            $additionalConditions[] = 'kd.keyword = ?';
            $queryParams[] = $filters['keyword'];
        }

        if (count($additionalConditions) > 0) {
            $baseQuery .= ' AND ' . implode(' AND ', $additionalConditions);
        }

        $stmt = $this->dbConnection->prepare($baseQuery);
        $stmt->execute($queryParams);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
