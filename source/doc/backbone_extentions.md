# Extended View, Model, Collection functions
`Sources: ./source/js/utils/apparena`
> All Views, Models and Collections in official modules and the template are extended with basic code to initialize and remove/destroy instances of the objects. That means, you have a uniform solution to initialize Views for example and destroy them (inclusive their event listener) with a uniform function call. You can extend this functions or create new solutions or additional jobs, that automatically provided by existing objects. Existing source code is reduced and duplicated outsourced to a separate file.

> To see, which functions are outsourced and exist, please take a look into the file under **„/source/js/utils/apparena“**. The important files are **View.js**, **Model.js** and **Collection.js**

> All functions are stored in the ReturnObj Object, that will be returned after loading by RequireJs. By default, the code part is empty and must be filled by and Backbone function.

## ReturnObj
The ReturnObj class stores functionality to extend a AMD module, that handles initialization and removing objects of a Backbone view. Namespace and code must be set by the AMD module.

```javascript
ReturnObj = {
    init:        Init,
    code:        null,      // required a Backbone.[View|Model|Collection] Object, must be set in the AMD module
    namespace:   '',        // required a unique name as key for the singleton - example: modulename_objecktname
    remove:      Remove,
    getInstance: Instance
};
```

## Init(settings)
Initialize handler wrapper, to return an object from Instance() that is stored in a singleton object. The Init() function handles the job, which function must be called on an initialization. That means, if an instance of the called object exists, it returns this one. Otherwise it creates a new instance. With the setting parameter you can arrange a new initialization and overwriting an existing object in the singleton storage object. Otherwise you can define here a specific object ID and some attributes, that is needed in the Backbone object. The setting parameter is not required.

`@param settings {Object} Not required - JSON string with settings for attributes and view id`
`@returns {Object}`

#### Call example 1: AMD module to initialize a Backbone view
```javascript
// first init a new View and render something to the DOM
var view = View().init(); // returnes an Backbone view instance object, otherwise, its chainable
view.render()
$('body').html(view.el);
```

#### Call example 2: chainable version
```javascript
// first init a new View and render something to the DOM
View().init().render();
```

## GetInstanze(settings)
Called by the Init() function to creates a new instance and store them into view singleton object. The parameter settings is not required. It's only to overwrite proberties of an view, model or collection.

`@param settings {Object} Not required - JSON string with settings for attributes and view id`

## Instance()
Returns an instance from the global singleton storage. This function can be called by .getInstance() or better over the Init() function. If you know, that the instance exist, you can call this function directly.

#### Call example
```javascript
// first init a new View and render something to the DOM
View().getInstance();
```

## Remove()
Destroys an Backbone objects (incl. singleton storage) and existing event listener. In views, this function removes generated HTML from the set view element property.

#### Call example: AMD module to initialize a Backbone view
```javascript
// first init a new View and render something to the DOM
View().init().render();

// now destroy the object and remove the HTML
View().remove();
```