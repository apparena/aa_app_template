#!/bin/sh
cd ..
yuidoc .
doxx --source "source/js" --target "source/doc/app/doxx" --ignore "vendor,build,dist" --title "AppArena App template" --template "build/doxxtemplate.jade"