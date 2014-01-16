<?php
define('REDIRECT', true);
include_once('includes/bootstrap.php');
?>

<!doctype html>
<html lang="de-DE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?php __pc('general_title'); ?></title>
    <meta name="description" content="<?php __pc('general_desc'); ?>" />
    <link rel="canonical" href="<?php echo $aa->instance->share_url; ?>" />

    <link type="text/css" rel="stylesheet" href="<?php echo $css_file['name']; ?>" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --><!--[if lt IE 9]>
    <script src="js/vendor/bootstrap/assets/js/html5shiv.js"></script>
    <script src="js/vendor/bootstrap/assets/js/respond.min.js"></script><![endif]-->

    <!--Current server date: <?php echo $current_date->format("d.m.Y H:i:s");?>-->
</head>
<body class="<?php echo $classbody ?> oldbrowser">

<?php include_once('includes/navigation.php'); ?>

<div class="container">
    <header>
        <?php __pc('header_custom'); ?>
    </header>
    <div id="content">
        <div class="content-wrapper" class="clearfix">
            <div class="browser-notification">
                <?php __pc('old_browser_page'); ?>
            </div>
        </div>
    </div>
    <footer>
        <?php __pc('footer_custom') ?>
    </footer>
</div>

</body>
</html>