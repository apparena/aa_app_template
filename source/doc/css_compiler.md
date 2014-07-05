# Internal LESS/CSS Compiler
We use a little helper to compile LESS and CSS code to one file and shrink them to optimize the app performance. Over a config file, we can define, which files and app-manager configs are loaded and which parts are replaced with other stuff, like other config values, paths or something else.

## Source definitions
First of all, we open the file css-config.php in our config folder (/source/configs/). On top we find an array that's called $css_import. Here we can define the path as key to an file or a config name from the app-manager. As value we define the type of the array key. Here are three options:

* main - First loaded file. Must not be on the first place in the array
* file - For internal or external css/less files
* config - app-manager config value

### Examples
```php
$css_import = array(
    '/css/style.css' => 'main',
    '/js/vendor/bootstrap/dist/css/bootstrap.css' => 'file',
    'css_user' => 'config',
);
```
In this case we initialize the style.css as our first file with main. After that we load the vendor bootstrap file and additional the css_user config value from the app-manager API. It's important to define the right order of files. Only the main file is loaded at first and can be placed everywhere.

## Replacement definitions
In the second array, that's called $css_path_replacements, we define some search and replace think. That means, you can replace some variables or path elements with additional config values from app-manager or with the app base path or something else.

Here you have the option to user own srings/code or all config and locale values from the app-manager. Additional you can use the variable $base_path to replace something we the app root path.

### Examples

Here we replace first the variable {{app_base_color.value}} in our less/css code with a defined color from our app-manager config app_base_color. At the second line we replace the font-awesome basic path with our base path to load the right files.

## Additional Information
In the config file, you can add some usefull funktions or additional code to replace something or load other files. In some apps, you will find a funktion to load google fonts over a app-manager cinfiguration. That's a simple function to expand the search/replace array and to use a selected font. Be creative and enlarge this with new amazing thinks.

## Start Compiler
To start compiling our css code, initiaze only the helper and call the compiler method:
```php
$css = new \Apparena\Helper\Css();
$css->getCompiled();
```
Thats it!
There are a additional method to add additional sources without the config file, but you only need this two line to start the magic. The compiler put the compiled code automatically to our cache handler and stored it. If a cached file exist, the compiler returned it. Otherwise he starts a new compiling process.