<?php
require_once 'modelos/ConexionDB.php';
require_once 'modelos/config.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/AnuncioDAO.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/UsuarioDAO.php';
require_once 'modelos/Fotos.php';
require_once 'modelos/FotosDAO.php';

// Inicia la sesión
session_start();

//Creamos la conexión utilizando la clase que hemos creado
$connexionDB = new ConexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $connexionDB->getConnexion();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
//Creamos el objeto AnunciosDAO para acceder a BBDD a través de este objeto
$anuncioDAO = new AnuncioDAO($conn);

//Obtener el anuncio
$idAnuncio = htmlspecialchars($_GET['ID']);
$anuncio = $anuncioDAO->getById($idAnuncio);



if ($_SESSION['id'] == $anuncio->getUsuarioId()) {
    // Eliminar fotos del anuncio de la carpeta FotosAnuncios
    $fotosDAO = new FotosDAO($conn);
    $fotosAnuncio = $fotosDAO->getAllByAnuncioID($idAnuncio); // Obtener las fotos del anuncio

    foreach ($fotosAnuncio as $foto) {
        $ruta = "fotosAnuncios/" . $foto->getImagen();
        if (file_exists($ruta)) {
            unlink($ruta); // Eliminar la imagen de la carpeta
        }
    }
    $fotosDAO->delete($idAnuncio);
    $anuncioDAO->delete($idAnuncio);

    header('location: index.php');
} else {
    echo ("No puedes borrar este mensaje");
}
