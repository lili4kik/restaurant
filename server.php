<?php
// Указываем, что сервер возвращает JSON
header('Content-Type: application/json; charset=utf-8');

// Разрешаем запросы с любого источника (для локальной разработки)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Проверяем, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешен. Используйте POST.']);
    exit;
}

// Получаем данные из тела запроса (в формате JSON)
$inputData = file_get_contents('php://input');
$data = json_decode($inputData, true);

// Проверка на то, что JSON удалось распарсить
if ($data === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Ошибка формата данных. Ожидается JSON.']);
    exit;
}

$name = isset($data['name']) ? trim($data['name']) : '';
$age = isset($data['age']) ? $data['age'] : null;

// ================= СЕРВЕРНАЯ ВАЛИДАЦИЯ =================
if (empty($name)) {
    http_response_code(400);
    echo json_encode(['error' => 'Имя не может быть пустым.']);
    exit;
}

// Проверяем, является ли возраст числом и больше ли он нуля
if (!is_numeric($age) || $age <= 0 || $age > 150) {
    http_response_code(400);
    echo json_encode(['error' => 'Возраст должен быть положительным числом (от 1 до 150).']);
    exit;
}

// Приводим возраст к целому числу
$age = (int)$age;

// ================= ФОРМИРОВАНИЕ ОТВЕТА =================
$message = "Привет, {$name}! Тебе {$age} лет.";

// Отправляем успешный ответ
http_response_code(200);
echo json_encode(['message' => $message], JSON_UNESCAPED_UNICODE);