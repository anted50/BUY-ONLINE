<?php
session_start();
if (isset($_SESSION["id"])) {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $stringFile = file_get_contents("goods.xml");
        $xml = new SimpleXMLElement($stringFile);
        for ($i = 0; $i < $xml->item->count(); $i++) {
            // $emailXml = $xml->customer[$i]->email;
            // $passwordXml = $xml->customer[$i]->Password;
            $itemId = $xml->item[$i]->itemId;
            $name = $xml->item[$i]->name;
            $description = $xml->item[$i]->description;
            $price = $xml->item[$i]->price;
            $quantity = $xml->item[$i]->quantity;
            echo "|" . $itemId . "," . $name . "," . $description . "," . $price . "," . $quantity;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST["iId"])) {
            $op = $_POST["op"];
            $iId2 = (int) $_POST["iId"];
            // echo $iId2;
            $stringFile = file_get_contents("goods.xml");
            $xml = new SimpleXMLElement($stringFile);

            $quantity = $xml->item[$iId2 - 1]->quantity;
            if ($op == "add") {
                $quantity = $quantity - 1;
            } else {
                $quantity = $quantity + 1;
            }
            $xml->item[$iId2 - 1]->quantity = $quantity;
            $xml->asXML("goods.xml");

            $customerId = (string) $_SESSION["id"];
            // echo $xml->customer->attributes();
            $parent = $xml->customer;
            if (!containsUser($xml, $customerId)) {
                $parent = $xml->addChild("customer");
                $parent->addAttribute("customer_id", $customerId);
                $child = $parent->addChild("items", 1);
                $child->addAttribute("item_id", $iId2);
                $xml->asXML("goods.xml");
            } else {
                if (!contains($xml, $iId2, $customerId)) {
                    foreach ($xml->customer as $customer) {
                        if ((int) $customer['customer_id'] == $customerId) {
                            $elementToModify = $customer;
                            break;
                        }
                    }
                    if ($elementToModify !== null) {
                        $newChild = $elementToModify->addChild('items', 1);
                        $newChild->addAttribute('item_id', $iId2);
                        $xml->asXML("goods.xml");
                    }
                } else {
                    foreach ($xml->customer as $customer) {
                        if ((int) $customer['customer_id'] == $customerId) {
                            $elementToModify = $customer;
                            break;
                        }
                    }
                    if ($elementToModify !== null) {
                        foreach ($elementToModify->items as $items) {
                            if ((int) $items['item_id'] == $iId2) {
                                if ($op == "add") {
                                    $val = (int) $items[0] + 1;
                                } else {
                                    $val = (int) $items[0] - 1;
                                }
                                $items[0] = $val;
                                // print_r($items);
                                $xml->asXML("goods.xml");
                            }
                        }
                    }
                }
            }

            foreach ($xml->customer as $customer) {
                if ((int) $customer['customer_id'] == $customerId) {
                    $elementToModify = $customer;
                    break;
                }
            }
            if ($elementToModify !== null) {
                foreach ($elementToModify->items as $items) {
                    $item_id = (int) $items['item_id'];
                    $price = $xml->item[$item_id - 1]->price;
                    echo '|' . $item_id . ',' . $items . ',' . $price;
                }

            }
        }

        
    }
}
function contains($xml, $val, $customerId)
{
    foreach ($xml->customer->items as $item) {
        $item_id = (string) $item['item_id'];
        if ($item_id == $val) {
            return true;
        }
    }
    return false;
}
function containsUser($xml, $val)
{
    for ($i = 0; $i < count($xml->customer); $i++) {
        $customer = $xml->customer[$i];
        $customer_id = (string) $customer['customer_id'];
        if ($customer_id == $val) {
            return true;
        }
    }
    return false;
}



// function xml_attribute($object, $attribute){
//     if(isset($object[$attribute]))
//         return (int) $object[$attribute];
// }
?>