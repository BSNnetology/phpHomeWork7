<?php
    ini_set('display_errors','Off');
    error_reporting(E_ALL);
    
    // -- Проверим, передан ли файл, или вернем ошибку
    if ($_FILES['content']['size'] === 0) {
        sendResult('Файл для сохранения не выбран');
    }

    // -- Проверим, передано ли имя сохранения, или вернем ошибку
    if (empty($_POST['file_name'])) {
        sendResult('Имя для сохранения файла не выбрано');
    }
    
    // -- Установим папку - приемник файлов
    $uploadDir = __DIR__ . '/files/';

    // -- Создадим папку, если она отсутсвует
    if(!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // -- Получим расширение файла - источника
    $sourceEx = pathinfo($_FILES['content']['name'])['extension']; 
    // -- Получим расширение файла - приемника
    $receptEx = pathinfo($_POST['file_name'])['extension']; 
    // -- Получим итоговое имя файла
    $uploadFile = $_POST['file_name'] . ($sourceEx === $receptEx ? '' : ('.' . $sourceEx));
    
    if (file_exists(filename: $uploadDir . $uploadFile)) {
        sendResult('Файл "' . $uploadFile . '" уже существует...');
    } elseif (move_uploaded_file($_FILES['content']['tmp_name'],$uploadDir . $uploadFile)) {
        sendResult('Файл успешно сохранен:|||' . $uploadDir . $uploadFile);
    } else {
        sendResult('Не удалось сохранить файл: ' . $uploadFile);
    }
    
    /* ----------- Перейдем на исходную страницу с возвратом ответа Get --------- */
    function sendResult(string $result): void {
        header("Location: " . $_SERVER["HTTP_REFERER"] . '?res=' . $result);
        exit;
    }