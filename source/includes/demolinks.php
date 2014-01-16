<div class="btn-group btn-group-justified">
    <?php
    /*
    if (file_exists('modules/logging/'))
    {
        echo '<a href="#page/logging/demo/action" class="btn btn-default">Action-Log Demo</a>';
        echo '<a href="#page/logging/demo/admin" class="btn btn-default">Admin-Log Demo</a>';
        echo '<a href="#page/logging/demo/group" class="btn btn-default">Group-Log Demo</a>';
    }
    */
    $path = ROOT_PATH . '/modules/';

    $recursiveIterator = new RecursiveDirectoryIterator($path);
    foreach ($recursiveIterator as $element)
    {
        $config = $element->getPathName() . '/configs/demolinks.php';
        if ($element->isDir() && file_exists($config) && $element->getFilename() !== '.' && $element->getFilename() !== '..')
        {
            include_once($config);
        }
    }

    ?>
</div>