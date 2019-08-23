<!DOCTYPE html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="pageholder">
        <header>
            <div class="site--header__holder">
                <div class="site--nav">
                    <?php wp_nav_menu( array( 'menu' => 'main-menu', 'container' => 'nav', 'container_class' => 'header--main__menu', 'container_id' => 'main-menu') );?>
                    <div class="menu--socials">
                        <a href="#" target="_blank" title="Our Facebook URL" class="menu--socials__link">
                            <img src="/wp-content/themes/hg/assets/images/fb-icon.png" alt="Facebook logo" />
                        </a>
                        <a href="#" target="_blank" title="Our Youtube URL" class="menu--socials__link">
                            <img src="/wp-content/themes/hg/assets/images/yt-icon.png" alt="Youtube logo" />
                        </a>
                        <a href="#" target="_blank" title="Our Instagram URL" class="menu--socials__link">
                            <img src="/wp-content/themes/hg/assets/images/insta-icon.png" alt="Instagram logo" />
                        </a>
                    </div>
                </div>

                <div class="site--logo">
                    <a href="/" title="Homepage link"><img src="/wp-content/themes/hg/assets/images/logo.png" alt="Site logo" /></a>
                </div>
            </div>
        </header>
        <div class="page--inner">