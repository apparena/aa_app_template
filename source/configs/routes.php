<?php
$app->get('/', function ()
{
    echo "Home Page";
});

$app->get('/testPage', function () use ($app)
{
    echo "TEST Page";
    #$app->render('testpage.php');
});