<?php
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

    $DB = new PDO('mysql:dbname=db_posts;127.0.0.1','root','');

    $result = $DB->query("SELECT * FROM burger WHERE Name LIKE '%".$name."%'");

    if ($result->rowCount()>0)
    {
        echo "Вы уже зарегистрированы, мы собираем Ваш заказ <b>$name</b>";

    }else{
//        $query = "INSERT INTO burger (`name`,`phone`,`email`,`street`,`home`,`part`,`appt`,`floor`,`comment`)
//              VALUES ('$name','$phone','$email','$street','$home','$part','$appt','$floor','$comment')";
//        $reg=$DB->query($query);
        echo "Спасибо,что выбрали нас. Мы собираем Ваш заказ <b>$name</b>";
    }

    $query = "INSERT INTO orderburger (`name`,`order`)VALUES ('$name','$order')";
    $reg=$DB->query($query);



    //echo $order;
   // date_default_timezone_set('Asia/Yekaterinburg');
   // $a = Date(d.".".m.".".Y." ".H.":".i);
    //$file = 'people '.$a.' .txt';

    $to = $email; // куда отправить
    $messageID= $DB->query("SELECT COUNT(id) FROM orderburger WHERE name='$name'");
    //$messageID->fetch()[0];// тема № заявки
    $message = "Спасибо! Это уже ". $messageID->fetch()[0]. " заказ";
    echo "<br>". $message;

    //mail($email, $subject, $message);


        $to      = 'nobody@example.com';
        $subject = 'the subject';
        $message = 'hello';
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

