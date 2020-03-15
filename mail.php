<?php
    require_once 'connection.php';

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $street = $_POST['street'];
    $home = $_POST ['home'];
    $part = $_POST ['part'];
    $appt = $_POST ['appt'];
    $floor = $_POST ['floor'];
    $comment = $_POST ['comment'];
    $order = 'DarkBeefBurger за 500 рублей, 1 шт';


    function User($name,$order,$DB)
    {
        $result = $DB->query("SELECT * FROM burger WHERE Name LIKE '%" . $name . "%'");

        if ($result->rowCount() > 0) {
            echo "Вы уже зарегистрированы, мы собираем Ваш заказ <b>$name</b>";

        } else {
            echo "Спасибо,что выбрали нас. Мы собираем Ваш заказ <b>$name</b>";
        }

        $query = "INSERT INTO orderburger (`name`,`order`)VALUES ('$name','$order')";
        $DB->query($query);
    }
    function Order($name,$DB,$street,$home,$appt)
    {
        date_default_timezone_set('Asia/Yekaterinburg');
        $a = Date('d m Y H.i');
        $file = 'Заказ '.$name. " ".$a.' .txt';

        $orderID= $DB->query("SELECT MAX(id) FROM orderburger ");
        $messageID= $DB->query("SELECT COUNT(id) FROM orderburger WHERE name='$name'");

        $message = "Заказ № ". $orderID->fetch()[0]. ".<br> Ваш заказ будет доставлен по адресу: ул. " . $street
            . " дом № ".$home . " квартира № ". $appt. "Спасибо! Это уже ". $messageID->fetch()[0]. " заказ";



        if ($put=false)
        {
            file_put_contents ("order/$file",$message);
        }
        else{
            $put= mkdir('order',FILE_APPEND);
            file_put_contents ("order/$file",$message);
        }
    }

    User($name,$order,$DB);
    Order($name,$DB,$street,$home,$appt);










