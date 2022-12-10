<script type='text/javascript' src="<?= assetUrl() ?>/js/survey.js"></script>
<div class='container' style='min-height: 76vh;'>
    <h2>Survey</h2>
    <?= form_open("Survey/submit", array('class' => 'needs-validation', 'novalidate' => '') ) ?>
    <div>
        <div class="input-group mb-3 col-md-8">
            <span class="input-group-text">Course</span>
            <select class="form-select" id="survey-course" name="survey-course"  required>
                <option selected disabled value=""></option>
                <?php foreach ($survey_courses as $course) {?>
                    <option value="<?=$course['id']?>"><?=$course['course_code']?> - <?=$course['course_name']?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="input-group mb-3  col-md-4">
            <span class="input-group-text">Year</span>
            <select class="form-select" id="survey-year" name="survey-year" required>
                <option selected disabled value=""></option>
                <?php $currentyear=date("Y"); for ($x = 0; $x <= 8; $x++) { ?>
                    <option value="<?=$currentyear-$x?>"><?=$currentyear-$x?></option>
                <?php } ?>
            </select>
            <!--<input class="form-control" value="<?=date("Y")?>" id="survey-year" required type="number" min="2010" max="<?=date("Y")?>" placeholder="(e.g. <?=date("Y")?>)" aria-label="<?=date("Y")?>">-->
        </div>
        
        <div class="input-group mb-3  col-md-4">
            <span class="input-group-text" id="basic-addon1">Term</span>
            <select class="form-select" id="survey-term" name="survey-term" required>
                <option selected disabled value=""></option>
                <option value="fall">Fall</option>
                <option value="winter">Winter</option>
                <option value="summer">Summer</option>
            </select>
        </div>
    </div>
    <div class="col-lg-9">

        <?php if (isset($message) || (validation_errors('', ''))!='' ) { ?>
        <div class="alert alert-info" role="alert">
            <?= $message?>
            <?= validation_errors('<p>', '</p>')?>
            <?php 
                //$errorArray = $this->form_validation->error_array();
                //echo 'You must provide '.(  count($errorArray)==1 ? 'an answer for question' : 'answers for questions'  ).' '.implode(', ', $errorArray ).'.';
            ?>
        </div>
        <?php }?>
        
            <div class="accordion mb-3" id="accordionExample">
                <?php $category=''; $category_num=0;
                foreach ($survey_questions as $item) {
                    if($category != $item['category']){
                        $category_num+=1;
                        $category = $item['category'];
                        ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?=$item['id']?>">
                                <button class="accordion-button <?=$category_num==1 ? '' : 'collapsed'?> " type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$item['id']?>" aria-expanded="false" aria-controls="collapseOne">
                                    <div>
                                        <p class="d-inline h4 fw-bolder text-capitalize"><?= $item['category_display'] ?></p>
                                        <p class="d-inline text-muted fw-strong"> (Section <?=$category_num?>)</p>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse<?=$item['id']?>" class="accordion-collapse collapse <?=$category_num==1 ? 'show' : ''?>" aria-labelledby="heading<?=$item['id']?>" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <table class="table table-hover">
                                        <?php foreach ($survey_questions as $question) { if($question['category'] == $item['category']) {  ?>
                                            <tr>
                                                <td class="text-muted">Q.<?=$question['id']?></td>
                                                <td><p class="d-inline-block"><?=$question['question']?></p></td>
                                                <td><?= form_input( array(  'name' => $question['id'],
                                                                                'id' => $question['id'],
                                                                                'value' => set_value( $question['id'], isset($_COOKIE['survey_data'][$question['id']]) ? json_decode(get_cookie('survey_data'))->{$question['id']} : ''), 
                                                                                'type' => 'number',
                                                                                'max' => $question['total'],
                                                                                'min' => 0,
                                                                                'required' => ''),
                                                                        '', 
                                                                        'class="form-control text-center px-0 d-inline-block survey-form" style="width:5rem;" placeholder="0 - '.$question['total'].'"'); ?></td>
                                            </tr>
                                        <?php } } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?= form_submit('survey_submit', 'Submit', array('class' => 'btn btn-primary') ); ?>
        <div class="invalid-feedback">
          You must fill out each field of the survey.
        </div>
        <?= form_fieldset_close(); ?>
        <?= form_close() ?>
    </div>
</div>