
<div class="container">
    <h4><?php echo lang('forgot_password_heading'); ?> <small><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></small></h4>

    <?php echo form_open("auth/forgot_password", array("class" => "form-horizontal half-width", "role" => "form")); ?>

    <?php if ($message): ?>
        <div class="warnings"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php echo form_input($email, '', sprintf(lang('forgot_password_email_label'), $identity_label)); ?>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <?php echo form_submit('submit', lang('forgot_password_submit_btn')); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>