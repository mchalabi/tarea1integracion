<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->get('/status', function() use($app) {
  
  return json_encode('Http 201');
});

$app->get('/texto', function() use($app) {
  
  return json_encode('Http 201');
});


$app->post('/validarfirma', function() use($app) {
  $value1= $_REQUEST('mensaje');
  $value2= $_REQUEST('hash');

  foreach (getallheaders() as $name => $value) {
    
    if ($name == null){
    	return 'Http 400';
    }
    if ( is_string(gettype($name)) == false){
    	return 'Http 400';
    } 
  }

  $sha256= hash('sha256', $value1);

  //if($sha256 == null){
  	//return json_encode('Htpp 500');
  //}
  
  if($sha256== $value2){
  	$devolver= value1 . ' true';
  }
  else{
    $devolver= value1 . ' false'; 
  }

  return $devolver;

});


$app->run();
