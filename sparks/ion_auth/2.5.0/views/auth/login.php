<div class="container">
    
    <h4><?php echo lang('login_heading'); ?> <small><?php echo lang('login_subheading'); ?></small></h4>

    <?php echo form_open("auth/login", array("class" => "form-horizontal half-width", "role" => "form")); ?>

    <?php if ($message): ?>
        <div class="warnings"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php echo form_input($identity, '', lang('login_identity_label', 'identity')); ?>

    <?php echo form_input($password, '', lang('login_password_label', 'password')); ?>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <?php echo form_checkbox('remember', '', lang('login_remember_label', 'remember')); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <?php echo form_submit('submit', lang('login_submit_btn')); ?>
            &nbsp; <a href="forgot_password"><?php echo lang('login_forgot_password'); ?></a>
        </div>
    </div>

    <?php echo form_close(); ?>
</div>