<?php
require_once 'modelos/ConexionDB.php';
require_once 'modelos/config.php';
require_once 'modelos/Anuncio.php';
require_once 'modelos/AnuncioDAO.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/UsuarioDAO.php';
require_once 'modelos/Fotos.php';
require_once 'modelos/FotosDAO.php';

session_start();

$error = '';

//Conectamos con la bD
$connexionDB = new ConexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
$conn = $connexionDB->getConnexion();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
//Obtengo el id del anuncio que viene por GET
$idAnuncio = htmlspecialchars($_GET['ID']);
//Obtengo el anuncio de la BD
$anuncioDAO = new AnuncioDAO($conn);
$anuncio = $anuncioDAO->getById($idAnuncio);
if (!$anuncio || $anuncio->getUsuarioId()!==$_SESSION['id']) {
    header("Location: index.php");
    exit();
}
$FotosDAO = new FotosDAO($conn);

//Obtenemos los usuario de la BD para el desplegable
$usuarioDAO = new UsuarioDAO($conn);
$usuario = $usuarioDAO->getAll();
$fotos = $FotosDAO->getAllByAnuncioId($anuncio->getId());
//Cuando se envíe el formulario actualizo el anuncio en la BD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Limpiamos los datos que vienen del usuario
    $titulo = htmlspecialchars($_POST['titulo']);
    $descripcion = strip_tags($_POST['descripcion']);
    if ($_SESSION['id'] == $anuncio->getUsuarioId()) {
        //Validamos los datos
        if (empty($titulo) || empty($descripcion)) {
            $error = "Los dos campos son obligatorios";
        } else {
            $anuncio->setTitulo($titulo);
            $anuncio->setdescripcion($descripcion);

            if ($anuncioDAO->update($anuncio)) {
                // Actualizar la foto principal
                $fotoPrincipalId = htmlspecialchars($_POST['foto_principal']);
                if (!empty($fotoPrincipalId)) {
                    // Actualizar la marca 'EsPrincipal' en la base de datos para la foto seleccionada
                    foreach ($fotos as $foto) {
                        if ($foto->getId() == $fotoPrincipalId) {
                            $foto->setPrincipal(true);
                            $FotosDAO->update($foto);
                        } else {
                            $foto->setPrincipal(false);
                            $FotosDAO->update($foto);
                        }
                    }
                }
                header('location: index.php');
                die();
            }
        }
    } else {
        echo ("No puedes editar este anuncio");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Anuncio</title>
    <script src="https://cdn.tiny.cloud/1/tuhio4pfer658qpfd48ee0yllubavjvoa7xkjsqdko6m9bmp/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
        });
    </script>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin-left: 5%;
        margin-right: 15%;
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
    .error{
        color: red;
    }
    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px 0;
    }

    .foto {
        max-width: 300px;
        height: auto;
        display: inline-block;
        vertical-align: top;
        margin-bottom: 20px;
    }

    .foto-label {
        display: inline-block;
        vertical-align: top;
        margin-right: 20px;
    }

    a {
        text-decoration: none;
        color: #007bff;
    }
</style>

<body>
    <h2>Editar anuncio </h2>
    <p class="error"><?= $error ?></p>
    <form action="editar_anuncio.php?ID=<?= $idAnuncio ?>" method="post">
        Título:<input type="text" name="titulo" placeholder="Titulo" value="<?= $anuncio->getTitulo() ?>"><br><br>
        Descripción: <textarea name="descripcion" placeholder="descripcion"><?= $anuncio->getDescripcion() ?></textarea><br>
        <h4>Elige la foto principal</h4>
        <?php foreach ($fotos as $foto) : ?>
            <div>
                <?php
                // Ruta relativa a las imágenes
                $ruta = "fotosAnuncios/" . $foto->getImagen();
                if (!empty($ruta) && file_exists($ruta)) {
                    echo '<img class="foto" src="' . $ruta . '" alt="Anuncio Image">';
                } else {
                    echo '<p>Imagen no disponible</p>';
                }
                ?>
                <label>
                    <input type="radio" name="foto_principal" value="<?= $foto->getId() ?>" <?= $foto->getPrincipal() ? 'checked' : '' ?>>
                    Principal
                </label>
            </div>
        <?php endforeach; ?>
        <input type="submit" value="Guardar Cambios">
        <a href="index.php">Cancelar</a>
    </form>
</body>

</html>