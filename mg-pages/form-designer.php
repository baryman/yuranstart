<?php
MG::enableTemplate();
$success = formDesigner::processingForm($_POST);
if ($success) {
  ?>
  <div class="mg-form-success-answer">
      <h2><?php echo $success ?></h2>
  </div>
<?php } else { ?>
  <div class="mg-form-error-answer">
      <h2>Извините, по техническим причинам форма не отправлена. Попробуйте позже.</h2>
  </div>
<?php } ?>
