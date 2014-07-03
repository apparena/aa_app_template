# App helper functions
We inplemented some helper functions in php and javascript to simplify some developing steps. Here you find a short list and some information to all the own helper. You can find much more helper for our php (slim framework) and javascript (backbonejs and extension) engine frameworks on the vendor pages.

You can find all php helper under *\libs\AppArena\Helper\aa_helper.php* and all JS helper under *\js\utils\apparena\helper.js*.

## __c([$config], [$value = 'value'])
Returns the config value for the identifier in parameter 1. The second parameter defins the identifier key (i.e. value, src, ...). 
**In javascript you can use this helper with _.c()**

## __pc([$config], [$value = 'value'})
Same like __c() but with directly print out the return.

## __t([$locale_string], [$replace1, $replace2, ...])
Returns the translatet locale string identifier from parameter 1. With the other parameters, you can replace some placeholder in the returned string. We use here the printf() function to do this. THe very basic placeholder of this function is %s. 
**In javascript you can use this helper with _.t()**

## __pt([$locale_string], [$replace1, $replace2, ...])
Same like __t() but with directly print out the return.


## getBrowser()
Returnes browser information.

## global_escape()
Escapes $_GET, $_POST, $_REQUIRE $_COOKIE variables for newer PHP version, where this option is deprecated in php.ini.

## unregister_globals()
Unregistered register globals for newer PHP versions, where the php.ini option is deprecated.

## escape($value, $specialchars = true)
Escapes param 1 value with stripslahes AND htmlspecialchars with second parameter is true and returned a trimed version.

## pr($string|array|object)
Shortcut for print_r() with additional output and Styling. Shows a formated debug output.

## vd($string|array|object)
Shortcut for var_dump() with additional output and Styling. Shows a formated debug output.

## ifempty($var, $value = '')
Sets $var with $value if empty

## iif($exp, $true, $false = '')
Returns $true if $exp = TRUE, else $false

## is_serialized()
Cheks a string, if that is serialized or not. Returns true or false. With this you can check the string before you unserialize something. Normaly if the string is not serialized, you get back a fatal error.

### Additional vendor documentations
- [Slim framework](http://www.slimframework.com/)
- [BackboneJs](http://www.Backbonejs.org/)
- [UnderscoreJs](http://underscorejs.org/)
- [lodash](http://lodash.com/)