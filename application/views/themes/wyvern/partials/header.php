<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Wyvern:: A Simple Site Builder</title>

        <!-- Favicon Goes Here -->
        <link rel="shortcut icon" type="image/png" href="<?php echo get_single_asset_url('img/favicon.ico'); ?>"/>

        <!-- Theme Fonts -->
        <?php foreach ($fonts as $font_file): ?>
            <link href="<?php echo $font_file; ?>" rel="stylesheet">
        <?php endforeach; ?>
        <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
        <!-- /Theme Fonts -->

        <!-- Theme CSS -->
        <?php foreach ($css as $css_file): ?>
            <link href="<?php echo $css_file; ?>" rel="stylesheet">
        <?php endforeach; ?>
        <!-- Theme /CSS -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>

        <!-- Theme Javascript -->
        <?php foreach ($js as $js_file): ?>
            <script src="<?php echo $js_file; ?>" type="text/javascript"></script>
        <?php endforeach; ?>
        <!-- Theme /Javascript -->

        <script type="text/javascript">
            $(document).ready(function() {
                // cache the window object
                $window = $(window);

                $('section[data-type="background"]').each(function() {
                    // declare the variable to affect the defined data-type
                    var $scroll = $(this);

                    $(window).scroll(function() {
                        // HTML5 proves useful for helping with creating JS functions!
                        // also, negative value because we're scrolling upwards                             
                        var yPos = -($window.scrollTop() / $scroll.data('speed'));

                        // background position
                        var coords = '50% ' + yPos + 'px';

                        // move the background
                        $scroll.css({backgroundPosition: coords});
                    }); // end window scroll
                });  // end section function

                document.createElement("section");

            }); // close out script
        </script>
        <style>
        </style>
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo get_single_asset_url('img/logo-110px-35px.png'); ?>" alt="Wyvern" /></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#about">About</a>
                    </li>
                    <li>
                        <a href="#services">Services</a>
                    </li>
                    <li>
                        <a href="#contact">Contact</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('/auth/login'); ?>">Clients</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('/development'); ?>">Development</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
            <!-- /.container -->
        </nav>