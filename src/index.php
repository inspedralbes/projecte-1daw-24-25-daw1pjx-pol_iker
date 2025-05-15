<?php
require 'connection_Mongo.php';  // Incluir la función para registrar logs

// Registrar el log cuando el usuario entra en la página index
registrarLog('/index.php');
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
    <header><h1>Gestor d'incidencies Institut Pedralbes</h1></header>
    

    <div class="container mt-5 d-flex justify-content-center">
        <div class="row g-4" style="max-width: 1000px; margin-top: 60px;"> 

          <div class="col-md-4" class="tarjeta">
            <a href="usuari.php">
            <div class="card text-center">
              <div class="card-body">
                <img src="usuari.png" width="265px"   class="mb-3" alt="Formulario">
                <h5 class="card-title">USUARI</h5>
                <p class="card-text"></p>
              </div>
            </div>
            </a>
          </div>
          <div class="col-md-4"class="tarjeta">
              <a href="tecnic.php">
            <div class="card text-center">
              <div class="card-body">
                <img src="tecnic2.png" width="265px" class="mx-auto d-block mb-3" alt="Llista">
                <h5 class="card-title">TECNIC</h5>
                <p class="card-text"></p>
              </div>
            </div>
            </a>
          </div>
          <div class="col-md-4" class="tarjeta">
              <a href="admin.php">
            <div class="card text-center">
              <div class="card-body">
                <img src="admin.jpg" width="265px" class="mx-auto d-block mb-3" alt="Llista">
                <h5 class="card-title">ADMIN</h5>
                <p class="card-text"></p>
              </div>
            </div>
            </a>
          </div>
        </div>
      </div>
</body>
</html>