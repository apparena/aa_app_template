<?php
unset($aa->config->css_bootstrap);
unset($aa->config->css_app);
unset($aa->config->tac);

if (__c('admin_debug_mode') === '1')
{
    echo '<button id="show-debug" class="show-debug btn btn-default" data-content="debug">Show Debug</button>';
    echo '<div id="debug">';

    echo '<button class="show-debug btn btn-default" data-content="debug-instance">Show instance</button>';
    echo '<button class="show-debug btn btn-default" data-content="debug-config">Show config</button>';
    echo '<button class="show-debug btn btn-default" data-content="debug-locale">Show locale</button>';
    echo '<button class="show-debug btn btn-default" data-content="debug-fb">Show fb</button>';
    echo '<button class="show-debug btn btn-default" data-content="debug-env">Show env</button>';

    echo '<pre id="debug-instance">';
    print_r($aa['instance']);
    echo '</pre>';

    echo '<pre id="debug-config">';
    print_r($aa->config);
    echo '</pre>';

    echo '<pre id="debug-locale">';
    print_r($aa->locale);
    echo '</pre>';

    echo '<pre id="debug-fb">';
    print_r($aa->fb);
    echo '</pre>';

    echo '<pre id="debug-env">';
    print_r($aa->env);
    echo '</pre>';

    echo '</div>';
}

$offline_mode = '';
if (defined('OFFLINE_MODE') && OFFLINE_MODE === true)
{
    $offline_mode = '<br>offline mode';
}
if (defined('ENV_MODE') && ENV_MODE === 'dev')
{
    echo '<div class="env-mode">' . ENV_MODE . $offline_mode . '</div>';
}
?>
<script id="tempcontainer">
    var aa = <?php echo json_encode($aaForJs); ?>; // copy aa as a global object to js
</script>

<script src="configs/require-config.js"></script>
<script id="requirejs" data-main="js/main" src="js/vendor/requirejs/require.js"></script>