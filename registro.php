<?php
require_once 'modelos/ConexionDB.php';
require_once 'modelos/Usuario.php';
require_once 'modelos/UsuarioDAO.php';
require_once 'modelos/config.php';

$email = $nombre = $telefono = $poblacion = "";

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Limpiamos los datos
    $email = htmlentities($_POST['email']);
    $password = htmlentities($_POST['password']);
    $nombre = htmlentities($_POST['nombre']);
    $telefono = htmlentities($_POST['telefono']);
    $poblacion = htmlentities($_POST['poblacion']);

    // Validar el correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } else {

        //Conectamos con la BD
        $conexionDB = new ConexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $conexionDB->getConnexion();

        //Compruebo que no haya un usuario registrado con el mismo email
        $usuarioDAO = new UsuarioDAO($conn);
        if ($usuarioDAO->getByEmail($email) != null) {
            $error = "Ya hay un usuario con ese email";
        } else {
            // Validar la longitud mínima del password
            if (strlen($password) < 4) {
                $error = "La contraseña debe tener al menos 4 caracteres.";
            } else {

                //Insertamos en la BD
                $usuario = new Usuario();
                $usuario->setEmail($email);
                $passwordCifrado = password_hash($password, PASSWORD_DEFAULT);
                $usuario->setPassword($passwordCifrado);
                $usuario->setNombre($nombre);
                $usuario->setTelefono($telefono);
                $usuario->setPoblacion($poblacion);

                if ($usuarioDAO->insert($usuario)) {
                    header("location: index.php");
                } else {
                    $error = "No se ha podido insertar el usuario";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #9ed8d9;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        width: 80%;
        max-width: 400px;
        /* Ancho máximo del formulario */
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
        /* Centra el contenido del formulario */
    }

    h1 {
        text-align: center;
        color: #333;
    }

    form {
        text-align: left;
    }

    form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }

    form input[type="submit"] {
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    form input[type="submit"]:hover {
        background-color: #0056b3;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 10px;
        text-decoration: none;
        color: #333;
    }

    a:hover {
        color: #007BFF;
    }
</style>

<body>
    <h1>Registro</h1>
    <?= $error ?>
    <form action="registro.php" method="post">
        Email: <input type="email" name="email" value="<?= $email ?>" required><br>
        Contraseña: <input type="password" name="password" required><br>
        Usuario: <input type="text" name="nombre" value="<?= $nombre ?>" required><br>
        Teléfono: <input type="text" name="telefono" value="<?= $telefono ?>"><br>
        Población: <input type="text" name="poblacion" value="<?= $poblacion ?>"><br>
        <input type="submit" value="Registrar">
        <a href="index.php">Cancelar</a>
    </form>
</body>

</html>