<?php
// views/dashboard/keywords/uploadKeywords.php
include 'views/dashboard/partials/header.php';
?>

<div class="screen">
    <div class="p-8">
            <h1 class="text-4xl font-medium">Upload Keywords</h1>
            <p>Upload your keywords file</p>
        </div>
        
        <div class="p-8">
            
            <div class="bg-slate-300 rounded-lg p-4">
                <h2 class="font-medium text-xl">Upload Keywords</h2>
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
</div>