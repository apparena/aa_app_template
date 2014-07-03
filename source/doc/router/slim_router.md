# Slim router
The slim framework router is the main router in our app template. We can deligate all RESTFUL request to the right controller and his action. Additional we handle the main app requests like assets, ID and language checks and redirections to expire, error, cache delete and similar pages.

Additional we can handle each type of request (GET, POST, PUT, DELETE) and connect them with a single controller action. We define our routes in the routes.php file in our config directory. Here you find an array with all option. Normaly the key defines the URL part that will be maped to the right action part. You can define some placeholder in the key part, that will be available as variable in the action method. Please take a look at the examples below. Each key starts and ends with an /.

## Routing structure
**[URL pattern] => [Class]:[Function]@[Method]**
or something bedder
**[URL pattern] => [Controller]:[Action]@[HTTP Method]**

In the value part are all parameters optional. Each part has a default value:
- Controller = Main
- Action = index (indexAction)
- HTTP Method = all

It's important to add the word "Action" to your class functions as suffix.
Example:
- function = **index**
- function name = **indexAction**

## Routing with ...
There are different ways to create a news route:
### ... one action for all request types
```php
'/:i_id/:lang/idbyfb/' => 'Main:idbyfb'
```
This example uses 2 variables at the beginning. **:i_id** and **:lang** are available as **$i_id** and **$lang** as first and second parameter in our idbyfbAction method. This HTTP request will be match on every HTTP request type (GET, POST, PUT, DELETE), if the third part is **idbyfb/** and no more parts follow.

### ... one optional placeholder
```php
'/:i_id/:lang/share/(:base/)' => 'Share'
```
Same as before, but now with a thirt placeholder on fourth part in our URL. If you saround a placeholder with () you can define it as optional. PLease be sure, that the last / is in the (). ***Example: (:base/)***

### ... a special HTTP request method
```php
'/:i_id/:lang/' => 'Main:index@get'
```
This request will be maped only on a get request.

### ... different HTTP request methods but same URL
```php
'/:i_id/:lang/' => array(
	'get'    => 'Main:missingId',
	'post'   => 'Main:missingId',
	'put'    => 'Ajax:missingId',
	'delete' => 'Ajax:missingId',
),
```
With an additional array as value, you can map your request to different actions, one by each HTTP request method.