<?php
add_action('wp_enqueue_scripts',function(){wp_enqueue_style('cafe-origen',get_stylesheet_uri(),array(),'1.0.0');});
add_action('after_setup_theme',function(){add_theme_support('woocommerce');});
