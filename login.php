<?php
session_start();

$email = $_POST["email"];
$password = $_POST["password"];
$stringFile = file_get_contents("customer.xml");
// $xmlFile = simplexml_load_file($stringFile);
$xml = new SimpleXMLElement($stringFile);

for ($i = 0; $i < count($xml); $i++) {
    // $emailXml = $xml["customer"][$i]["email"];
    $emailXml = $xml->customer[$i]->email;
    $passwordXml = $xml->customer[$i]->password;
    if ($email == $emailXml){
        if ($password == $passwordXml) {
            $customerId = $xml->customer[$i]->customer_id->__toString();
            $_SESSION["id"] = $customerId;
            die("Success");
        } else {
            die("incorrect password or email");
        }
    }
}
echo "not found";

?>