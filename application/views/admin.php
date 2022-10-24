<div class='container mb-2' style='min-height: 76vh;'>
    <h2>Admin page</h2>
    <div class='mt-5 '>
        <h3 class='d-inline-block'>Users table</h3>
        <p class='d-inline text-muted'>(Frozen users are highlited in blue)</p>
    </div>
    
    <table class='table table-sm table-striped table-cellhover text-center'>
        <tr class='fw-bold table-secondary'>
            <td class='px-0'>Delete</td>
            <td class='px-0'>Freeze</td>
            <td>Username</td>
            <td>Access level</td>
            <td>Frozen</td>
        </tr>
        <?php foreach ($users_table as $item) { ?>
            <tr <?=(($item["frozen"]=='Y') ? 'class="frozen"': '')?>> 
                <td><a href="<?=site_url()?>/Admin/delete/<?=$item["user_id"]?>"><i class="h5 bi bi-trash3"></i></a></td>
                <td><a href="<?=site_url()?>/Admin/freeze/<?=$item["user_id"]?>"><i class="h5 bi bi-snow2"></i></a></td>
                <td><?= $item["username"] ?> </td>
                <td><?= $item["accesslevel"] ?> </td>
                <td><?=(($item["frozen"]=='Y') ? 'Yes': 'No')?></td>
            </tr>
        <?php } ?>
        <?php if (count($users_table) == 0){ ?>
            <tr>
                <td colspan='6'>No users found!</td>
            </tr>
        <?php } ?>
    </table>

    <div class="card col-md-5">
        <div class="card-body">
	        <h3 class="card-title text-center mb-2 mt-1">Add a new user</h3>
            <?= form_open('Admin/create'); ?>

            <p class='text-success'><?= $msg ?></p>
            <?= validation_errors('<div class="text-danger">', '</div>')?>
            <!-- <?= form_label('Username:', 'username', 'class="form-label"'); ?> -->
            <div class="input-group mb-3 mt-2">
                <span class="input-group-text text-muted" id="basic-addon1"><i class="bi bi-person-fill"></i>Username</span>
                
                <?= form_input( array(  'name' => 'username',
                                        'id' => 'username',
                                        'value' => set_value('username',"") ),
                                '', 
                                'class="form-control" placeholder="Username"'); ?>
            </div>
            <!-- <?= form_label('Password:', 'password', 'class="form-label"'); ?> -->

            <div class="input-group mb-3">
                <span class="input-group-text text-muted" id="basic-addon1"><i class="bi bi-lock-fill"></i>Password</span>
                <?= form_input( array(  'name' => 'password',
                                        'id' => 'password',
                                        'value' => set_value('password',""),
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'placeholder' => 'Password' )); ?>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text text-muted" id="basic-addon1"><i class="bi bi-person-badge"></i>Access level</span>
                <select name="accesslevel" id="accesslevel" class="form-select">
                    <option class="text-muted" selected disabled value="">Select an Access level...</option>
                    <option value="member" <?= set_value('accesslevel',"")=='member' ? 'selected' : '' ?>  >Member</option>
                    <option value="editor" <?= set_value('accesslevel',"")=='editor' ? 'selected' : '' ?>  >Editor</option>
                    <option value="admin" <?= set_value('accesslevel',"")=='admin' ? 'selected' : '' ?>  >Admin</option>
                </select>
            </div>

            <?= form_submit('createsubmit', 'Create', array('class' => 'btn btn-primary') ); ?>
            <?= form_fieldset_close(); ?>
            <?= form_close() ?>
        </div>
    </div>
</div>