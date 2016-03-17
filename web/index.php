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
  

  return json_encode(array('texto'=>$texto, 'sha256'=>$sha2562));
});


$app->post('/validarFirma', function() use($app) {
  $value1= $_REQUEST ['mensaje'];

  $value2= $_REQUEST ['hash'];
  $minus= strtolower($value2);
  
  /*foreach (getallheaders() as $name => $value) {
    
    if ($name == null){
    	$variable1 = $app->json('Http error 400', 400);
    	return  $variable1;
    }
    if ( is_string(gettype($name)) == false){
    	$variable1 = $app->json('Http error 400', 400);
    	return  $variable1;
    } 
  }*/

  $sha2561= hash('sha256', $value1);

 /* if($sha2561 == null){
  	return  $app->json('Http error 500', 500);
  }*/
  
  if($sha2561 == $minus){
  	$bool = true;
  	$array = array('valido'=>$bool, 'mensaje'=>$value1);
  	$var = json_encode($array);

  	return $var;
  }
/*
  else{
  	$bool1 = false;
  	$var2 = json_encode(array('valido'=>$bool1, 'mensaje'=>$value1));
  	return $var2;
  }*/

});


$app->run();
