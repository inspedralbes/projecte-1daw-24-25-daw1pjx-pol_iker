<?php
require 'connection_Mongo.php'; 

registrarLog('/confirmat.php');

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=ç, initial-scale=1.0">
    <title>Formulari Validat</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
    <div style="display: flex; justify-content: center; align-items: center; min-height: 10vh;"></div>
   <fieldset style="margin-top: 10vh; width: 90%; max-width: 600px; padding: 20px; box-sizing: border-box;">
       <h2 class="validacio">S'ha fet correctament</h2>
       <a href="index.php" style="color: blue;">TORNAR A LA PAGINA PRINCIPAL</a>
   </fieldset>
</div>
</body>
</html>