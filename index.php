<?php
session_start();
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth'] = false;
}
if (!isset($_SESSION['tareas'])) {
    $_SESSION['tareas'] = array();
}
if (isset($_POST['eliminarTarea'])) {
    $fechaEliminar = $_POST['fechaEliminar'];
    if (isset($_SESSION['tareas'][$fechaEliminar])) {
        unset($_SESSION['tareas'][$fechaEliminar]);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Calendario</title>
</head>

<body>
    <header>
        <h1>Calendario Manu Ayala</h1>
    </header>
    <a href="cierresesion.php" id="cerrar-sesion">Cerrar sesión</a>
    <?php
    if ($_SESSION['auth']) {
        include 'config.php';

        $defaultMonth = date('n');
        $defaultYear = date('Y');

        $selectedMonth = isset($_POST['month']) ? $_POST['month'] : $defaultMonth;
        $selectedYear = isset($_POST['year']) ? $_POST['year'] : $defaultYear;

        $lastDayOfMonth = date('t', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear));
    ?>
        <div id='divformularios'>
            <div>
                <form method="post" action="">
                    <label for="month">Selecciona el mes:</label>
                    <select name="month" id="month">
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            $selected = ($i == $selectedMonth) ? 'selected' : '';
                            echo "<option value='$i' $selected>" . date("F", mktime(0, 0, 0, $i, 1, 2000)) . "</option>";
                        }
                        ?>
                    </select><br><br>

                    <label for="year">Selecciona el año:</label>
                    <select name="year" id="year">
                        <?php
                        $startYear = date('Y') - 10;
                        $endYear = date('Y') + 10;
                        for ($i = $startYear; $i <= $endYear; $i++) {
                            $selected = ($i == $selectedYear) ? 'selected' : '';
                            echo "<option value='$i' $selected>$i</option>";
                        }
                        ?>
                    </select><br><br>

                    <input type="submit" value="Mostrar Calendario" class='botonverde'>
                </form>
            </div>
            <div>
                <form method="post" action="">
                    <label for="currentDayColor">Color para el día actual:</label>
                    <input type="color" name="currentDayColor" id="currentDayColor" value="<?php echo isset($_POST['colores']) ? $_POST['currentDayColor'] : '#66ccff'; ?>">

                    <label for="nationalHolidayColor">Color para festivos nacionales:</label>
                    <input type="color" name="nationalHolidayColor" id="nationalHolidayColor" value="<?php echo isset($_POST['colores']) ? $_POST['nationalHolidayColor'] : '#ff6666'; ?>">

                    <label for="communityHolidayColor">Color para festivos de comunidad:</label>
                    <input type="color" name="communityHolidayColor" id="communityHolidayColor" value="<?php echo isset($_POST['colores']) ? $_POST['communityHolidayColor'] : '#99ff99'; ?>">

                    <label for="localHolidayColor">Color para festivos locales:</label>
                    <input type="color" name="localHolidayColor" id="localHolidayColor" value="<?php echo isset($_POST['colores']) ? $_POST['localHolidayColor'] : '#ffb366'; ?>">

                    <input type="submit" value="Guardar Cambios" name="colores" class='botonverde'>
                </form>
            </div>
            <div id='tareas'>
                <form action="" method="post">
                    <label for="tarea">Introduce una tarea:</label>
                    <input type="text" name="tarea" id="tarea" required>
                    <input type="date" name="fecha" required>
                    <input type="submit" value="Añadir tarea" name="enviartarea">
                </form>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Exportar Tareas" name="exportarTareas">

                    <label for="importarArchivo">Importar tareas desde archivo (prueba con tareas.txt en tareas/):</label>
                    <input type="file" name="importarArchivo">
                    <input type="submit" value="Importar Tareas" name="importarTareas">
                </form>

                <?php
                if (isset($_POST['enviartarea'])) {
                    $tarea = $_POST['tarea'];
                    $fecha = $_POST['fecha'];
                    $_SESSION['tareas'][$fecha] = $tarea;
                }
                function procesarContenidoImportado($contenidoImportado)
                {
                    $lineas = explode("\n", $contenidoImportado);
                    $tareasImportadas = array();

                    foreach ($lineas as $linea) {
                        $datos = explode(': ', $linea, 2);

                        if (count($datos) == 2) {
                            list($fecha, $tarea) = $datos;
                            $tareasImportadas[$fecha] = $tarea;
                        }
                    }

                    return $tareasImportadas;
                }

                if (isset($_POST['importarTareas']) && isset($_FILES['importarArchivo']['tmp_name'])) {
                    $contenidoImportado = file_get_contents($_FILES['importarArchivo']['tmp_name']);
                    $tareasImportadas = procesarContenidoImportado($contenidoImportado);
                    $_SESSION['tareas'] = array_merge($_SESSION['tareas'], $tareasImportadas);
                    echo "Tareas importadas correctamente.";
                }

                if (isset($_POST['exportarTareas'])) {
                    $contenidoArchivo = '';
                    foreach ($_SESSION['tareas'] as $fecha => $tarea) {
                        $contenidoArchivo .= "$fecha: $tarea\n";
                    }
                    file_put_contents('tareas/tareas.txt', $contenidoArchivo);
                    echo "Tareas exportadas correctamente.";
                }
                ?>

            </div>
        </div>
        <div class="calendar">
            <?php
            for ($day = 1; $day <= $lastDayOfMonth; $day++) {
                $isCurrentDay = ($day == $cordobaHolidays['currentDay']);
                $isNationalHoliday = isset($cordobaHolidays['nationalHolidays'][$selectedMonth]) && in_array($day, $cordobaHolidays['nationalHolidays'][$selectedMonth]);
                $isCommunityHoliday = isset($cordobaHolidays['communityHolidays'][$selectedMonth]) && in_array($day, $cordobaHolidays['communityHolidays'][$selectedMonth]);
                $isLocalHoliday = isset($cordobaHolidays['localHolidays'][$selectedMonth]) && in_array($day, $cordobaHolidays['localHolidays'][$selectedMonth]);

                $dayClasses = ($isCurrentDay) ? 'current-day' : (($isNationalHoliday) ? 'national-holiday' : (($isCommunityHoliday) ? 'community-holiday' : (($isLocalHoliday) ? 'local-holiday' : '')));

                $dayBackgroundColor = '';

                if (isset($_POST['colores'])) {
                    if ($isCurrentDay) {
                        $dayBackgroundColor = 'background-color: ' . $_POST['currentDayColor'];
                    } elseif ($isNationalHoliday) {
                        $dayBackgroundColor = 'background-color: ' . $_POST['nationalHolidayColor'];
                    } elseif ($isCommunityHoliday) {
                        $dayBackgroundColor = 'background-color: ' . $_POST['communityHolidayColor'];
                    } elseif ($isLocalHoliday) {
                        $dayBackgroundColor = 'background-color: ' . $_POST['localHolidayColor'];
                    }
                } else {
                    if ($isCurrentDay) {
                        $dayBackgroundColor = 'background-color: #66ccff';
                    } elseif ($isNationalHoliday) {
                        $dayBackgroundColor = 'background-color: #ff6666';
                    } elseif ($isCommunityHoliday) {
                        $dayBackgroundColor = 'background-color: #99ff99';
                    } elseif ($isLocalHoliday) {
                        $dayBackgroundColor = 'background-color: #ffb366';
                    }
                }
                echo "<div class='day $dayClasses' style='$dayBackgroundColor'>";

                if (isset($_SESSION['tareas'][$selectedYear . '-' . $selectedMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT)])) {
                    echo "<strong>$day</strong>";
                } else {
                    echo $day;
                }

                echo "</div>";
            }
            ?>
        </div>

        <div id='divtareas'>
            <h3>Tareas:</h3>
            <?php
            foreach ($_SESSION['tareas'] as $fecha => $tarea) {
                echo "<p>$fecha: $tarea 
                            <form action='' method='post' style='display:inline; margin-left:10px;'>
                                <input type='hidden' name='fechaEliminar' value='$fecha'>
                                <input type='submit' value='Eliminar tarea' name='eliminarTarea' class='botonEliminar'>
                            </form>
                          </p>";
            }
            ?>
        </div>
    <?php
    } else {
        echo "<form action='login.php' method='post'>";
        echo "<label for='credenciales'>Indica el nombre del fichero de las credenciales sin extension '.txt', prueba con 'manu', fichero que está en 'credenciales/':</label>";
        echo "<input type='text' name='credenciales' id='credenciales'><br><br>";
        echo "<input type='submit' name='enviar' value='Enviar'>";
    }
    ?>
</body>

</html>
