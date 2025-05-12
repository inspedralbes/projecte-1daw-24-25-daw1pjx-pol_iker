<?php
require_once 'connection.php';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Llistat d'incidències</title>
    <link rel="stylesheet" href="proyecte.css">
     <link rel="shortcut icon" href="pedralbres.ico" type="image/x-icon">
</head>
<body>
<header>
        <div class="btn-group">
            <button type="button" class="btn btn-primary"><a href="index.php">PAGINA INICIAL</a></button>
            <button type="button" class="btn btn-primary"><a href="crear.php">FORMULARI DE INICIDÈNCIES</a></button>
            <button type="button" class="btn btn-primary"><a href="asignar.php">LLISTAT DE INCIDENCIE NO ASSIGNADES</a></button>

        </div> 
        <h1>LLISTAT DE INICIDÈNCIES</h1>
</header>

<!-- Filtres -->
<fieldset class="filtre">
    <form method="GET" action="">
        <label for="filtreEstat">Filtrar per estat:</label>
        <select id="filtreEstat" name="estat">
            <option value="">Tots</option>
            <option value="No Fet" <?= ($_GET['estat'] ?? '') == 'No Fet' ? 'selected' : '' ?>>No Fet</option>
            <option value="En Proces" <?= ($_GET['estat'] ?? '') == 'En Proces' ? 'selected' : '' ?>>En Proces</option>
            <option value="Fet" <?= ($_GET['estat'] ?? '') == 'Fet' ? 'selected' : '' ?>>Fet</option>
        </select><br>

        <label for="filtrePrioritat">Filtrar per prioritat:</label>
        <select id="filtrePrioritat" name="prioritat">
            <option value="">Tots</option>
            <option value="Alta" <?= ($_GET['prioritat'] ?? '') == 'Alta' ? 'selected' : '' ?>>Alta</option>
            <option value="Mitja" <?= ($_GET['prioritat'] ?? '') == 'Mitja' ? 'selected' : '' ?>>Mitja</option>
            <option value="Baixa" <?= ($_GET['prioritat'] ?? '') == 'Baixa' ? 'selected' : '' ?>>Baixa</option>
        </select>

        <button type="submit">Aplicar filtre</button>
    </form>
</fieldset>

    <fieldset class = "llistat">
    <?php
    $estat = $_GET['estat'] ?? '';
    $prioritat = $_GET['prioritat'] ?? '';

    $sql = "SELECT i.ID, u.Nom AS UsuariNom , epl.Nom AS NomEmpleat, d.Nom_Departament, e.Estat AS EstatText, i.Descripcio, i.Fecha,p.Nivel_de_Prioritat AS PrioritatText
            FROM Incidencies i
            JOIN Usuari u ON i.Usuari = u.DNI
            LEFT JOIN Empleat epl ON i.Empleat = epl.DNI
            JOIN Departament d ON i.Departament = d.ID
            JOIN Estat e ON i.Estat = e.ID
            JOIN Prioritat p ON i.Prioritat = p.ID";

if ($estat !== '') {
    $sql .= " AND e.Estat = '" . $conn->real_escape_string($estat) . "'";
}
if ($prioritat !== '') {
    $sql .= " AND p.Nivel_de_Prioritat = '" . $conn->real_escape_string($prioritat) . "'";
}

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p class='incidencia' data-estat='" . htmlspecialchars($row["EstatText"]) . "' data-prioritat='" . htmlspecialchars($row["PrioritatText"]) . "'>";
            echo "<p><strong>ID:</strong> " . $row["ID"] . "<br>";
            echo "<strong>Usuari:</strong> " . htmlspecialchars($row["UsuariNom"]) . "<br>";
            echo "<strong>Empleat:</strong> " . htmlspecialchars($row["NomEmpleat"] ?? 'No assignat') . "<br>";
            echo "<strong>Departament:</strong> " . htmlspecialchars($row["Nom_Departament"]) . "<br>";
            echo "<strong>Estat:</strong> " . htmlspecialchars($row["EstatText"]) . "<br>";
            echo "<strong>Prioritat:</strong> " . htmlspecialchars($row["PrioritatText"]) . "<br>";
            echo "<strong>Descripció:</strong> " . htmlspecialchars($row["Descripcio"]) . "<br>";
            echo "<strong>Data:</strong> " . htmlspecialchars($row["Fecha"]) . "<br>";
            echo "<a href='esborrar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px; margin-right:10px; background-color:red; color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Esborrar</a>";
            echo "<a href='modificar.php?ID=" . $row["ID"] . "' style='display:inline-block; margin-top:10px;  background-color: rgb(31, 122, 140); color:white; text-decoration:none; padding:8px 12px; border-radius:5px;'>Modificar</a><hr>";
        }
    } else {
        echo "<p>No hi ha dades a mostrar.</p>";
    }

    $conn->close();
    ?>
    </fieldset>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtreEstat = document.getElementById('filtreEstat');
    const filtrePrioritat = document.getElementById('filtrePrioritat');
    const incidencies = document.querySelectorAll('.incidencia');
    const missatge = document.getElementById('missatgeBuit');

    function filtrar() {
        const estatSeleccionat = filtreEstat.value.trim().toLowerCase();
        const prioritatSeleccionada = filtrePrioritat.value.trim().toLowerCase();
        let visibles = 0;

        incidencies.forEach(function (incidencia) {
            const estat = (incidencia.dataset.estat || '').trim().toLowerCase();
            const prioritat = (incidencia.dataset.prioritat || '').trim().toLowerCase();

            let mostrar = true;

            if (estatSeleccionat !== 'tots' && estat !== estatSeleccionat) {
                mostrar = false;
            }

            if (prioritatSeleccionada !== 'tots' && prioritat !== prioritatSeleccionada) {
                mostrar = false;
            }

            if (mostrar) {
                incidencia.style.display = 'block';
                visibles++;
            } else {
                incidencia.style.display = 'none';
            }
        });

        missatge.style.display = visibles === 0 ? 'block' : 'none';
    }

    filtreEstat.addEventListener('change', filtrar);
    filtrePrioritat.addEventListener('change', filtrar);
    filtrar(); // Filtrar al iniciar
});
</script>

</body>
</html>
