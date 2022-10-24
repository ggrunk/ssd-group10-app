<div class='container mb-2' style="height:339px;min-height: 76vh;">
    <div class="card col-md-5 mx-auto">
        <div class="card-body">
	        <h3 class="card-title text-center mb-4 mt-1">Create an account</h3>

            <?= form_open("Login/createuser") ?>
            <hr>
            <p class='text-danger'><?= $msg ?></p>
            <?= validation_errors('<div class="text-danger">', '</div>')?>
            <!-- <?= form_label('Username:', 'username', 'class="form-label"'); ?> -->
            <div class="input-group mb-3">
                <span class="input-group-text" ><i class="bi bi-person-fill"></i></span>
                <?= form_input( array(  'name' => 'username',
                                        'id' => 'username',
                                        'value' => set_value('username',"") ),
                                '', 
                                'class="form-control" placeholder="Username"'); ?>
            </div>
            <!-- <?= form_label('Password:', 'password', 'class="form-label"'); ?> -->

            <div class="input-group mb-3">
                <span class="input-group-text" ><i class="bi bi-lock-fill"></i></span>
                <?= form_input( array(  'name' => 'password',
                                        'id' => 'password',
                                        'value' => set_value('password',""),
                                        'type' => 'password',
                                        'class' => 'form-control',
                                        'placeholder' => 'Password' )); ?>
            </div>
            <div>
                <?= form_submit('createsubmit', 'Create Account', array('class' => 'btn btn-primary') ); ?>
            </div>
            <?= form_fieldset_close(); ?>
            <?= form_close() ?>
        </div>
    </div>
</div>