<?php get_header(); ?>

<div class="container">
    <?php $this->load->view($view, $this->viewdata, $render); ?>
</div>

<?php get_modal(); ?>
<?php get_footer(); ?>