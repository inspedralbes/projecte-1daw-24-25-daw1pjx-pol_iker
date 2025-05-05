<?php
// Configuración de la base de datos
$servername = "db";
$username = "usuari";
$password = "paraula_de_pas";
$dbname = "incidencia";

// Conexión
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recoger los datos del formulario
$departament = $_POST['departament'];
$tipus_text = $_POST['Tipus_incidencia'];
$numero_pc = $_POST['numero'];
$descripcio = $_POST['descripcio'];
$empleat_dni = $_POST['dni'];

// Buscar el ID del tipo de incidencia
$tipus_stmt = $conn->prepare("SELECT ID FROM Tipus_Incidencia WHERE Tipus_de_Incidencia = ?");
$tipus_stmt->bind_param("s", $tipus_text);
$tipus_stmt->execute();
$tipus_result = $tipus_stmt->get_result();
if ($tipus_row = $tipus_result->fetch_assoc()) {
    $tipus_id = $tipus_row['ID'];
} else {
    die("Error: Tipo de incidencia no válido.");
}

// Asignar estado por defecto (ej: pendiente = ID 1)
$estat_id = 1;

// Inserción en la tabla de incidencias
$stmt = $conn->prepare("INSERT INTO Incidencies (Tipus, Estat, Empleat, Numero_PC, Descripcio, Fecha) VALUES (?, ?, ?, ?, ?, CURDATE())");
$stmt->bind_param("iisss", $tipus_id, $estat_id, $empleat_dni, $numero_pc, $descripcio);

if ($stmt->execute()) {
    echo "<p>Incidencia añadida correctamente.</p>";
    echo "<p><a href='llista.php'>Ver lista</a></p>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

