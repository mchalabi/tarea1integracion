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
  
  return $app->json('Http 201', 201);
 // return json_encode('Http 201');
});

$app->get('/texto', function() use($app) {
  $texto = file_get_contents("https://s3.amazonaws.com/files.principal/texto.txt");
  $sha2562= hash('sha256', $texto);
  $devolver2 = $texto . ', ' . $sha2562;
  return $app->json($devolver2, 201)
});


$app->post('/validarfirma', function() use($app) {
  $value1= $_REQUEST('mensaje');
  $value2= $_REQUEST('hash');

  /*foreach (getallheaders() as $name => $value) {
    
    if ($name == null){
    	return json_encode('Http 400');
    }
    if ( is_string(gettype($name)) == false){
    	return json_encode('Http 400');
    } 
  }*/

  $sha256= hash('sha256', $value1);

  /*if($sha256 == null){
  	return json_encode('Htpp 500');
  }*/
  
  if($sha256== $value2){
  	$devolver= value1 . ' true';
  }
  else{
    $devolver= value1 . ' false'; 
  }

  //return json_encode($value1);
  return $app->json($devolver, 201);

});


$app->run();
