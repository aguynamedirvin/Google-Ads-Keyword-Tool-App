<?php
// controllers/DashboardController.php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/CampaignDataProcessor.php';

class DashboardController {
    public function index() {
        // Check if user is logged in
        $user = new User();

        if (!$user->isLoggedIn()) {
            header('Location: /login');
            exit();
        }

        $processor = new CampaignDataProcessor();

        // Get metrics for a specific user
        $metrics = $processor->getMetrics($user->id());

        // Get metrics for a specific campaign of the user
        //$campaignMetrics = $processor->getMetrics($userId, ['campaign' => 'Campaign Name']);

        // Get metrics for a specific ad group of a campaign
        //$adGroupMetrics = $processor->getMetrics($userId, ['campaign' => 'Campaign Name', 'adGroup' => 'Ad Group Name']);

        // Get metrics for a specific keyword
        //$keywordMetrics = $processor->getMetrics($userId, ['keyword' => 'Keyword']);

        // Render the dashboard view
        include 'views/dashboard/dashboard.php';
    }
}
