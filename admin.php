<?php
    require_once 'connection.php';

    $users = $DB->query("SELECT name FROM burger ");
    $users = $users->fetchAll();

    echo "Cписок всех зарегистрированных пользователей.";
    echo "<table border=\"1\">";
    foreach ($users as $value)
    {
        echo "<tr><td>".$value['name']."</td></tr>";
    }
    echo "</table><br>";
    echo "Cписок всех заказов.";

   $order = $DB->query("SELECT `name`, `order` FROM orderburger  ");
   $order = $order->fetchAll();

    echo "<table border=\"1\">";
    foreach ($order as $item)
    {
        echo "<tr><td>".$item['name']."</td><td>".$item['order']."</td></tr>";
    }
    echo "</table>";
