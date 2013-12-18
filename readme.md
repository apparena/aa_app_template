
# App-Arena.com App Template

Github: https://github.com/apparena/aa_app_template

Docs:   http://www.app-arena.com/docs/display/developer

## File structure:

- .vagrant
- build
```
Build/Compile scripts and configurations for r.js and/or yoeman
```
- dist
```
Compiled app (Stage and process files)
```
- files
```
Dotfiles for vagrant. During initial startup, they will automatically be copied into the VM.
```
- puppet
```
puppet configurations for vagrant
```
- shell
```
vagrant shell scripts
```
- source
```
app source files (developer area)
```
    - config
    ```
    app configuration files for php, css sources, requirejs ...
    ```
    - css
    ```
    basic css
    ```
    - docs
    ```
    documentations and base files like sql files or server configurations, history ...
    ```
    - img
    ```
    images, sprites
    ```
    - js
        - bootstrap.js
        - plugins.js
        - scripts.js
    -  libs
        - AppArena
        - Zend
    - modules					--> App modules, which can be integrated easily into this app-template
    - templates					--> Template files which can be loaded easily via ajax
    - tests
    - tmp
    - config.php				--> Main configuration file, which needs to be configured for each new app
    - index.php					--> Main index file, which loads all other content dynamically
    - init.php					--> File to be included in each template file to establish the app-manager connection and the session to work with
- .bowerrc
```
bower configuration to install vendor packages to source/js/vendor
```
- .gitignore
```
global git ignore list
```
- .jshintrc
```
jshint configuration file to copy or global use in IDE
```
- .hiera.yaml
```
one of the puppet configuration files for vagrant
```
- readme.md
```
Central readme file with more information about this app
```
- Vagrant
```
Vagrant configuration file
```

## Install needed sources

### Install Bower, requirejs/r.js on windows:
1. Downlaod nodejs and install it from: http://nodejs.org/
2. Open a GIT console and enter:

    ```
    npm install requirejs -g
    ```

    ```
    npm install bower -g
    ```

### Get vendor sources:
Open a GIT console, change into the app sources directory and enter:
```
bower install
```

### Compile sources:
Open a GIT console, change into the app sources/build directory and enter:
```
./build.sh
```

### Install new app packages:
Open a GIT console, change into the app sources directory and enter:
```
bower install <PACKAGENAME> or <URL TO ZIP PACKAGE>
```

To save the package to the app install/update process enter:
```
bower install <PACKAGENAME> or <URL TO ZIP PACKAGE> --save
```