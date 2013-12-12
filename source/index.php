<?php include_once('includes/bootstrap.php'); ?>

<!doctype html>
<html lang="de-DE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?php __pc('general_title'); ?></title>
    <meta name="description" content="<?php __pc('general_desc'); ?>" />
    <link rel="canonical" href="<?php echo $aa->instance->share_url; ?>" />

    <link type="text/css" rel="stylesheet" href="<?php echo $css_file['name']; ?>" />

    <!--[if gte IE 9]>
    <link type="text/css" rel="stylesheet" href="css/ie9.css" />
    <![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!--Current server date: <?php echo $current_date->format("d.m.Y H:i:s");?>-->
</head>
<body class="<?php echo $classbody ?> <?php __pc('design_template') ?>">

<?php include_once('includes/navigation.php'); ?>

<div class="container">
    <header class="gradient">
        <?php __pc('header_custom'); ?>
        <?php __pc('header_custom_design'); ?>
    </header>
    <div id="content">
        <figure class="page-background">
            <?php
            $app_background_image = __c('overview_background_mobile');
            if ($aa->env->device->type !== 'mobile')
            {
                $app_background_image = __c('overview_background_desktop');
            }
            ?>
            <img src="<?php echo $app_background_image; ?>" title="" alt="background" />
        </figure>
        <div class="content-wrapper clearfix">
            <i class="<?php __pc('loader_class') ?> icon-spin startloader"></i></div>
    </div>
    <footer>
        <?php __pc('footer_custom') ?>
    </footer>
    <?php if (__c('show_comments') === '1'): ?>
        <div id="comment-box">
            <h3><?php __pc('fb_comments_title'); ?></h3>

            <div class="fb-comments" data-href="<?php echo $aa->instance->share_url; ?>" data-colorscheme="light" data-width="810" data-num_posts="<?php __pc('fb_comments_amount'); ?>"></div>
        </div>
    <?php ENDIF; ?>
    <div class="content-terms text-center text-muted">
        <small>
            <?php
            $terms_trans = __t('footer_terms');
            $terms_link = '<a href="#/page/app/terms">' . __t('terms') . '</a>';
            echo sprintf($terms_trans, $terms_link);
            ?>
        </small>
    </div>
    <?php if (__c('branding_activated') === '1'): ?>
        <div class="branding">
            <?php __pc('branding_footer'); ?>
        </div>
    <?php ENDIF; ?>
</div>

<?php include_once('includes/footer.php'); ?>
</body>
</html>