<?php
require 'vendor/autoload.php';

function registrarLog($urlVisitada) {
    // Establecer la zona horaria correcta
    date_default_timezone_set('Europe/Madrid');

    try {
        // Conectar con la base de datos MongoDB
        $client = new MongoDB\Client("mongodb+srv://projecte_Iker_Ricardo_Pol:Pol-Ricardo-Iker@projectemongo.5pcntao.mongodb.net/?retryWrites=true&w=majority&appName=ProjecteMongo");
        $db = $client->projecte_Iker_Ricardo_Pol;
        $logsCollection = $db->logs;

        // Obtener el usuario (si está autenticado)
        $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'no autenticado';

        // Obtener el timestamp
        $timestamp = date("Y-m-d H:i:s");

        // Obtener el navegador
        $navegador = $_SERVER['HTTP_USER_AGENT'];

        // Crear el registro del log
        $log = [
            'url_visitada' => $urlVisitada,
            'usuari' => $usuario,
            'timestamp' => $timestamp,
            'navegador' => $navegador
        ];

        // Insertar el log en la base de datos
        $logsCollection->insertOne($log);

    } catch (Exception $e) {
        echo "Error al conectar con MongoDB: " . $e->getMessage();
    }
}
?>

