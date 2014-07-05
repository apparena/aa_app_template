# Browser support settings
To disable your app for old browsers, we implemented a browser config file. Here you can define all browsers and versions, that will be redirected to an information page. You can modify this information in an separated template.

# Config file
Open the file **browser-config.php** in the app config directory. Here you find an array that will be returned with all the brwoser settings. The main key's are separated to

- all
- desktop
- mobile
- tablet

and contains additional arrays with a key for browsers and an array with the browser version and an operator for checks.

That means, that you can define your settings to different devices. The **all** key means in this case, all devices. With the other key's, you can refine your settings.

If one of the settings is true, the user will be redirected to https://www.yourapp.com/[i_id]/[LOCALE]/browser/. This page implements the config value **old_browser_page** from the app-manager. Thats an HTML value. So you can define a hole page with all Information for your user, a video, a smart picture or something else. Or you change the browser.html template and create a basic information page for all instances.

## Example
```php
'msie' => array(
	'version'  => '9',
	'operator' => '<',
),
```
In this example, we define an Internet-Explorer setting that will be redirect all user with a Internet-Explorer version smaler 9. As operator you can use the known ones **>**, **<**, **=**.

The browser key's are used from the user agend's, that are sent by each browser. We implemented all basic browser's to the example config. If you need a special browser, please add a new key after one main key.