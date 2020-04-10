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



function UserAuth($name,$DB,$phone,$email)
{
    $result = $DB->query("SELECT * FROM users_id WHERE Name LIKE '%" . $name . "%'". "AND phone LIKE'%".$phone."%'");

    if ($result->rowCount() > 0) {
        echo "<b>$name</b>, вы уже зарегистрированы, мы собираем Ваш заказ";

    } else {
        echo "Спасибо - это ваш первый заказ";
        $DB->query("INSERT INTO users_id (`name`,`phone`,`email`) VALUES ('$name','$phone','$email')");
    }
}

function UserOrder($DB,$name,$street,$home,$part,$appt,$floor,$comment,$phone)
{
    $userId = $DB->query("SELECT id FROM users_id WHERE Name LIKE '%" . $name . "%'". "AND phone LIKE'%".$phone."%'");
    $userId = $userId->fetch()[0];

    $DB->query("INSERT INTO orders (`User_id`,`street`,`home`,`part`,`appt`,`floor`,`comment`)
                VALUES ('$userId','$street','$home','$part','$appt','$floor','$comment')");


}

function Order($name,$DB,$street,$home,$appt,$UserRoot,$phone)
{
    $orderID = $DB->query("SELECT MAX(id) FROM orders "); // номер заказа
    $subject = "Заказ № " . $orderID->fetch()[0];

    $userId = $DB->query("SELECT id FROM users_id WHERE Name LIKE '%" . $name . "%'". "AND phone LIKE'%".$phone."%'");
    $userId = $userId->fetch()[0];

    $messageID = $DB->query("SELECT COUNT(id) FROM orders WHERE User_id='$userId'"); // кол-во заказов
    $messageID = $messageID->fetch()[0];

    $messageOrder = "Дорогой, ".$name.". Ваш заказ будет доставлен по адресу: ул. " . $street
        . " дом № " . $home . " квартира № " . $appt . ". Спасибо! Это уже " . $messageID . " заказ";

    try {
        $transport = new Swift_SmtpTransport($UserRoot['host'], $UserRoot['port'], $UserRoot['encryption']);
        $transport->setUsername($UserRoot['Username']);
        $transport->setPassword($UserRoot['password']);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
            ->setFrom(['Buzuluk56-1@yandex.ru' => 'Buzuluk56-1@yandex.ru'])
            ->setTo(['buzuluk56@yandex.ru'])
            ->setBody($messageOrder);

        $mailer->send($message);

    } catch (Exception $e) {
        var_dump($e->getMessage());
        echo '<pre>' . print_r($e->getTrace(), 1);
    }
}


UserAuth($name,$DB,$phone,$email);
UserOrder($DB,$name,$street,$home,$part,$appt,$floor,$comment,$phone);
Order($name,$DB,$street,$home,$appt,$UserRoot,$phone);









