<?php
// views/dashboard/keywords/uploadKeywords.php
include 'views/dashboard/partials/header.php';
?>


<div class="screen flex flex-col">

    <div class="p-4 md:p-8 shrink">
        <h1 class="text-3xl md:text-6xl font-bold mb-2 md:mb-4">Upload Report</h1>
        <p class="text-xl">Upload your search term report to start analysis.</p>
    </div>
    
    <div class="p-4">
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md">
            <h2 class="font-medium text-xl mb-4">Upload CSV file</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" required>
                <button class="button" type="submit">Upload</button>
            </form>
        </div>


        <div id="search-term-table"></div>
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
            </script>

    </div>

</div>


<?php include 'views/dashboard/partials/footer.php'; ?>