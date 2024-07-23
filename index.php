<?php
// index.php

// Подключение к API Kommo CRM
function kommo_api_request($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

// Функция для проверки занятости времени задачи
function is_time_slot_taken($user_id, $start_time, $end_time)
{
    $tasks = kommo_api_request('GET', 'https://kommo.com/api/v4/tasks', [
        'responsible_user_id' => $user_id,
        'filter[date]' => [
            'from' => $start_time,
            'to' => $end_time
        ]
    ]);

    return count(json_decode($tasks, true)) > 0;
}

// Получение данных из POST-запроса
$input = json_decode(file_get_contents('php://input'), true);

$user_id = $input['user_id'];
$start_time = $input['start_time'];
$end_time = $input['end_time'];

$response = [
    'is_taken' => is_time_slot_taken($user_id, $start_time, $end_time)
];

echo json_encode($response);
?>
