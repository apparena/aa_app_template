#!/bin/sh
r.js -o app.build.js
cd ../dist/
rm -rf js/vendors
rm -rf tests
rm -rf doc
rm build.txt
rm .bowerrc