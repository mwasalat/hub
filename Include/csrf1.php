<?php
/*
@class: CSRFProtect v0.1
@desc: Yet another simple protecion for CSRF attacks!
@author: C1c4Tr1Z <research@voodoo-labs.org>
@homepage:http://voodoo-labs.org/
@parm legend:
()<= Optional
{}<= Strict

@TODO: (*)Use it with $_COOKIE
(*)Add more encryption methods ('XOR' for example)
(*)Make it "legal" for PHP Classes standarts
(*)Make it more portable for extended propouses
*/

class csrfProtect{

//Encryption methods used by the class
private $cryptMethods=array("md5", "sha1", "crc32", "crypt");
public $cryptMeth=null;
public $csrfMessage=null;
//The uber l33t token:
private $token=null;

/*
@name: __construct
@desc: Constructor.
@parm: {String}
*/
function __construct($crypt=null){

//Default encryption type
$this->cryptMeth="sha1";
//Default detected CSRF message. You can use it to redirect or log the attempts using PHP
$this->csrfMessage=
"echo \"<h1>CSRF detected!</h1><br/><pre>\";
htmlspecialchars((print_r($_REQUEST)),ENT_QUOTES);
echo \"</pre>\";";

if(!empty($crypt) && in_array($crypt, $this->cryptMethods))
$this->cryptMeth=$crypt;
}

/*
@name: createToken
@desc: Creates a random token.
@parm: (String)
*/
private function createToken($salt=null){
$plainToken="";
if(empty($salt)){
$a=(rand()*rand());
$b=rand();
$plainToken=uniqid(($a+$b),true);
}else{
$plainToken=$salt.uniqid(rand(),true);
}
return $plainToken;
}

/*
@name: csrfProtection
@desc: Gives you the hidden input, with a random token and the posibility to choose the desired encryption method.
@parm: {String} (String) (String)
*/
public function csrfProtection($input_name, $salt=null, $crypt=null){
if(empty($input_name))
die("Error!");
if(!empty($salt)){
$token=$this->createToken($salt);
}else{
$token=$this->createToken();
}
if(!empty($crypt) && in_array($crypt, $this->cryptMethods))
$this->cryptMeth=$crypt;

$encryption=$this->cryptMeth;
$final_token=$encryption($token);
$input="<input type=\"hidden\" name=\"$input_name\" value=\"$final_token\">x0a";
$_SESSION[$input_name]=$final_token;

return $input;

}

/*
@name: checkCSRF
@desc: checks for Cross-Site Request Forgeries and validates.
@parm: {String} (String) {String}
*/
public function checkCSRF($name, $http="GET"){
if(empty($name) || empty($http) || !eregi("^(GET|POST){1}$",$http))
die("Error!");
$method=($http=="GET")?$_GET:$_POST;
if(isset($method[$name]))
if(!strcmp($method[$name], $_SESSION[$name])==0)
$this->csrfResponse();
return 0;
}

/*
@name: checkCSRF
@desc: Executes a response when an attack its detected!
@parm: NULL
*/
private function csrfResponse(){

return die(eval($this->csrfMessage));
}
}
?>



