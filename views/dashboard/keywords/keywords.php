<?php
// views/dashboard/keywords/keywords.php
include 'views/dashboard/partials/header.php';
?>

<div class="screen">
    <div class="p-8">
            <h1 class="text-4xl font-medium">Keywords</h1>
            <p>View your keywords</p>
        </div>
        
        <div class="p-8">
            
            <div class="bg-slate-300 rounded-lg p-4">

                <div class="flex flex-col xl:flex-row">
                    <div>
                        <h2 class="text-2xl font-medium">Search Terms</h2>
                        <p class="mb-4">View your search terms</p>
                        <div id="search-term-table"></div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-medium">Campaigns</h2>
                        <p class="mb-4">View your campaigns</p>
                        <div id="campaign-term-table"></div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-medium">Ad Groups</h2>
                        <p class="mb-4">View your ad groups</p>
                        <div id="ad-group-table"></div>
                    </div>
                </div>

                <script>
                    // Convert PHP data to a JavaScript object
                    var tabledata = <?php echo $jsonData; ?>;

                    // Create Tabulator on DOM element with id "search-term-table"
                    new Tabulator("#search-term-table", {
                        data: tabledata, // assign data to table
                        autoColumns: true, // create columns from data field names
                        height: "75vh",

                        // Enable pagination
                        pagination: "local",
                        paginationSize: 100, // number of rows per page
                    });


                    campaignsJsonData = <?php echo $campaignsJsonData; ?>;
                    // Create Tabulator on DOM element with id "search-term-table"
                    new Tabulator("#campaign-term-table", {
                        data: campaignsJsonData, // assign data to table
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

                </script>
            </div>

        </div>

    </div>
</div>