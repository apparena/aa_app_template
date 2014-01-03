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

    <!--Current server date: <?php echo $current_date->format("d.m.Y H:i:s");?>-->
</head>
<body class="<?php echo $classbody ?>">

<?php include_once('includes/navigation.php'); ?>

<div class="container">
    <header>
        <?php __pc('header_custom'); ?>
    </header>
    <div id="content">

        <!-- DEMO PART START. please remove this, before deployment -->
        <div class="btn-group btn-group-justified">
        <?php
        if(file_exists('modules/fangate/'))
        {
            echo '<a href="#page/fangate" class="btn btn-default">Fangate Demo</a>';
        }
        if(file_exists('modules/logging/'))
        {
            echo '<a href="#page/logging/demo/action" class="btn btn-default">Action-Log Demo</a>';
            echo '<a href="#page/logging/demo/admin" class="btn btn-default">Admin-Log Demo</a>';
            echo '<a href="#page/logging/demo/group" class="btn btn-default">Group-Log Demo</a>';
        }
        if (file_exists('modules/auth/'))
        {
            echo '<a href="#page/auth" class="btn btn-default">Login Demo</a>';
            echo '<a href="#page/auth/modal" class="btn btn-default">Login-Modal Demo</a>';
        }
        ?>
        </div>
        <!-- DEMO PART END. -->

        <div class="content-wrapper clearfix">
            <i class="fa <?php __pc('loader_class') ?> fa-spin startloader"></i>
        </div>
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
            <?php __pt('footer_terms', '<a href="#/page/app/terms">' . __t('terms') . '</a>'); ?>
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