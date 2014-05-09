<?php
/**
 * add css lib paths here to compile it later with a less compiler in init.php
 * path starts from root
 */
$css_import = array(
    '/css/style.css'                                       => 'main',
    '/js/vendor/bootstrap/dist/css/bootstrap.css'          => 'file',
    '/js/vendor/font-awesome/css/font-awesome.css'         => 'file',
    '/modules/notification/css/jquery.pnotify.default.css' => 'file',
    //'css_app'                                              => 'config',
    //'css_user'                                             => 'config',
);

// some stuff that replaces after compiling key = search, value = replace
$css_path_replacements = array(
    '{{app_base_color.value}}'  => __c('app_base_color'),
    '{{base_path}}'             => $base_path,
    '{{color_primary.value}}'   => __c('color_primary'),
    '{{color_secondary.value}}' => __c('color_secondary'),
    '{{color_highlight.value}}' => __c('color_highlight'),
    '{{app_font_body}}'         => __c('app_font_body'),
    '{{app_font_headings}}'     => __c('app_font_headings'),
    '../fonts/fontawesome'      => $base_path . '/js/vendor/font-awesome/fonts/fontawesome',
    '../fonts/glyphicons'       => $base_path . '/js/vendor/bootstrap/dist/fonts/glyphicons'
);

// import some google fonts
/**
 * Changed google font name to the right url syntax
 *
 * @param string $name google fontname
 *
 * @return string returns the right url syntax name
 */
function changeFontName($name)
{
    return str_replace(array(' ', '-'), array('+', '+'), $name);
}

$font_name = changeFontName(__c('app_font_body'));
if (__c('app_font_body') !== __c('app_font_headings'))
{
    $font_name = changeFontName(__c('app_font_body'));
    $font_name .= '|' . changeFontName(__c('app_font_headings'));
}
$import                                          = '@import url(https://fonts.googleapis.com/css?family=' . $font_name . ');';
$css_path_replacements['{{google_font_import}}'] = $import;

return array('import' => $css_import, 'replace' => $css_path_replacements);