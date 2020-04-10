<?php
    require_once 'connection.php';

    $orders = $DB->query("SELECT * FROM orders LEFT JOIN users_id ON orders.User_id = users_id.id");
    $orders = $orders->fetchAll();

    $users = $DB->query("SELECT * FROM users_id");
    $users = $users->fetchAll();


    echo "Cписок всех зарегистрированных пользователей.";
    echo "<table border=\"1\">";
    echo "<tr><td><b>Имя</b></td><td><b>Телефон</b></td><td><b>E-mail</b></td></tr>";
    foreach ($users as $value)
    {
        echo "<tr><td>".$value['name']."</td><td>".$value['phone']."</td><td>".$value['email']."</td></tr>";
    }
    echo "</table><br>";

    echo "Cписок всех заказов.";


    echo "<table border=\"1\">";
    echo "<tr><td><b>№</b></td><td><b>Имя</b></td><td><b>Улица</b></td><td><b>Дом</b></td><td><b>Корпус</b>
            </td><td><b>Квартира</b></td><td><b>Этаж</b></td><td><b>Комментарий</b></td></tr>";
    foreach ($orders as $item)
    {
        echo "<tr><td>".$item[0]."</td><td>".$item['name']."</td><td>".$item['street']."</td><td>".$item['home']."</td>
        <td>".$item['part']."</td><td>".$item['appt']."</td><td>".$item['floor']."</td><td>".$item['comment']."</td></tr>";
    }
    echo "</table>";
