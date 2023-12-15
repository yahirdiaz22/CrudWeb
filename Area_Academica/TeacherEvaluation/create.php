<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "areaacademicabn";
$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Fallo de conexión: " . $connection->connect_error);
}

$date = "";
$calification = "";
$idSubject = "";
$errorMessage = "";
$successMessage = "";

// Obtener las materias para el combobox
$sqlMaterias = "SELECT * FROM subject";
$resultMaterias = $connection->query($sqlMaterias);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST["date"];
    $calification = $_POST["calification"];
    $idSubject = $_POST["idSubject"];

    do {
        if (empty($date) || empty($calification) || empty($idSubject)) {
            $errorMessage = "Llena todos los campos";
            break;
        }

        // Modificar la consulta para establecer el status a 1
        $sql = "INSERT INTO teacherevaluation (date, calification, idSubject, status)" .
                "VALUES ('$date', '$calification', '$idSubject', 1)";
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Consulta inválida: " . $connection->error;
            break;
        }

        $date = "";
        $calification = "";
        $idSubject = "";
        $successMessage = "Se añadió la evaluación del profesor";

    } while (false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Project</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Agregado el siguiente script para el selector de fecha -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>Nueva Evaluación del Profesor</h2>
        <?php
        if (!empty($errorMessage)){
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>$errorMessage</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            "; 
        }
        ?>
        <form method="post">
            <div class="col-sm-6">
                <label class="col-sm-3 col-form-label">Fecha</label>
                <div class="col-sm-6">
                    <!-- Input para la fecha con el selector -->
                    <input type="text" class="form-control datepicker" name="date" value="<?php echo $date; ?>">
                </div>
            </div>
            <div class="col-sm-6">
                <label class="col-sm-3 col-form-label">Calificación</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="calification" value="<?php echo $calification; ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <label class="col-sm-3 col-form-label">Materia</label>
                <div class="col-sm-6">
                    <!-- Combobox para seleccionar la materia -->
                    <select class="form-control" name="idSubject">
                        <?php
                        while ($rowMateria = $resultMaterias->fetch_assoc()) {
                            echo "<option value='{$rowMateria['idSubject']}'>{$rowMateria['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php
            if (!empty($successMessage)){
                echo "
                <div class='row mb-3'>
                    <div class='offset-sm-3 col-sm-6'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>Registro Correcto</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>
                    </div>
                </div>
                ";
            }
            ?>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid" >
                    <button type="submit" class="btn btn-light btn-sm'">Agregar</button>
                </div>
                <div class="col-sm-3 d-grid" >
                <a class="btn btn-light btn-sm'" href="/web/Area_Academica/TeacherEvaluation/tableTeacherEvaluation.php" role="button">Regresar</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Script para inicializar el selector de fecha -->
    <script>
        $(document).ready(function(){
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });
    </script>
</body>
</html>
