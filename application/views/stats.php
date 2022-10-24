<?php
/*     try {
        $dbh = new PDO(
            "mysql:host=localhost;dbname=group08_mohawkqc_db",
            "group08",
            "Project12-2022"
        );
    } catch (Exception $e) {
        die("ERROR: Couldn't connect. {$e->getMessage()}");
    }
    
    $command = "SELECT *
                FROM submissions
                ORDER BY year, term DESC";
     $stmt = $dbh->prepare($command);
     $success = $stmt->execute();
            
     $data_submissions = array();
     while ($row = $stmt->fetch()) {
         array_push($data_submissions,$row);
    } */
	
?>
<div class='container' style='min-height: 76vh;'>
    <h2>Stats</h2>
    <p>This is a table containing all the survey submissions. You can filter the results in the form below.</p>
    
    <div class="card p-2 mb-2 col-lg-5">
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Filter year:</span>
            <!--<input class="form-control" id="filter-year" type="number" min="<?=date("Y")-10?>" max="<?=date("Y")?>" placeholder="(e.g. <?=date("Y")?>)" aria-label="<?=date("Y")?>" aria-describedby="basic-addon1">-->
            <select class="form-select" id="filter-year" >
                <option selected disabled value=""></option>
                <?php $currentyear=date("Y"); for ($x = 0; $x <= 10; $x++) { ?>
                    <option value="<?=$currentyear-$x?>"><?=$currentyear-$x?></option>
                <?php } ?>
            </select>
       </div>
        
        <div class="input-group mb-3">
            <span class="input-group-text">Filter term:</span>
            <select class="form-select" id="filter-term">
                <option></option>
                <option value="fall">Fall</option>
                <option value="winter">Winter</option>
                <option value="summer">Summer</option>
            </select>
        </div>
        
        <div class="input-group mb-3 col-md-8">
            <span class="input-group-text">Filter Course:</span>
            <select class="form-select" id="filter-course">
                <option></option>
                <?php foreach ($survey_courses as $course) {?>
                    <option value="<?=$course['course_code']?>"><?=$course['course_code']?> - <?=$course['course_name']?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="mb-3">
            <button class="btn btn-sm btn-primary" id="filter-clear">Clear Filter</button>
        </div>
    </div>
    <div id="stats-table" class="w-fit mb-3"></div>
    <button type="button" id="button-export-xlsx" class="btn btn-sm btn-primary float-end"><i class="bi bi-download"></i> Export Excel Sheet (.xlsx)</button>
    
    <br><br><br>
    <div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Courses</span>
            <select class="form-select" id="courses">
                <?php
                    /* $command = "SELECT courses.id, courses.program_name, courses.course_code
                                FROM submissions
                                JOIN courses on courses.id=submissions.course_ID";
                    $stmt = $dbh->prepare($command);
                    $success = $stmt->execute();
                    
                    $unique = array();
                    while ($row = $stmt->fetch()) {
                        if (!in_array($row[0],$unique)){
                            echo '<option value='.$row[0].'>'.$row[2].' : '.$row[1].'</option>';
                            array_push($unique,$row[0]);
                        }
                    } */
					$unique = array();
					foreach ($data_submitted_courses as $row) {
                        if (!in_array($row[0],$unique)){
                            echo '<option value='.$row[0].'>'.$row[2].' : '.$row[1].'</option>';
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
                    /* $command = "SELECT submissions.user_ID, users.username
                                FROM submissions
                                JOIN users on users.user_id=submissions.user_ID";
                    $stmt = $dbh->prepare($command);
                    $success = $stmt->execute();
                    
                    $unique = array();
                    while ($row = $stmt->fetch()) {
                        if (!in_array($row[0],$unique)){
                            echo '<option value='.$row[0].'>'.$row[1].'</option>';
                            array_push($unique,$row[0]);
                        }
                    } */
					$unique = array();
                    foreach ($data_submitted_users as $row) {
                        if (!in_array($row[0],$unique)){
                            echo '<option value='.$row[0].'>'.$row[1].'</option>';
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
        
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
      
        var course = document.getElementById("courses");
        var instructor = document.getElementById("instructors");
        var ddata = JSON.parse('<?php echo json_encode($data_submissions); ?>');
        
        document.getElementById("courses").addEventListener("change", drawChart);
        document.getElementById("instructors").addEventListener("change", drawChart);
        

        function drawChart() {
            var temp = [['Year','Score']];
            var concat;
            if(instructor.value === ""){
                for (const element of ddata) {
                    if(course.value === element['course_ID']){ // organize only by course and average any that have the same term
                        if(element['term']=="winter"){
                            concat = 15;
                        }
                        else if(element['term']=="summer"){
                            concat = 25;
                        }
                        else{
                            concat = 35;
                        }
                        temp.push([(parseInt(element['year'])*100+concat),parseInt(element['total'])]);
                        console.log('success');
                    }
                }
            }
            else if (instructor.value !== ""){
                for (const element of ddata) {
                    if(course.value === element['course_ID'] && instructor.value==element['user_ID']){
                        if(element['term']=="winter"){
                            concat = 15;
                        }
                        else if(element['term']=="summer"){
                            concat = 25;
                        }
                        else{
                            concat = 35;
                        }
                        temp.push([(parseInt(element['year'])*100+concat),parseInt(element['total'])]);
                        console.log('success');
                    }
                }
            }
            data = google.visualization.arrayToDataTable(temp);
            
        var current_year = new Date().getFullYear();
        var my_ticks = [];

        for (var i = 0; i < 10; i++) {
            my_ticks.push({v: (current_year-i)*100, f: current_year});
        }

        var options = {
            title: 'QM Scores',
            //curveType: 'function',
            legend: { position: 'bottom' },
            vAxis: {
                viewWindow: {
                    min: 0,
                    max: 100
                }
            }
       };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>


<script type='text/javascript' src="<?= assetUrl() ?>/js/stats.js"></script>