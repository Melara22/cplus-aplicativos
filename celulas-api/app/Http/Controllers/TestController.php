<?php

namespace App\Http\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class TestController 
{

	protected $container;

 	public function __construct($container){
 		$this->container = $container;
 	} 

 	public function __get($property)
 	{
 	    if ($this->container->{$property}) {
 	        return $this->container->{$property};
 	    }
 	}

   	public function home($request, $response, $args) {
   		$con = $this->db; 
     	$email = $request->getParam('email');
     	$password = $request->getParam('password');

     	if(empty($email)){
       	return $response->withStatus(500)
               ->withHeader('Content-Type', 'application/json')
               ->withJson(array('error' => array(
                "type"=>"required",
                 "message"=>"Parámetro faltante: email",
                 "status"=>500)));
     	} else if(empty($password)){
        return $response->withStatus(500)
                ->withHeader('Content-Type', 'application/json')
                ->withJson(array('error' => array(
                  "type"=>"required",
                  "message"=>"Parámetro faltante: password",
                  "status"=>500)));
    	}

    	$fpass = md5($password);

    	$pre_em = $con->prepare("SELECT *
    	                         FROM user
    	                         WHERE email = :email AND password = :password");

    	$values_em = array(':email' => $email, ':password' => $fpass);
    	$pre_em->execute($values_em);
    	$result_em = $pre_em->fetch();

    	if ($result_em) {
    	  return $response->withStatus(200)
    	          ->withHeader('Content-Type', 'application/json')
    	          ->withJson(array('response' => $result_em));
    	}else{
    		return $response->withStatus(422)
    		        ->withHeader('Content-Type', 'application/json')
    		        ->withJson(array('error' => array(
    		            "message"=>"Don't exist",
    		            "status"=>422)));
    	}

   	}	

}