<?php

require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID'];
    
 
    if (is_numeric($id)) {
        
        
        $sql_actuacions = "DELETE FROM actuacio_de_incidencia WHERE id_incidencia = ?";
        $stmt_act = $conn->prepare($sql_actuacions);
        $stmt_act->bind_param("i", $id);
        
    
        if (!$stmt_act->execute()) {
            echo "<p class='error'>Error al eliminar actuacions: " . htmlspecialchars($stmt_act->error) . "</p>";
        }
        $stmt_act->close();

      
        $sql = "DELETE FROM Incidencies WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        
        if ($stmt->execute()) {
            $stmt->close();

            header("Location: esborrada.html");
            exit();
        } else {
            echo "<p class='error'>Error al esborrar l'incidència: " . htmlspecialchars($stmt->error) . "</p>";
            $stmt->close();
        }
    } else {

        echo "<p class='error'>ID no vàlid.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Incidència</title>
    <link rel="stylesheet" href="proyecte.css">
</head>
<body>
    <header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="llista_admin.php">LLISTA DE INICIDÈNCIES</a></button>
        </div>
        <h1>ESBORRAR INCIDÈNCIA</h1>
    </header>

    <?php

    if (isset($_GET['ID'])) {
        $id = $_GET['ID'];
        
        
        if (is_numeric($id)) {
            
            $sql = "SELECT ID FROM Incidencies WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
               
                echo "<form method='POST' action='esborrar.php'>";
                echo "<fieldset><h1>ELIMINAR INCIDÈNCIA</h1>";
                echo "<p>Estàs segur que vols eliminar aquesta incidència amb ID: ". htmlspecialchars($id) . "?</p>";
                echo "<input type='hidden' name='ID' value='" . htmlspecialchars($id) . "'>";
                echo "<button style='display:inline-block; margin-top:10px; background-color:red; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Sí, esborrar</button>";
                echo "</fieldset>";
                echo "</form>";
            } else {
                echo "<p class='error'>No s'ha trobat l'incidència amb ID: " . htmlspecialchars($id) . "</p>";
            }
        } else {
            echo "<p class='error'>ID no vàlid.</p>";
        }
    } else {
        echo "<p class='error'>No s'ha especificat cap ID.</p>";
    }
    ?>
 <script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelector('h1').classList.add('animar');
});
</script>
</body>
</html>
