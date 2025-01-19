<?php
// views/dashboard/keywords/keywords.php
include 'views/dashboard/partials/header.php';
?>

<div class="screen flex flex-col p-4">

    <div class="p-4 md:p-8 shrink">
        <h1 class="text-3xl md:text-6xl font-bold mb-2 md:mb-4">Keywords</h1>
        <p class="text-xl">See how your keywords are performing</p>
    </div>
    
    <div class="p-4">
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md">

            <div class="flex flex-col xl:flex-row">
                <div>
                    <h2 class="text-2xl font-medium">Campaigns(Database)</h2>
                    <p class="mb-4">View your campaigns</p>
                    <div id="campaign-term-table"></div>
                </div>

                <div>
                    <h2 class="text-2xl font-medium">Ad Group (Database)</h2>
                    <p class="mb-4">View your ad groups</p>
                    <div id="ad-group-table"></div>
                </div>

                <div>
                    <h2 class="text-2xl font-medium">Keyword Metrics (Database)</h2>
                    <p class="mb-4">View your keyword metrics</p>
                    <div id="keywords-metrics-table"></div>
                </div>


                <div>
                    <h2 class="text-2xl font-medium">Keywords (Database)</h2>
                    <p class="mb-4">View your search terms</p>
                    <div id="keywords-table"></div>
                </div>

                <div>
                    <h2 class="text-2xl font-medium">Search Terms (Database)</h2>
                    <p class="mb-4">View your search terms</p>
                    <div id="search-terms-table"></div>
                </div>

                
            </div>

            <script>

                // Convert PHP data to a JavaScript object
                campaignsData = <?php echo $campaignsJsonData; ?>;
                // Create Tabulator on DOM element with id "search-term-table"
                new Tabulator("#campaign-term-table", {
                    data: campaignsData, // assign data to table
                    autoColumns: true, // create columns from data field names
                    height: "75vh",

                    // Enable pagination
                    pagination: "local",
                    paginationSize: 100, // number of rows per page
                });

                
                adGroupsData = <?php echo $adGroupsJsonData; ?>;
                new Tabulator("#ad-group-table", {
                    data: adGroupsData, // assign data to table
                    autoColumns: true, // create columns from data field names
                    height: "75vh",

                    // Enable pagination
                    pagination: "local",
                    paginationSize: 100, // number of rows per page
                });

                // Convert PHP data to a JavaScript object
                var keywordsdata = <?php echo $keywordsJsonData; ?>;
                // Create Tabulator on DOM element with id "search-term-table"
                new Tabulator("#keywords-table", {
                    data: keywordsdata, // assign data to table
                    autoColumns: true, // create columns from data field names
                    height: "75vh",

                    // Enable pagination
                    pagination: "local",
                    paginationSize: 100, // number of rows per page
                });

                keywordsMetricsData = <?php echo $keywordsMetricsJsonData; ?>;
                new Tabulator("#keywords-metrics-table", {
                    data: keywordsMetricsData, // assign data to table
                    autoColumns: true, // create columns from data field names
                    height: "75vh",

                    // Enable pagination
                    pagination: "local",
                    paginationSize: 100, // number of rows per page
                });

                searchTermsData = <?php echo $searchTermsJsonData; ?>;
                new Tabulator("#search-terms-table", {
                    data: searchTermsData, // assign data to table
                    autoColumns: true, // create columns from data field names
                    height: "75vh",

                    // Enable pagination
                    pagination: "local",
                    paginationSize: 100, // number of rows per page
                });
                

            </script>
        </div>

    </div>

</div>

<?php include 'views/dashboard/partials/footer.php'; ?>