<?php
session_start();
$_SESSION['usuario'] = 'tecnic';

require_once 'connection_Mongo.php';
registrarLog($_SERVER['REQUEST_URI']);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="proyecte.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Gestor d'Incidencies</title>
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
    <header><h1>TECNIC</h1></header>
    

    <div class="container mt-5 d-flex justify-content-center">
        <div class="row g-4" style="max-width: 1000px; margin-top: 60px;"> 
          <div class="col-md-6" id="tarjeta">
              <a href="llista.php">
            <div class="card text-center">
              <div class="card-body">
                <img src="llista2.png" width="265px" class="mx-auto d-block mb-3" alt="Llista">
                <h5 class="card-title">LLISTAT</h5>
                <p class="card-text"></p>
              </div>
            </div>
            </a>
          </div>
          <div class="col-md-6" id="tarjeta">
            <a href="index.php">
            <div class="card text-center">
              <div class="card-body">
              <img src="exit2.png" width="232px"   class="mb-3" alt="Formulario">
                <h5 class="card-title">LOG OUT</h5>
                <p class="card-text"></p>
              </div>
            </div>
            </a>
          </div>
        </div>
      </div>
       <script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('h1').classList.add('animar');
});
</script>
</body>
</html>