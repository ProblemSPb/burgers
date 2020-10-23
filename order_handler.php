<?php

/**
 * Обработчик формы заказов
 * так как я уже умею работать с БД на базовом уровне, а с JSON - нет,
 * то в этом задании я решила работать с JSON,
 * но в пункте 4 "Сохранить данные заказа - id пользователя, сделавшего заказ, дату заказа, полный адрес клиента"
 * сохраняю только имейл в качестве ID и счетчик заказов
 */

/**
 * Функция выводит сообщение при успешном заказе
 * @param $address_data - из $_POST
 * @param $current_orders_count
 */
function printSuccessMessage($address_data, $current_orders_count)
{
    // генерим номер заказа
    $id = rand(300, 1000);

    // если поле корпус не заполнено, не показываем его в адресе
    $korpus = empty($address_data['part']) ? "" : (", корпус " . $address_data['part']);
    $address = "Улица " . $address_data['street'] . ", дом " .$address_data['home'] .
        $korpus . ", квартира " . $address_data['appt'] . ", этаж " . $address_data['floor'];

    // выводим статус на экран
    echo "Спасибо, ваш заказ будет доставлен по адресу: $address<br>
Номер вашего заказа: $id<br>
Это ваш $current_orders_count заказ!";
}

// проверка, если в POST есть данные
if (isset($_POST)) {
    $email = $_POST['email'];
    $address_data = $_POST;

    // получаем данные из файла
    $file_pointer = fopen("users.json", "r+");
    if (file_get_contents("users.json")) {
        $file_data = file_get_contents("users.json");
        $array_data = json_decode($file_data, true);

        $email_found = false;

        // ищем пользователя в базе клиентов
        foreach ($array_data as &$item) {
            // если найден
            if (in_array($email, $item)) {
                $email_found = true;

                // считаем общее число заказов для клиента, включая текущий из формы
                $current_orders_count = $item['orders_number'] + 1;
                // обновляем данные в массиве
                $item['orders_number'] = $current_orders_count;

                // выводим на экран
                printSuccessMessage($address_data, $current_orders_count);
            }
        }

        // пользователь не найден в базе
        if (!$email_found) {
            // подготавливаем данные для записи
            $new_user = [
                "email" => $email,
                "orders_number" => 1
            ];
            array_push($array_data, $new_user);

            // генерим номер заказа
            $id = rand(300, 1000);
            // число заказов для нового клиента = 1
            $current_orders_count = 1;

            printSuccessMessage($address_data, $current_orders_count);
        }

        // обновляем данные в файле json
        $json_data = json_encode($array_data);
        file_put_contents("users.json", $json_data);

    } else {
        // если файл не удалось открыть
        error_log("Error while getting the data from the file.");
        die();
    }
    fclose($file_pointer);
}


// TODO:
// проверка, если в POST есть данные
/*if (empty($_POST['email'])) {
    header("404");
    exit();
}*/

// разнести на файлы
// убрать email из глобальной области видимости
//$email = $_POST['email'];
//$address_data = $_POST;

// получение имейла

// добавление имейла

// добавление заказа

// печать сообщения

// main to collect them all
/*function main($data)
{
    echo 1;
}*/