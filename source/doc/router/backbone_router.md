# BackboneJS router
With the backboneJS router, we handle all the internel JS requests, to load single pages, elements or do RESTful/AJAX requests. Information to the basic router functions are published on the [vendor page](http://backbonejs.org/#Router), but we implemented some more features.

## Routing structure
### Module call
* load module main page: **#/mod/[MODULENAME]**
* load module page that's not named main: **#/mod/[MODULENAME]/[FILENAME]**
* load module page with parameters: **#/mod/[MODULENAME]/[FILENAME]/[PARAM]/[PARAM]/[PARAM]/[...]**

All this calls are routed into the directory **/sources/modules/[MODULENAME]/js/[FILENAME]** by using **mod** as first url parameter. The MODULENAME must be only the shortname ogf the module, without the prefix **aa_app_mod_**.
For example: static (#/mod/static) will be call the module aa_app_mod_static and here the main.js.

### App file call
Additional to the module calls, we cann call files from our app directory. This will be happen over the **page** parameter in the url instead of **mod**. With page we call a file from the **/sources/js/[FILENAME]**.
* load page by filename: **#/page/[FILENAME]**
* load page file drom subdirectory: **#/page/[DIRECTORY]/[FILENAME]**
* load page file with parameters: **#/page/[DIRECTORY]/[FILENAME]/[PARAM]/[PARAM]/[PARAM]/[...]**

### Use router without loading anything, but changing body class
We implemented a call function that will do nothing ... ok it do something, but not loading anything. With this call, you can modify the css class in the body tag, without loading a page file. In some cases we need this function, to change the url (for example to call a module file again with a click) but not loose the body class.

If you call **#/call/[NAME]**, the router adds the name as class to the body tag. On each other call, the router adds the module or filename as body class. With this solution, you can style app parts for each module oder call.

## Router functions
* **goToPreviewsAction** - url redirect to previews action (call method)
* **goToPreviewsPage** - url redirect to previews page (mod method) (currently only for mod calles and not for page calles)
* **redirection** - same lik goto but as global call and not only over views
	* type - call type (mod, page, call)
	* page - module name or filename or string of both ('' or '/' for main, 'auth', 'auth/login')

## View prototype functions
* **goTo** - redirection methode to change URL and call new module/file over the backbone router
	* location - example: '/', '/mod/[MODULENAME]/[FILENAME]'
	* trigger - stop trigger the call function if false. Default: true (not required)
* **ajax** - Shortcut to start an jQuery ajax call
	* data - JSON data object for the request
	* async - true or false (not required)
	* callback - callback function on success call (not required)
* **log** - Shortcut for logging something over the logging module. You find more information in the logging documentation.
* **destroy** - undelegate view events, remove a previously-stored piece of data, unbind jquery events