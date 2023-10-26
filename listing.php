<?php
session_start();
if (isset($_POST["logou"])) {
    print_r($_SESSION["managerId"]);
    session_unset();
} else {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $desc = $_POST["desc"];

    $file = file_get_contents("data/goods.xml");
    $xml = new SimpleXMLElement($file);

    $count = count($xml) + 1;

    $parent = $xml->addChild("item");
    $parent->addChild("itemId", $count);
    $parent->addChild("name", $name);
    $parent->addChild("price", $price);
    $parent->addChild("quantity", $quantity);
    $parent->addChild("hold", $quantity);
    $parent->addChild("description", $desc);

    $xml->asXML("data/goods.xml");
}
?>