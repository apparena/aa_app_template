# Controller function overview
Here you find a list of classes, dunctions and methods to use in the slim controller. You can use all slim framework functions, too. For more information, take a look into the [vendor documentation](http://docs.slimframework.com/).

## Functions
### $this->addBasicLayoutData();
define basic variables and settings for layout rendering

### $this->isFacebook();
check facebook sign request

### $this->defineApi();
define API settings and initialization

### $this->callApi();
call appmanager api and get all instance information

### $this->before($i_id = 0, $lang = APP_DEFAULT_LOCALE);
setup some things before we call the main method
* **@param int**    $i_id API instance ID
* **@param string** $lang language settings

### $this->after();
setup some things after we call the main method

### $this->display($data = array(), $status = null);
display rendered data with layout template
* **@param array** $data   template data as array
* **@param null**  $status http header status

### $this->redirect($url = '/', $status = 302)
Redirect (overwritten the slim standard)

> This method immediately redirects to a new URL. By default, this issues a 302 Found response; this is considered the default generic redirect response. You may also specify another valid 3xx status code if you want. This method will automatically set the HTTP Location header for you using the URL parameter.

* **@param string** $url    The destination URL
* **@param int**    $status The HTTP redirect status code (optional)

## Variables
### [Array] $_data
Storage for template information.
Example:
```php
$this->_data = array('app_content' => $this->render('pages/index'));
```
This will be add a rendered index template to the variable app_content and can be used in a mustage template.

### [Array|NULL] $_sign_request
Facebook sign_request information, if the function isFacebook() was called.

### [Array] $_request
The result of the slim request result (\Slim\Http\Request)

### [Array] $_status
Set and stored current HTTP header status. Default is 200.

### [Boolean] $_render
Default is true, but with false, you can stop the template rendering process

### [Object] $db
Database object. Can be used by initializing with the global statement in the needed method.

### [Object] \Apparena\App
* ::$i_id - stored instance id
* ::$locale - stored current language
* ::$api - Object with API settings and functions
* ::$_app_data - Object with API settings and functions
* ::$_signed_request - Object with Facebook sign_request information
* ::setLocale($locale, $this) - define current language for API response with first parameter, second must be $this (the slim object)
* ::getCurrentDate() - Returns the current date as object

## Modifiy slim config
To modify the slim configuration (see config file slim-config.php), use $this->config($key, $value). For example, with this you can change the base template ($this->config('templates.base', 'pages/error')) or other thinks.