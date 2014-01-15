<?php
/*
 * Prepares a page with Facebook Meta Data
 * This page receives the instance id as GET-Parameter and creates a page perfectly fitted for a like or send button to like
 * So if a friend of the user which liked this page, clicks the link to that, page he will be redirected.
 */
define('REDIRECTION', true);
require_once 'init.php';
//$fb_share_url = "https://apps.facebook.com/" . $aa['instance']['fb_app_url'] . "/share.php?i_id=" . $aa->instance->i_id;

$fb_share_url = $aa['instance']["fb_canvas_url"] . "share.php?i_id=" . $aa->instance->i_id;

$redirect_url = $aa['instance']['fb_page_url'] . "?sk=app_" . $aa['instance']['fb_app_id'];

// redirect only desktops to facebook
if (__c('app_using_on') === 'website'
    || ($aa['env']['device']['type'] !== 'desktop' && __c('app_using_on') !== 'facebook')
    || (!empty($_GET['page']) && $_GET['page'] === 'website')
)
{
    $redirect_url = $aa['instance']["fb_canvas_url"] . "index.php?i_id=" . $aa->instance->i_id;
}

if (!empty($_GET['locale']))
{
    $redirect_url .= '&locale=' . $_GET['locale'];
}

// Check if app_data exists and concatinate it to the sharing url
if (isset($_GET['app_data']))
{
    $fb_share_url .= "&amp;app_data=" . urlencode($_GET['app_data']);
    $redirect_url .= "&app_data=" . urlencode($_GET['app_data']);
}

$share_image = __c('share_image');
$share_title = __c('general_title');
$share_description = __c('general_desc');

if (!empty($_GET['share-door']) && is_numeric($_GET['share-door']))
{
    $door_type   = __c('door_' . $_GET['share-door'] . '_type');
    $door_prefix = 'door_' . $_GET['share-door'] . '_type_' . $door_type . '_';
    $door_image  = __c($door_prefix . 'image');
    if (!empty($door_image) && $door_image !== false)
    {
        $share_image = $door_image;
    }

    $door_title = __c($door_image . 'title');
    if (!empty($door_title) && $door_title !== false)
    {
        $share_title = $door_title;
    }

    $door_desc = __c($door_image . 'desc');
    if (!empty($door_desc) && $door_desc !== false)
    {
        $share_description = $door_desc;
    }
    $fb_share_url .= "&amp;share-door=" . urlencode($_GET['share-door']);
}

if (!empty($_GET['share-greetingcard']) && is_numeric($_GET['share-greetingcard']))
{
    $greetingcard = __c('greetingcard_image' . $_GET['share-greetingcard']);
    if (!empty($greetingcard) && $greetingcard !== false)
    {
        $share_image = $greetingcard;
    }
    $fb_share_url .= "&amp;share-greetingcard=" . urlencode($_GET['share-greetingcard']);
}

$og_type = 'website';
if (!empty($_GET['og-object']))
{
    $og_type = $aa['instance']['fb_app_url'] . ':' . $_GET['og-object'];
    $fb_share_url .= "&amp;og-object=" . urlencode($_GET['og-object']);
}

?>
<!doctype html>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# <?php echo $aa['instance']['fb_app_url']; ?>: http://ogp.me/ns/fb/<?php echo $aa['instance']['fb_app_url']; ?>#">
    <meta charset="UTF-8">
    <!-- Google+ Meta Data -->
    <title><?php echo $share_title; ?></title>
    <meta name="description" content="<?php echo $share_description; ?>" />
    <link rel="image_src" href="<?php echo $share_image; ?>" />
    <link rel="canonical" href="<?php echo $fb_share_url; ?>" />

    <!-- Facebook Meta Data -->
    <meta property="fb:app_id" content="<?php echo $aa['instance']['fb_app_id'] ?>" />
    <meta property="og:title" content="<?php echo $share_title; ?>" />
    <meta property="og:type" content="<?php echo $og_type; ?>" />
    <meta property="og:url" content="<?php echo $fb_share_url; ?>" />
    <meta property="og:image" content="<?php echo $share_image; ?>" />
    <meta property="og:site_name" content="<?php echo $share_title; ?>" />
    <meta property="og:description" content="<?php echo $share_description; ?>" />
    <!--meta property="og:i_id" content="<?php echo $aa->instance->i_id; ?>" /-->
</head>

<body>
<!-- Share image for Google+ -->
<img src="<?php echo $share_image; ?>" alt="" title="" />

<?php if (empty($_GET['debug'])): ?>
    <script type="text/javascript">
    top.location = "<?php echo $redirect_url?>";
</script>
<?php endif; ?>
</body>
</html>
