<?php
require 'vendor/autoload.php';
require_once 'dbcon.php';
include_once( 'classes/messages.php' );
spl_autoload_register( function( $class ) {
		require_once 'classes/' . $class . '.php';
	});
$db = DB::getInstance( 'Config' );
$auth = new Auth( 'sha256' );
$app = new \Slim\Slim( array(
			'debug' => true,
			'mode' => 'production',
			'templates.path' => './templates'
		)
	);
// Define DB resource
$app->container->singleton('db', function () {
	return DB::getInstance( 'Config' );
} );
$app->add( $auth );
//$app = new \Slim\Slim();
$app->get( '/', 'Routes:getProfile' );

$app->get( '/login', 'Routes:getLogin')->name("login");

$app->post( '/profile', 'Routes:postProfile')->name("profile");

$app->get( '/profile', 'Routes:getProfile')->name("profileget");

$app->get( '/userdata', 'Routes:getProfileData');

$app->get( '/questions', 'Routes:getQuestions');

$app->get( '/questions/:id', 'Routes:getQuestion');

$app->post( '/questions/submit/:id', 'Routes:postAnswer');

$app->get( '/init', 'Routes:getInit');

$app->get( '/signup', 'Routes:getSignup');

$app->post( '/signup', 'Routes:postSignup');

$app->get( '/logout', 'Routes:getLogout');

$app->run();
