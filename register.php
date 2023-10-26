<?php
$firstName = $_POST["firstName"];
$password = $_POST["password"];
$email = $_POST["email"];

$stringFile = file_get_contents("customer.xml");
// $xmlFile = simplexml_load_file($stringFile);
$xml = new SimpleXMLElement($stringFile); 
  
$count = count($xml);

$parent = $xml -> addChild("customer"); 
$parent -> addChild("customer_id", $count);
$parent -> addChild("firstName", $firstName); 
$parent -> addChild("email", $email); 
$parent -> addChild("password", $password); 
echo $xml->asXML("customer.xml"); 

?>