#!/bin/sh
r.js -o app.build.js
cd ../dist/
rm -rf js/vendors
#rm js/router.js
#rm js/app.js
rm js/boilerplate.js
rm -rf tests
rm -rf doc
rm build.txt
rm bower.json
rm .bowerrc