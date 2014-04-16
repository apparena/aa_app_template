#!/bin/bash

# USEFULL PATHS
TEMPLATE_REPO="https://github.com/apparena/aa_app_template.git"
TEMPLATE_DIR="aa_app_template"

# define directory variables
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

#cd ../source/doc/app/doxx
#rm -R *
#cd ../yuidoc
#rm -R *
#cd ../../../..
#cd ../source/doc/app
#rm -R *
#cd ../../../
#yuidoc .
#doxx --source "source/js" --target "source/doc/app/doxx" --ignore "vendor,build,dist" --title "AppArena App template" --template "build/doxxtemplate.jade"

function cleanup () {
    cd ${DIR}
    cd ../source/doc/app
    rm -R *
}

function startyuidoc () {
    cd ${DIR}
    cd ../source
    cd ${1}
    yuidoc .
}

function startdoxx () {
    cd ${DIR}
    cd ../source
    CURRENTDIR=$(pwd)
    cd ${1}
    echo ${1}
    #doxx --source "source/js" --target "source/doc/app/doxx" --ignore "vendor,build,dist" --title "AppArena App template" --template "build/doxxtemplate.jade"
    doxx --target "${CURRENTDIR}/doc/app/doxx" --ignore "vendor,build,dist" --title "Gewinnspiel App" --template "${DIR}/doxxtemplate.jade"
}

#cleanup
#startyuidoc js
#startyuidoc modules
#startdoxx js
#startdoxx modules