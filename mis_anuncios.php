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

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

//Creamos la conexión
$conexionDB = new ConexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $conexionDB->getConnexion();


//Creamos los objetos DAO para acceder a BBDD a través de este objeto
$AnuncioDAO = new AnuncioDAO($conn);
$pagina_actual = isset($_GET['page']) ? $_GET['page'] : 1;
$anuncios = $AnuncioDAO->getAllPaginacionUser($pagina_actual, $_SESSION['id']);
$anunciosPorPagina = 5; // Suponiendo que muestras 5 anuncios por página
$totalAnuncios = $AnuncioDAO->getTotalAnuncios(); // Debes obtener la cantidad total de anuncios de alguna manera, por ejemplo, con una consulta SQL COUNT

$totalPages = ceil($totalAnuncios / $anunciosPorPagina);
//$anuncios = $AnuncioDAO->getAllByUser($_SESSION['id']);

$errorInicio = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallapop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #007BFF;
        color: #fff;
        text-align: center;
        padding: 1px;
    }

    #menu {
        float: left;
        width: 20%;
        padding: 20px;
        background-color: #333;
        color: #fff;
        box-sizing: border-box;
    }

    #menu ul {
        list-style: none;
        padding: 0;
    }

    #menu li {
        margin-bottom: 10px;
    }

    #menu a {
        text-decoration: none;
        color: #fff;
    }

    main {
        margin-left: 40%;
        width: 40%;
        padding: 20px;
        box-sizing: border-box;
    }

    .foto {
        width: 300px;
        height: 250px;
    }

    .anuncio {
        padding: 10px;
        border: 1px solid #ddd;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
        position: relative;
    }

    .titulo {
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .icono_editar,
    .icono_borrar {
        position: absolute;
        bottom: 5px;
        color: #aaa;
    }

    .icono_editar {
        left: 5px;
    }

    .icono_borrar {
        right: 5px;
    }

    .texto {
        font-size: 1.2em;
    }

    a {
        text-decoration: none;
        color: #007BFF;
    }

    .pagination {
        /* Estilos de la paginación */
        margin-top: 20px;
        text-align: center;
    }

    .pagination a {
        /* Estilos para los enlaces de paginación */
        display: inline-block;
        padding: 5px 10px;
        margin: 0 5px;
        border: 1px solid #007BFF;
        color: #007BFF;
        text-decoration: none;
        border-radius: 5px;
    }

    .pagination a.active {
        /* Estilos para el enlace activo (página actual) */
        font-weight: bold;
        background-color: #007BFF;
        color: #fff;
    }
</style>

<body>
    <header>
        <h1>Mis anuncios</h1>
    </header>
    <div id="menu" style="float: left; width: 20%;">
        <ul>
            <li><a href="index.php">Anuncios</a></li>
            <li><?php if ($_SESSION['usuario'] != null) { ?>
                    <!-- Mostrar el nombre de usuario y opción de cerrar sesión -->
                    <p>Bienvenido, <?= $_SESSION['usuario'] ?></p>
            <li><a href="insertar_anuncio.php">Nuevo anuncio</a></li>
            <li><a href="mis_anuncios.php">Mis Anuncios</a></li>
            <form action="logout.php">
                <input type="submit" value="Cerrar Sesión" name="cerrar">
            </form>
        <?php } else { ?>
            <!-- Mostrar campos de inicio de sesión -->
            <?= $errorInicio ?>
            <form action="index.php" method="post">
                <input type="text" name="email" placeholder="Email...">
                <input type="text" name="password" placeholder="Password...">
                <input type="submit" value="Iniciar sesión">
            </form>
            <a href="registro.php">Registrate</a>
        <?php } ?>
        </li>

        </ul>
    </div>

    <main>

        <?php
        if ($anuncios === null || empty($anuncios)) {
            echo "<h1>No tienes anuncios creados.</h1>";
        } else {
            foreach ($anuncios as $anuncio) : ?>
                <div class="anuncio">
                    <h2 class="titulo">
                        <div> <a href="ver_anuncio.php?ID=<?= $anuncio->getId() ?>"><?= $anuncio->getTitulo() ?></a></div>
                    </h2>
                    <h4><?= $anuncio->getPrecio() ?> €</h4>
                    <?php
                    $fotosDAO = new FotosDAO($conn);
                    $fotosAnuncio = new Fotos();
                    $fotosAnuncio = $fotosDAO->getImagenPrincipalById($anuncio->getId());
                    $ruta = "fotosAnuncios/" . $fotosAnuncio->getImagen();

                    if (!empty($ruta)) {
                        echo '<img class="foto" src="' . $ruta . '" alt="Anuncio Image">';
                    } else {
                        echo '<p>Imagen no disponible</p>';
                    }
                    ?>
                    <?php if ($anuncio->getUsuarioId() == $_SESSION['id']) { ?>
                        <span class="icono_borrar"><a href="borrar_anuncio.php?ID=<?= $anuncio->getId() ?>"><i class="fa-solid fa-trash color_gris"></i></a></span>
                        <span class="icono_editar"><a href="editar_anuncio.php?ID=<?= $anuncio->getId() ?>"><i class="fa-solid fa-pen-to-square color_gris"></i></a></span>
                    <?php  } ?>
                </div>
        <?php endforeach;
        } ?>
        <?php if ($anuncios === null || empty($anuncios)) {
        } else { ?>
            <div class="pagination">
                <?php if ($pagina_actual > 1) : ?>
                    <a href="?page=<?= $pagina_actual - 1 ?>">Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a <?= $i === (int)$pagina_actual ? 'class="active"' : '' ?> href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($pagina_actual < $totalPages) : ?>
                    <a href="?page=<?= $pagina_actual + 1 ?>">Siguiente</a>
                <?php endif; ?>
            </div>
        <?php } ?>
    </main>
</body>

</html>