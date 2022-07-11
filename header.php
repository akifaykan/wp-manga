<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="<?php bloginfo('charset'); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if (is_singular() && pings_open(get_queried_object())): ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php endif; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?=body_class('inner dark')?>>
    <header>
        <div class="container mt">
            <?php 
                if (is_home()){
                    echo sprintf(
                        '<h1>%s</h1>',
                        get_bloginfo('title')
                    );
                } else {
                    echo sprintf(
                        '<h1 class="mb">%s</h1>',
                        get_the_title()
                    );
                    echo '<a class="mt back" href='.home_url().'>&larr; Geri dön</a>';
                }
            
            ?>
        </div>
    </header>
