===================================
=     App-Arena.com App Template      =
===================================
Github: 	https://github.com/apparena/aa_app_template
Docs: 		http://www.app-arena.com/docs/display/developer

File structure:
-----------------------------------
- css						--> Alle css-resources and libraries
- img						--> All necessary image files
- js
	- bootstrap.js			--> Default Bootstrap js libary including all effects (http://twitter.github.com/bootstrap/javascript.html)
	- plugins.js			--> All javascript libraries will be merged in this file (this will reduce the number of http-requests for loading external js libs)
	- scripts.js			--> All custom js functions merged in one file (this will reduce the number of http-requests for loading external js libs)
-  libs
	- AA					--> Main App-Arena files to connect to the App-Manager and some helper classes
	- fb-php-sdk			--> Official facebook php sdk 
- modules					--> App modules, which can be integrated easily into this app-template
- templates					--> Template files which can be loaded easily via ajax
- config.php				--> Main configuration file, which needs to be configured for each new app
- index.php					--> Main index file, which loads all other content dynamically
- init.php					--> File to be included in each template file to establish the app-manager connection and the session to work with
- readme.md					--> Central readme file with more information about this app


Install Bower and requirejs/r.js on windows:
-----------------------------------
1. Downlaod nodejs and install it: http://nodejs.org/
2. Open a GIT console and enter:
 - npm install requirejs -g
 - npm install bower -g

Get vendor sources:
-----------------------------------
Open a GIT console, change into the app sources directory and enter:
 - bower install

Compile sources:
-----------------------------------
Open a GIT console, change into the app sources/build directory and enter:
 - ./build.sh

Install new app packages:
-----------------------------------
Open a GIT console, change into the app sources directory and enter:
 - bower install <PACKAGENAME> or <URL TO ZIP PACKAGE>

To install save the package to the app install/update process enter:
 - bower install <PACKAGENAME> or <URL TO ZIP PACKAGE> --save