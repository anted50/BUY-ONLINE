<?php
session_start();
$username = $_POST["username"];
$password = $_POST["password"];

$file = "data/manager.txt";
$handle = fopen($file, "r");

while (!feof($handle)) {
    $line = fgets($handle);
    $mValue = explode(",", $line);
    $curUsername = trim($mValue[0]);
    $curPassword = trim($mValue[1]);
    if ($curUsername == $username) {
        if ($curPassword == $password) {
            $_SESSION["managerId"] = $curUsername;
            fclose($handle);
            die("success");
        } else {
            die("incorrect password");
        }
    }
}
echo "not found";
fclose($handle);
?>