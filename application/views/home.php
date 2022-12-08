<div class='container' style='min-height: 76vh;'>
    <!--<h2>Home</h2>-->
    <div class="text-center">
        <img class="img-fluid col-md-5" src="<?= assetUrl() ?>/img/mc-logo.png">
        <?php 
        echo '<script>console.log("isset($_SERVER[\'CI_ENV\']): '.(isset($_SERVER['CI_ENV']) ? 'true' : 'false').'");</script>';
        echo '<script>console.log("ENVIRONMENT: '.ENVIRONMENT.'" );</script>';
        echo '<script>console.log("getenv(\'CI_ENV\'): '.getenv('CI_ENV').'" );</script>';
        if(ENVIRONMENT !== 'production'){
            echo '<p class="text-muted">(dev)</p>';
        } else {
            echo '<p class="text-white" style="display: none;user-select: none;">(prod)</p>';
        }
        ?>
    </div>
</div>