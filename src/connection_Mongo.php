<?php
require 'vendor/autoload.php';

function registrarLog($urlVisitada) {
    date_default_timezone_set('Europe/Madrid');

    try {
        $client = new MongoDB\Client("mongodb+srv://admin:secure1234@cluster0.qdhihk6.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
        $db = $client->ProjecteFinal;
        $logsCollection = $db->logs;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'no autenticado';
        $timestamp = date("Y-m-d H:i:s");
        $navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'desconocido';

        $log = [
            'url_visitada' => $urlVisitada,
            'usuari' => $usuario,
            'timestamp' => $timestamp,
            'navegador' => $navegador
        ];

        $logsCollection->insertOne($log);

    } catch (Exception $e) {
        error_log("Error al conectar con MongoDB: " . $e->getMessage());
    }
}
