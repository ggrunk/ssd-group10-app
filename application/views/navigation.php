<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 py-0">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url(); ?>index.php?/Home">
            <img class="px-2 py-0" src="<?= assetUrl() ?>/img/mohawk-logo.svg" height="60">Mohawk Quality Control
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a href="<?= base_url(); ?>index.php?/Home" class="py-4 px-3 nav-link <?= ($this->uri->segment(1)=='Home' || $this->uri->segment(1)=='')? 'active' : ''?>">Home</a></li>
                <li class="nav-item"><a href="<?= base_url(); ?>index.php?/Survey" class="py-4 px-3 nav-link <?= ($this->uri->segment(1)=='Survey')? 'active' : ''?>">Survey</a></li>
                <li class="nav-item"><a href="<?= base_url(); ?>index.php?/Stats" class="py-4 px-3 nav-link <?=($this->uri->segment(1)=='Stats')? 'active' : ''?>">Stats</a></li>
                <?php
                    if($_SESSION['accesslevel']=='admin'){
                ?>
                <li class="nav-item"><a href="<?= base_url(); ?>index.php?/Admin" class="py-4 px-3 nav-link <?= ($this->uri->segment(1)=='Admin')? 'active' : ''?>">Admin Panel</a></li>
                <?php
                    }
                ?>
                
                <?php if ($loggedin) { ?>
                    <li class="nav-item"><a href="<?= base_url(); ?>index.php?/Login/logout" class="py-4 px-3 nav-link">Logout <p class='d-inline text-muted'>(<?= $this->userauth->getUsername() ?>)</p></a></li>
                <?php } else { ?>
                    <li class="nav-item fw-light "><a href="<?= base_url(); ?>index.php?/Login" class="py-4 px-3 nav-link <?= ($this->uri->segment(1)=='Login')? 'active' : ''?>">Login</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>