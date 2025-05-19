<?php
require 'vendor/autoload.php';

use MongoDB\Client;

try {
    $client = new Client("mongodb+srv://admin:secure1234@cluster0.qdhihk6.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
    $db = $client->ProjecteFinal;
    $logsCollection = $db->logs;
    $logs = $logsCollection->find([], ['sort' => ['timestamp' => -1]]);
} catch (Exception $e) {
    die("Error al conectar con MongoDB: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Registro de Logs</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>

    <header>
        <div class="btn-group">
        <button type="button" class="btn btn-primary"><a href="admin.php">ADMIN</a></button>
        <button type="button" class="btn btn-primary"><a href="llista_admin.php">LLISTA DE INICIDÈNCIES</a></button>
        <button type="button" class="btn btn-primary"><a href="asignar.php">LLISTAT DE INCIDENCIES NO ASIGNADES</a></button>
        <button type="button" class="btn btn-primary"><a href="informe_tecnic.php">INFORME</a></button>
        <h1>HISTORIAL DE LOGS</h1>    
    </div> 
    </header>

    <div style ="margin: 5rem; margin-top: 15rem;">
        <table class="llistat_no_asignades">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>URL Visitada</th>
                    <th>Fecha y Hora</th>
                    <th>Navegador</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr style="background-color: white;">
                        <td><?= htmlspecialchars($log['usuari']) ?></td>
                        <td><?= htmlspecialchars($log['url_visitada']) ?></td>
                        <td><?= htmlspecialchars($log['timestamp']) ?></td>
                        <td><?= htmlspecialchars($log['navegador']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>

  const filas = document.querySelectorAll('table.llistat_no_asignades tbody tr');

  filas.forEach(fila => {
    fila.addEventListener('mouseenter', () => {
      fila.style.backgroundColor = '#cceeff'; 
      fila.style.cursor = 'pointer';
    });
    fila.addEventListener('mouseleave', () => {
      fila.style.backgroundColor = 'white'; 
    });
  });
</script>
 <script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('h1').classList.add('animar');
});
</script>
</body>
</html>
