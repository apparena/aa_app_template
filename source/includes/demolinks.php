<?php
$path              = ROOT_PATH . '/modules/';
$demo_links        = '<div class="btn-group btn-group-justified">';
$recursiveIterator = new RecursiveDirectoryIterator($path);
foreach ($recursiveIterator as $element)
{
    $config = $element->getPathName() . '/configs/demolinks.php';
    if ($element->isDir() && file_exists($config) && $element->getFilename() !== '.' && $element->getFilename() !== '..')
    {
        $demo_links .= file_get_contents($config);
    }
}
$demo_links .= '</div>';

return $demo_links;