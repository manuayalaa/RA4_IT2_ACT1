<?php

session_start();
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth'] = false;
}
if (!isset($_POST['enviar'])) {
    header('Location: index.php');
}
$usuario = 'manu';
$contrasena = 'manu';
$fichero = $_POST['credenciales'];
$fichero = str_replace(' ', '', $fichero);
$fichero = strtolower($fichero);
$fichero = str_replace('ñ', 'n', $fichero);
$fichero = str_replace('á', 'a', $fichero);
$fichero = str_replace('é', 'e', $fichero);
$fichero = str_replace('í', 'i', $fichero);
$fichero = str_replace('ó', 'o', $fichero);
$fichero = str_replace('ú', 'u', $fichero);
$fichero = "credenciales/".$fichero.".txt";
$manejador = fopen($fichero, 'r');
if ($manejador) {
    // Lee el archivo línea por línea hasta llegar al final
    while (($linea = fgets($manejador)) !== false) {
        $arrlinea = explode(":", $linea);
        if ($arrlinea[0] == $usuario && $arrlinea[1] == $contrasena) {
            $_SESSION['auth'] = true;
            header('Location: index.php');
        } else {
            $_SESSION['auth'] = false;
            header('Location: index.php');
        }
    }

    // Cierra el archivo después de su lectura
    fclose($manejador);
} else {
    // Manejo de errores si no se puede abrir el archivo
    echo "No se pudo abrir el archivo.";
}
