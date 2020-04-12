<?php
require_once 'connection.php';


$name = htmlspecialchars($_POST['name']);
$phone = htmlspecialchars($_POST['phone']);
$email = htmlspecialchars($_POST['email']);
$street = htmlspecialchars($_POST['street']);
$home = htmlspecialchars($_POST ['home']);
$part = htmlspecialchars($_POST ['part']);
$appt = htmlspecialchars($_POST ['appt']);
$floor = htmlspecialchars($_POST ['floor']);
$comment = htmlspecialchars($_POST ['comment']);
$orders = 'DarkBeefBurger за 500 рублей, 1 шт';



function UserAuth($name,$DB,$phone,$email)
{
    $result = $DB->query("SELECT * FROM users_id WHERE Name LIKE '%" . $name . "%'". "AND phone LIKE'%".$phone."%'");

    if ($result->rowCount() > 0) {
        echo "<b>$name</b>, вы уже зарегистрированы, мы собираем Ваш заказ";

    } else {
        echo "Спасибо - это ваш первый заказ";
        $query ="INSERT INTO users_id (`name`,`phone`,`email`) VALUES (:name,:phone,:email)";
        $prepared = $DB->prepare($query);
        $ret = $prepared->execute(['name'=>$name, 'phone'=>$phone, 'email'=>$email]);
    }
}

function UserOrder($DB,$name,$street,$home,$part,$appt,$floor,$comment,$phone,$orders)
{
    $userId = $DB->query("SELECT id FROM users_id WHERE Name LIKE '%" . $name . "%'". "AND phone LIKE'%".$phone."%'");
    $userId = $userId->fetch()[0];

    $query = "INSERT INTO orders (`User_id`,`street`,`home`,`part`,`appt`,`floor`,`comment`,`userOrder`)
                VALUES (:userId,:street,:home,:part,:appt,:floor,:comment,:userOrder)";

    $prepared = $DB->prepare($query);
    $ret = $prepared->execute(['userId'=>$userId, 'street'=>$street, 'home'=>$home,
                            'part'=>$part, 'appt'=>$appt, 'floor'=>$floor, 'comment'=>$comment, 'userOrder'=>$orders]);

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
UserOrder($DB,$name,$street,$home,$part,$appt,$floor,$comment,$phone,$orders);
Order($name,$DB,$street,$home,$appt,$UserRoot,$phone);









