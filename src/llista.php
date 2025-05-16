<?php
require_once 'connection.php';
require 'connection_Mongo.php';  // Incluir la función para registrar logs

registrarLog('/llista.php');

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat d'incidències</title>
    <link rel="stylesheet" href="proyecte.css">
    <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
<header>
    <div class="btn-group">
        <button type="button" class="btn btn-primary"><a href="tecnic.php">TECNIC</a></button>
    </div> 
    <h1>LLISTAT DE INCIDÈNCIES</h1>
</header>

<fieldset class="filtre">
    <label for="filtreEmpleat">Filtrar per Empleat:</label>
    <select id="filtreEmpleat">
        <option value="">Tots</option>
        <option value="Ricardo">Ricardo</option>
        <option value="Joel">Joel</option>
        <option value="Iker">Iker</option>
    </select>    

    <label for="filtreEstat">Filtrar per estat:</label>
    <select id="filtreEstat">
        <option value="">Tots</option>
        <option value="No Fet">No Fet</option>
        <option value="En Proces">En Proces</option>
        <option value="Fet">Fet</option>
    </select>

    <label for="filtrePrioritat">Filtrar per prioritat:</label>
    <select id="filtrePrioritat">
        <option value="">Tots</option>
        <option value="Alta">Alta</option>
        <option value="Mitja">Mitja</option>
        <option value="Baixa">Baixa</option>
    </select>

    
</fieldset>

<fieldset class="llistat">
<?php
$sql = "SELECT i.ID, u.Nom AS UsuariNom , epl.Nom AS NomEmpleat, d.Nom_Departament,
               e.Estat AS EstatText, p.Nivel_de_Prioritat AS PrioritatText,
               i.Descripcio, i.Fecha
        FROM Incidencies i
        JOIN Usuari u ON i.Usuari = u.DNI
        LEFT JOIN Empleat epl ON i.Empleat = epl.DNI
        JOIN Departament d ON i.Departament = d.ID
        JOIN Estat e ON i.Estat = e.ID
        JOIN Prioritat p ON i.Prioritat = p.ID
        ORDER BY p.ID DESC";


$result = $conn->query($sql);



if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $color = '';
        switch ($row["PrioritatText"]) {
            case 'Alta': $color = 'red'; break;
            case 'Mitja': $color = 'orange'; break;
            case 'Baix': $color = 'rgb(31, 122, 140); '; break ; 
}
        echo "<div class='incidencia' data-empleat='" . htmlspecialchars($row["NomEmpleat"] ?? 'No assignat') . "' data-estat='" . htmlspecialchars($row["EstatText"] ?? 'Sense estat') . "' data-prioritat='" . htmlspecialchars($row["PrioritatText"] ?? 'No assignat') . "'>";
        echo "<div class='ticket' style='border: 3px solid $color; padding-left: 10px;'>";
        echo "<div class='ID'><strong>ID:</strong> " . htmlspecialchars($row["ID"]) . "</div>";
        echo "<div class='camp'><strong>Usuari:</strong> " . htmlspecialchars($row["UsuariNom"] ?? 'No trobat') . "</div>";
        echo "<div class='camp'><strong>Empleat:</strong> " . htmlspecialchars($row["NomEmpleat"] ?? 'No assignat') . "</div>";
        echo "<div class='camp'><strong>Departament:</strong> " . htmlspecialchars($row["Nom_Departament"] ?? 'No assignat') . "</div>";
        echo "<div class='camp'><strong>Estat:</strong> " . htmlspecialchars($row["EstatText"] ?? 'Sense estat') . "</div>";
        echo "<div class='camp'><strong>Prioritat:</strong> <span style='color: $color; font-weight:bold;'>" . htmlspecialchars($row["PrioritatText"] ?? 'No assignat') . "</span></div>";
        echo "<div class='camp'><strong>Data:</strong> " . htmlspecialchars($row["Fecha"]) . "</div>";
        echo "<div class='camp'><strong>Descripció:</strong></div>";
        echo "<div class='descripcio'>" . nl2br(htmlspecialchars($row["Descripcio"])) . "</div>";
        echo "<a href='esborrar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px; margin-right:10px; background-color:red; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Esborrar</a>";
        echo "<a href='modificar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px; margin-right:10px; background-color: rgb(31, 122, 140); color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Actuacio</a>";
        echo "<a href='descripcio_actuacions_tecnic.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px; background-color: green; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>descripcions</a><hr>";
        echo "</div>"; // fin .ticket
        echo "</div>"; 
    }
} else {
    echo "<p>No hi ha cap incidència per mostrar.</p>";
}

$conn->close();
?>
</fieldset>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtreEmpleat = document.getElementById('filtreEmpleat');
    const filtreEstat = document.getElementById('filtreEstat');
    const filtrePrioritat = document.getElementById('filtrePrioritat');
    const incidencies = document.querySelectorAll('.incidencia');

    function filtrar() {
        const empleatSeleccionat = filtreEmpleat.value;
        const estatSeleccionat = filtreEstat.value;
        const prioritatSeleccionada = filtrePrioritat.value;

        incidencies.forEach(incidencia => {
            const empleat = incidencia.getAttribute('data-empleat');
            const estat = incidencia.getAttribute('data-estat');
            const prioritat = incidencia.getAttribute('data-prioritat');

            const mostrar =
                (empleatSeleccionat === '' || empleat === empleatSeleccionat) &&            
                (estatSeleccionat === '' || estat === estatSeleccionat) &&
                (prioritatSeleccionada === '' || prioritat === prioritatSeleccionada);

            incidencia.style.display = mostrar ? 'block' : 'none';
        });
    }
    filtreEmpleat.addEventListener('change', filtrar);
    filtreEstat.addEventListener('change', filtrar);
    filtrePrioritat.addEventListener('change', filtrar);
});
</script>
</body>
</html>
