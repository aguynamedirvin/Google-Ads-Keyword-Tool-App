<?php

require_once 'config.php';

class Installer {
    private $dbConnection;

    public function __construct() {
        $this->connectToServer();
        $this->createDatabase();
        $this->dropExistingTables();
        $this->createTables();
    }

    private function connectToServer() {
        try {
            $dsn = 'mysql:host=' . Config::DB_HOST;
            $this->dbConnection = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function createDatabase() {
        try {
            $sql = "CREATE DATABASE IF NOT EXISTS " . Config::DB_NAME;
            $this->dbConnection->exec($sql);
            echo "Database created successfully or already exists.\n";
        } catch (PDOException $e) {
            die("Database creation failed: " . $e->getMessage());
        }
    }

    private function dropExistingTables() {
        $this->dropTable('financialmetrics');
        $this->dropTable('keyworddata');
        $this->dropTable('adgroups');
        $this->dropTable('campaigns');
        $this->dropTable('searchterms');
        //$this->dropTable('users');
    }

    private function createTables() {
        try {
            $this->dbConnection->exec("USE " . Config::DB_NAME);
    
            // Users Table
            $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
                user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                username VARCHAR(30) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                photo_url VARCHAR(255),
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $this->dbConnection->exec($sqlUsers);
            echo "Table 'users' created successfully.\n";
    
            // Create Campaigns Table
            $sqlCampaigns = "CREATE TABLE IF NOT EXISTS campaigns (
                campaign_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED,
                campaign_name VARCHAR(255),
                FOREIGN KEY (user_id) REFERENCES users(user_id)
            )";
            $createCampaignTables = $this->dbConnection->exec($sqlCampaigns);
            if ($createCampaignTables) {
                echo "Table 'campaigns' created successfully.\n";
            }

            // Create AdGroups Table
            $sqlAdGroups = "CREATE TABLE IF NOT EXISTS adgroups (
                adgroup_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                campaign_id INT UNSIGNED,
                adgroup_name VARCHAR(255),
                FOREIGN KEY (campaign_id) REFERENCES campaigns(campaign_id)
            )";
            $this->dbConnection->exec($sqlAdGroups);
            echo "Table 'ad groups' created successfully.\n";

            // Create KeywordData Table
            $sqlKeywordData = "CREATE TABLE IF NOT EXISTS keyworddata (
                keyworddata_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                adgroup_id INT UNSIGNED,
                keyword VARCHAR(255) NOT NULL,
                impressions INT UNSIGNED,
                clicks INT UNSIGNED,
                conversions INT UNSIGNED,
                cost DECIMAL(10, 2),
                FOREIGN KEY (adgroup_id) REFERENCES adgroups(adgroup_id)
                )";
            $this->dbConnection->exec($sqlKeywordData);
            echo "Table 'keyword data' created successfully.\n";

            // Create SearchTerms Table
            $sqlSearchTermData = "CREATE TABLE IF NOT EXISTS searchterms (
                searchterm_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                keyworddata_id INT UNSIGNED,
                search_term VARCHAR(255) NOT NULL,
                match_type VARCHAR(50),
                impressions INT UNSIGNED,
                clicks INT UNSIGNED,
                conversions INT UNSIGNED,
                cost DECIMAL(10, 2),
                FOREIGN KEY (keyworddata_id) REFERENCES keyworddata(keyworddata_id)
                )";
            $this->dbConnection->exec($sqlSearchTermData);
            echo "Table 'searchTerms data' created successfully.\n";

            // Create FinancialMetrics Table
            $sqlFinancialMetrics = "CREATE TABLE IF NOT EXISTS financialmetrics (
                metric_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                keyworddata_id INT UNSIGNED,
                cost_per_click DECIMAL(10, 2),
                conversion_rate DECIMAL(5, 2),
                cost_per_conversion DECIMAL(10, 2),
                revenue DECIMAL(10, 2),
                FOREIGN KEY (keyworddata_id) REFERENCES keyworddata(keyworddata_id)
                )";
            $this->dbConnection->exec($sqlFinancialMetrics);
            echo "Table 'financialmetrics' created successfully.\n";
    
        } catch (PDOException $e) {
            die("Table creation failed: " . $e->getMessage());
        }
    }

    private function dropTable($tableName) {
        try {
            $this->dbConnection->exec("USE " . Config::DB_NAME);
            $sql = "DROP TABLE IF EXISTS $tableName";
            $this->dbConnection->exec($sql);
            echo "Table '$tableName' dropped successfully.\n";
        } catch (PDOException $e) {
            die("Table drop failed: " . $e->getMessage());
        }
    }

}

new Installer();
