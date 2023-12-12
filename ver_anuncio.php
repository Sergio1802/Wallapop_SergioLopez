<?php

require_once 'modelos/ConexionDB.php';
require_once 'modelos/config.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/AnuncioDAO.php';
require_once 'modelos/Fotos.php';
require_once 'modelos/FotosDAO.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/UsuarioDAO.php';

session_start();

//Creamos la conexión utilizando la clase que hemos creado
$conexionDB = new ConexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $conexionDB->getConnexion();

//Creamos el objeto DAO para acceder a BBDD a través de este objeto
$AnuncioDAO = new AnuncioDAO($conn);
$UsuarioDAO = new UsuarioDAO($conn);
$FotosDAO = new FotosDAO($conn);

//Obtener el anuncio
$idAnuncio = htmlspecialchars($_GET['ID']);

$anuncio = $AnuncioDAO->getById($idAnuncio);

if (!$anuncio) {
    header("Location: index.php");
    exit();
}
$fotos = $FotosDAO->getAllByAnuncioId($anuncio->getId());
$idUsuario = $anuncio->getUsuarioId();
$usuarioAnuncio = $UsuarioDAO->getById($idUsuario);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anuncio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin-left: 5%;
        padding: 0;
        color: #333;
    }

    header {
        background-color: #007bff;
        color: white;
        text-align: center;
        padding: 10px;
    }

    h2 {
        color: #007bff;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px 0;
    }

    .foto {
        max-width: 300px;
        height: auto;
        display: block;
        margin-bottom: 20px;
    }

    a {
        text-decoration: none;
        color: #007bff;
    }
</style>

<body>
    <div id="container">
        <?php if ($anuncio != null) : ?>

            <div id="info">
                <h2><?= $anuncio->getTitulo() ?> </h2>
                <h4>Precio: <?= $anuncio->getPrecio() ?>€</h4>

                <p>Descripción: <?= $anuncio->getDescripcion() ?> </p>
                <p>Creado el: <?= $anuncio->getFechaCreacion() ?></p>
                <h4>Datos del dueño</h4>
                <p>Nombre: <?= $usuarioAnuncio->getNombre() ?></p>
                <p>Email: <?= $usuarioAnuncio->getEmail() ?></p>
                <p>Teléfono: <?= $usuarioAnuncio->getTelefono() ?></p>
            </div>
            <div class="foto-container">
                <h4>Fotos</h4>
                <?php foreach ($fotos as $foto) :
                    $ruta = "fotosAnuncios/" . $foto->getImagen();

                    if (!empty($ruta)) {
                        echo '<img class="foto" src="' . $ruta . '" alt="Anuncio Image">';
                    } else {
                        echo '<p>Imagen no disponible</p>';
                    }
                endforeach; ?>
            </div>
        <?php else : ?>
            <strong>anuncio no encontrado</strong>
        <?php endif; ?>
        <br>
        <a href="index.php">Volver al listado de Anuncios</a>
    </div>
</body>

</html>