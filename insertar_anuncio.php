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

function generarNombreArchivo(string $nombreOriginal): string
{
    $nuevoNombre = md5(time() + rand());
    $partes = explode('.', $nombreOriginal);
    $extension = $partes[count($partes) - 1];
    return $nuevoNombre . '.' . $extension;
}
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Creamos la conexión utilizando la clase que hemos creado
    $conexionDB = new ConexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $conexionDB->getConnexion();

    //Limpiamos los datos que vienen del usuario
    $titulo = htmlspecialchars($_POST['titulo']);
    $precio =  htmlspecialchars($_POST['precio']);
    $descripcion =  htmlspecialchars_decode($_POST['descripcion']);

    $idUsuario = $_SESSION['id'];

    $tiposPermitidos = ['jpeg', 'jpg', 'webp', 'png'];
    $errores = [];

    for ($i = 0; $i < count($_FILES['foto']['name']); $i++) {
        $nombreArchivo = $_FILES['foto']['name'][$i];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

        if (!in_array(strtolower($extension), $tiposPermitidos)) {
            $errores[] = "El archivo '$nombreArchivo' no tiene un formato admitido (jpeg, webp, png)";
        }
    }
    if (!empty($errores)) {
        $error = implode('<br>', $errores);
    } else {

        //Creamos el objeto AnuncioDAO para acceder a BBDD a través de este objeto
        $anuncioDAO = new AnuncioDAO($conn);
        $anuncio = new Anuncio();

        $anuncio->setTitulo($titulo);
        $anuncio->setDescripcion($descripcion);
        $anuncio->setPrecio($precio);
        $anuncio->setUsuarioId($idUsuario);
        $anuncioDAO->insert($anuncio);

        $idAnuncio = $anuncioDAO->getLastInsertedId();

        $total_fotos = count($_FILES['foto']['name']);
        $esPrincipal = true;
        for ($i = 0; $i < $total_fotos; $i++) {
            $foto = generarNombreArchivo($_FILES['foto']['name'][$i]);
            //Si existe un archivo con ese nombre volvemos a calcular el hash
            while (file_exists("fotosAnuncios/$foto")) {
                $foto = generarNombreArchivo($_FILES['foto']['name'][$i]);
            }
            if (!move_uploaded_file($_FILES['foto']['tmp_name'][$i], "fotosAnuncios/$foto")) {
                die("Error al copiar la foto a la carpeta fotosUsuarios");
            }
            $FotosDAO = new FotosDAO($conn);
            $fotos = new Fotos();
            $fotos->setImagen($foto);
            $fotos->setAnuncioId($idAnuncio);
            $fotos->setPrincipal($esPrincipal);
            $FotosDAO->insert($fotos);
            $esPrincipal = false;
        }
        header('location: index.php');
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo anuncio</title>
    <script src="https://cdn.tiny.cloud/1/tuhio4pfer658qpfd48ee0yllubavjvoa7xkjsqdko6m9bmp/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#descripcion',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
        });
    </script>
</head>
<style>
    /* Estilos generales */
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #007BFF;
    }

    form {
        display: flex;
        flex-direction: column;
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    input[type="text"],
    input[type="number"],
    textarea,
    input[type="file"],
    input[type="submit"] {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 14px;
    }

    input[type="text"],
    input[type="number"],
    textarea {
        width: calc(100% - 22px);
        /* Considerando el ancho del borde */
    }

    input[type="submit"] {
        background-color: #007BFF;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    /* Estilos para el error */
    .error {
        color: red;
        margin-bottom: 10px;
    }

    a {
        text-align: center;
        color: #007BFF;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    a:hover {
        color: #0056b3;
    }
</style>

<body>

    <h1>Nuevo anuncio</h1>
    <?= $error ?>
    <form action="insertar_anuncio.php" method="post" enctype="multipart/form-data">
        Titulo: <input type="text" name="titulo" placeholder="Titulo" required><br>
        Precio(€): <input type="number" step="any" name="precio" placeholder="Precio (€)" required><br>
        Descripción: <textarea id="descripcion" name="descripcion" placeholder="Descripción"></textarea><br>
        Fotos: <input type="file" name="foto[]" accept="image/jpeg,image/webp, image/png" multiple required><br>
        <input type="submit" value="Crear Anuncio">
        <a href="index.php">Cancelar</a>
    </form>

</body>

</html>
