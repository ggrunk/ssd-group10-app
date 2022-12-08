<div class='container' style='min-height: 76vh;'>
    <!--<h2>Home</h2>-->
    <div class="text-center">
        <img class="img-fluid col-md-5" src="<?= assetUrl() ?>/img/mc-logo.png">
        <?php 
        echo '<script>console.log("env: '.ENVIRONMENT.'" );</script>';
        if(ENVIRONMENT !== 'production'){
            echo '<p class="text-muted">(dev)</p>';
        } else {
            echo '<p class="text-white" style="display: none;user-select: none;">(prod)</p>';
        }
        ?>
    </div>
</div>