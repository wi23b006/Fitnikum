<?php

session_start();

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

if (isset($_GET["action"])) {

    if ($_GET["action"] == "add") {

        $id = $_GET["id"];
        $name = $_GET["name"];
        $price = $_GET["price"];

        if (isset($_SESSION["cart"][$id])) {
            $_SESSION["cart"][$id]["quantity"] = $_SESSION["cart"][$id]["quantity"] + 1;
        } else {
            $_SESSION["cart"][$id]["id"] = $id;
            $_SESSION["cart"][$id]["name"] = $name;
            $_SESSION["cart"][$id]["price"] = $price;
            $_SESSION["cart"][$id]["quantity"] = 1;
        }

        header("Location: ../../Frontend/pages/warenkorb.php");
        exit;
    }

    if ($_GET["action"] == "remove") {

        $id = $_GET["id"];

        unset($_SESSION["cart"][$id]);

        header("Location: ../../Frontend/pages/warenkorb.php");
        exit;
    }

    if ($_GET["action"] == "plus") {

        $id = $_GET["id"];

        $_SESSION["cart"][$id]["quantity"] = $_SESSION["cart"][$id]["quantity"] + 1;

        header("Location: ../../Frontend/pages/warenkorb.php");
        exit;
    }

    if ($_GET["action"] == "minus") {

        $id = $_GET["id"];

        $_SESSION["cart"][$id]["quantity"] = $_SESSION["cart"][$id]["quantity"] - 1;

        if ($_SESSION["cart"][$id]["quantity"] <= 0) {
            unset($_SESSION["cart"][$id]);
        }

        header("Location: ../../Frontend/pages/warenkorb.php");
        exit;
    }
}

echo "cart.php";

?>