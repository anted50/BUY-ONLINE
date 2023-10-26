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

        if (isset($_POST["gcy"])) {
            $gcy2 = (int) $_POST["gcy"];
            // echo $gcy2;
            $stringFile = file_get_contents("goods.xml");
            $xml = new SimpleXMLElement($stringFile);

            $customerId = (string) $_SESSION["id"];
            // echo $xml->customer->attributes();
            $parent = $xml->customer;
            if ($xml->customer->attributes() == $customerId) {
                $parent = $xml->addChild("customer");
                $parent->addAttribute("customer_id", $customerId);
                $child = $parent->addChild("items", 1);
                $child->addAttribute("item_id", $gcy2);
                $xml->asXML("goods.xml");
            } else {
                if (!contains($xml, $gcy2)) {
                    foreach ($xml->customer as $customer) {
                        if ((int) $customer['customer_id'] == $customerId) {
                            $elementToModify = $customer;
                            break;
                        }
                    }
                    if ($elementToModify !== null) {
                        $newChild = $elementToModify->addChild('items', 1);
                        $newChild->addAttribute('item_id', $gcy2);
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
                            if ((int) $items['item_id'] == $gcy2) {
                                $val = (int) $items[0] + 1;
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
                    $item_id = (string) $items['item_id'];
                    echo '|' . $item_id . ',' . $items;
                }

            }
        }
    }
    function contains($xml, $val)
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
        foreach ($xml->customer as $customer) {
            $customer_id = (string) $customer['customer_id'];
            if ($customer_id == $val) {
                return true;
            }
        }
        return false;
    }
}


// function xml_attribute($object, $attribute){
//     if(isset($object[$attribute]))
//         return (int) $object[$attribute];
// }
?>