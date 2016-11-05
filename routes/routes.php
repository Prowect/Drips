<?php

$router->group(['middleware' => 'web'], function($router){
	$router->get('/', function(){
		return view('welcome');
	});	
});
