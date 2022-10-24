<?php
    try {
        $dbh = new PDO(
            "mysql:host=localhost;dbname=group08_mohawkqc_db",
            "group08",
            "Project12-2022"
        );
    } catch (Exception $e) {
        die("ERROR: Couldn't connect. {$e->getMessage()}");
    }
    
     $command = "SELECT * FROM submissions";
     $stmt = $dbh->prepare($command);
     $success = $stmt->execute();
            
     $data_submissions = array();
     while ($row = $stmt->fetch()) {
         array_push($data_submissions,$row);
     }
    
?>
<div class='container' style='min-height: 76vh;'>
    <h2>Stats</h2>
    <p>This is a table containing all the survey submissions. Enter filters in the column headers to filter by term, course id</p>
    
    <div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Filter year:</span>
            <input class="form-control" id="filter-year" type="number" min="2010" max="<?=date("Y")?>" placeholder="(e.g. <?=date("Y")?>)" aria-label="<?=date("Y")?>" aria-describedby="basic-addon1">
        </div>
        
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Filter term:</span>
            <select class="form-select" id="filter-term">
                <option></option>
                <option value="fall">Fall</option>
                <option value="winter">Winter</option>
                <option value="summer">Summer</option>
            </select>
        </div>
        
        <div class="mb-3">
            <button class="btn btn-outline-primary" id="filter-clear">Clear Filter</button>
        </div>
    </div>
    <div id="stats-table" class="w-fit mb-3"></div>
    <button type="button" id="button-export-xlsx" class="btn btn-primary"><i class="bi bi-download float-end"></i> Export Excel Sheet (.xlsx)</button>
    
    <br><br><br>
    <div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Courses</span>
            <select class="form-select" id="courses">
                <?php
                    $command = "SELECT submissions.user_ID, courses.course_name
                                FROM submissions
                                JOIN courses on courses.id=submissions.course_ID";
                    $stmt = $dbh->prepare($command);
                    $success = $stmt->execute();
                    
                    $unique = array();
                    while ($row = $stmt->fetch()) {
                        echo hello;
                        if (!in_array($row[0],$unique)){
                            echo '<option value=\"'.$row[0].'\">'.$row[1].'</option>';
                            array_push($unique,$row[0]);
                        }
                    }
                ?>
            </select>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Instructor</span>
            <select class="form-select" id="instructors">
                <option></option>
                <?php
                    $command = "SELECT submissions.user_ID, users.username
                                FROM submissions
                                JOIN users on users.user_id=submissions.user_ID";
                    $stmt = $dbh->prepare($command);
                    $success = $stmt->execute();
                    
                    $unique = array();
                    while ($row = $stmt->fetch()) {
                        if (!in_array($row[0],$unique)){
                            echo '<option value=\"'.$row[0].'\">'.$row[1].'</option>';
                            array_push($unique,$row[0]);
                        }
                    }
                ?>
            </select>
        </div>

        <div id="curve_chart" style="width: 900px; height: 500px"></div>
    </div>
    
</div>



<?php
    /* debug output */
    // print_r('~~~~~~~~~~~~~~~~~submissions');
    // echo "<br/>";
    // var_dump($submissions);
    
    // print_r('~~~~~~~~~~~~~~~~~submissions_columns');
    // echo "<br/>";
    // var_dump($submissions_columns);
?>

<script type="text/javascript">
    var submissions = JSON.parse('<?php echo json_encode($submissions); ?>');
    var submissions_columns = JSON.parse('<?php echo json_encode($submissions_columns); ?>');
    console.log('stats.php script tag: test');
</script>
<script>
    var ddata = JSON.parse('<?php echo json_encode($data_submissions); ?>');
</script>
        
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    var course = document.getElementById("courses");
    var instructor = document.getElementById("instructors");
    //var ddata = JSON.parse('<?php echo json_encode($data_submissions); ?>');
            
    document.getElementById("courses").addEventListener("change", changed);
    document.getElementById("instructors").addEventListener("change", changed);
            
    function changed(){
        if(instructor.value === ""){
            for (const element of submissions) {
                if(course.value === element['term']){ // organize only by course and average any that have the same term
                    console.log('success');
                }
                else{ // organize by instructer and course
                    console.log('failed');
                }
            }
        }
    }
            
    function drawChart() {
        var temp = [['Year','Score']];
        for (const element of submissions) {
            temp.push([parseInt(element['year']),parseInt(element['total'])]);
        }
        // var temp = [
        //     ['Year', 'Sales'],
        //     ['2004',  1000],
        //     ['2005',  1170],
        //     ['2006',  660],
        //     ['2007',  1030]
        // ]
        var data = google.visualization.arrayToDataTable(temp);
    var options = {
        title: 'Company Performance',
        curveType: 'function',
        legend: { position: 'bottom' }
    };
    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    chart.draw(data, options);
    }
    </script>

<script type='text/javascript' src="<?= assetUrl() ?>/js/stats.js"></script>