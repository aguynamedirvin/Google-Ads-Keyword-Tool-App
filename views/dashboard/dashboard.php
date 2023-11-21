<?php
// views/dashboard/dashboard.php
include 'partials/header.php';
?>

<main class="app container mx-auto">

    <div class="screen flex flex-col md:flex-row">

            <div class="p-4 md:p-8 shrink">
                <h1 class="text-5xl font-bold mb-3">At a glance</h1>
                <p class="text-xl">See how your campaigns are performing</p>
            </div>

            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 md:gap-6 md:grid-cols-3">
                    <div class="flex flex-col justify-between p-6 rounded-lg bg-green-200">
                        <h3 class="text-xl font-medium text-green-800 mb-3">Total Cost</h3>
                        <p class="text-4xl font-bold text-green-800">$<?php echo isset($metrics['totalCost']) ? $metrics['totalCost'] : '--.--'; ?></p>
                        
                    </div>

                    <div class="flex flex-col justify-between bg-blue-100 p-6 rounded-lg">
                        <h3 class="text-xl font-medium text-blue-800 mb-3">Total Clicks</h3>
                        <p class="text-4xl font-bold text-blue-800"><?php echo isset($metrics['totalClicks']) ? $metrics['totalClicks'] : '--.--'; ?></p>
                    </div>

                    <div class="flex flex-col justify-between bg-red-100 p-6 rounded-lg">
                        <h3 class="text-xl font-medium text-red-800 mb-3">Total Conversions</h3>
                        <p class="text-4xl font-bold text-red-800"><?php echo isset($metrics['totalConversions']) ? $metrics['totalConversions'] : '--.--'; ?></p>
                    </div>

                    <div class="flex flex-col justify-between bg-yellow-100 p-6 rounded-lg overflow-hidden">
                        <h3 class="text-xl font-medium text-yellow-800 mb-3">Avg. Cost / Conv.</h3>
                        <p class="text-4xl font-bold text-yellow-800">$<?php echo isset($metrics['avgCostPerConversion']) ? $metrics['avgCostPerConversion'] : '--.--'?></p>
                    </div>

                    <div class="flex flex-col justify-between bg-violet-100 p-6 rounded-lg overflow-hidden">
                        <h3 class="text-xl font-medium text-violet-800 mb-3">Total Impressions</h3>
                        <p class="text-4xl font-bold text-violet-800"><?php echo isset($metrics['totalImpressions']) ? $metrics['avgCostPerConversion'] : '--.--'?></p>
                    </div>
                    <div class="flex flex-col justify-between bg-neutral-100 p-6 rounded-lg overflow-hidden">
                        <h3 class="text-xl font-medium text-neutral-800 mb-3">Avg. Conv. Rate</h3>
                        <p class="text-4xl font-bold text-neutral-800"><?php echo isset($metrics['avgConversionRate']) ? $metrics['avgConversionRate'] : '--.--'?>%</p>
                    </div>
                <div>
            </div>
    
    </div>
</main>