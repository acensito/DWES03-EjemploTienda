<?php
// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['enviar'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password']; 

    if (empty($usuario) || empty($password)) {
        $error = "Debes introducir un nombre de usuario y una contraseña";
    } else {
        function conectar(){
            $db_host = 'localhost';  //  hostname Por defecto: localhost 192.168.0.250 en red
            $db_name = 'ejtienda';  //  databasename
            $db_user = 'admintienda';  //  username
            $user_pw = 'admintienda';  //  password
            try {
                global $con; //Se realiza como variable global para tener la misma disponible en cualquier página
                $con = new PDO('mysql:host='.$db_host.'; dbname='.$db_name, $db_user, $user_pw);
                $con->exec("set names utf8");
            } catch (PDOException $e) { //Se capturan los mensajes de error
                $err = $e->getCode();
                $msj = $e->getMessage(); 
                echo "<div class='errcon'><p>" . $err . " " . $msj . "</p></div>"; //Se devuelven los mismos si existieran
            }
        }
        
                
    if (isset($_POST['enviar'])) { // Comprobamos si ya se ha enviado el formulario, si es asi
        /* Recabamos los datos del usuario de la sesion */
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
       
        if (empty($usuario)) { //Si el campo usuario esta vacio, se procede a devolver el correspondiente mensaje de error
            return $error = "<div class='errcon'><p>Debes introducir un nombre de usuario. Si no tienes usuario, puedes crearte uno registrandote o puedes acceder en modo invitado.</p></div>";
//        } else if (empty($password)) { //Este campo es optativo, yo he tomado como un intento de error malo el loguearse sin meter clave alguna.
//            return $error = "Debes de introducir una contraseña.";
        } else { //En el caso de que haya introducido un usuario...
            /*  Comprobamos las credenciales con la base de datos
             *  Conectamos a la base de datos */
            conectar();
            /* Ejecutamos la consulta para comprobar las credenciales */
            try {
                $consulta = "SELECT * FROM foreros WHERE login = :login";
                $resultado = $GLOBALS['con']->prepare($consulta);
                $resultado->bindParam(":login", $usuario);
                $resultado->execute();
                $registro = $resultado->fetch(); //Obtenemos el resultado
                
                if ($registro['bloqueado'] == true) { //Si esta bloqueado, no se prosigue y se manda mensaje de feedback
                    return $error = "<div class='errcon'><p>Usuario bloqueado. Contacte con el administrador</p></div>";
                } else { //En el caso de no estar bloqueado, y existe...
                    if ($resultado->rowCount() > 0) {
                        $hash = $registro['password']; //Obtenemos el password de la BD
                        $clave = password_verify($password, $hash); //Lo comparamos con el enviado por formulario
                        if ($clave) { //Si la clave es buena, iniciamos la sesion...
                            $_SESSION['usuario'] = $usuario; //Obtenemos el usuario
                            $_SESSION['hora'] = time();      //Obtenemos la hora
                            header("Location: foro.php");    //Redirijimos a la pagina de usuario registrado, foro.php
                        } else { //En el caso de existir el usuario y tener una clave incorrecta
                            if (!isset($_SESSION['usuarioIntento'])) { //Si no hay definido un usuario de intento...
                                $_SESSION['usuarioIntento'] = $usuario; //Definimos ese usario para almacenar sus intentos
                                $_SESSION['intentos'] = 1; //Le establecemos su primer intento
                            } else if ($_SESSION['usuarioIntento'] == $usuario && isset($_SESSION['usuarioIntento'])) { //Si ya hay usuario de intento definido y sigue siendo el mismo
                                $_SESSION['intentos']++; //Le sumamos al contador de intentos uno más
                                if ($_SESSION['intentos'] >= 3) { //Si es igual o mayor que 3 el numero de intentos (porsiacaso), bloqueamos dicho usuario
                                    /* Ctreamos la consulta y le actualizamos el campo bloqueado al valor true */
                                    try {
                                        $consulta = "UPDATE foreros SET bloqueado = true WHERE login = :usuario";
                                        $resultado = $GLOBALS['con']->prepare($consulta);
                                        $resultado->bindParam(":usuario", $usuario);
                                        $resultado->execute();
                                        return $error = "<div class='errcon'><p>Usuario bloqueado. Contacte con el administrador</p></div>"; //Mandamos un mensaje de feedback 
                                    } catch (PDOException $e) { //En el caso de obtener un error, se captura el mensaje y se muestra
                                        echo "<div class='errcon'><p>Se ha producido error " . $e->getMessage() . "</p></div>";
                                    }
                                }
                            } else { //En el caso de no ser el mismo usuario el que se ha introducido que el anterior intento, reseteamos el contador a 1 a ese usuario
                                $_SESSION['usuarioIntento'] = $_POST['usuario']; //Obtenemos la identidad de este nuevo usuario
                                $_SESSION['intentos'] = 1; //Le establecemos el contador a 1 de nuevo
                            }   
                            return $error ="<div class='errcon'><p>Clave incorrecta. Lleva " . $_SESSION['intentos'] . " intento de 3.</p></div>"; //Mandamos un mensaje de feedback
                        }
                    } else { //No existe el usuario
                        return $error = "<div class='errcon'><p>No existe el usuario. Entre como invitado o registrese como usuario</p></div>";
                    } 
                }
            } catch (PDOException $e) { //En el caso de obtener un error, se captura el mensaje y se muestra
                echo "<div class='errcon'><p>Se ha producido error " . $e->getMessage() . "</p></div>";
            }
        }
        
   } else if (isset($_POST['invitado'])) { //En el caso de pulsar el boton de invitado..
       $_SESSION['usuario'] = 'Invitado'; //Establecemos un usuario Invitado
       $_SESSION['hora'] = time(); //Establecemos su hora de conexion
       header("Location: invitado.php"); //Redirigimos a su web
   } else { //En cualquier otro tipo de error
       return $error = "Introduzca un nombre de usuario y una contraseña o acceda como invitado"; //Mensaje de feedback
   }
}       
        
        // Comprobamos las credenciales con la base de datos
        // Conectamos a la base de datos
//        try {
//            $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
//            $dsn = "mysql:host=localhost;dbname=ejtienda";
//            $dwes = new PDO($dsn, "admintienda", "admintienda", $opc);
//        } catch (PDOException $e) {
//            die("Error: " . $e->getMessage());
//        }
//
//        // Ejecutamos la consulta para comprobar las credenciales
//        $sql = "SELECT usuario FROM usuarios " .
//                "WHERE usuario='$usuario' " .
//                "AND contrasena='" . md5($password) . "'";
//
//        if ($resultado = $dwes->query($sql)) {
//            $fila = $resultado->fetch();
//            if ($fila != null) {
//                session_start();
//                $_SESSION['usuario'] = $usuario;
//                header("Location: productos.php");
//            } else {
//                // Si las credenciales no son válidas, se vuelven a pedir
//                $error = "Usuario o contraseña no válidos!";
//            }
//            unset($resultado);
//        }
//        unset($dwes);
    }
}
?>
<!DOCTYPE html>
<!-- Desarrollo Web en Entorno Servidor -->
<!-- Tema 4 : Desarrollo de aplicaciones web con PHP -->
<!-- Ejemplo Tienda Web: login.php -->
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Ejemplo Tema 4: Login Tienda Web</title>
        <link href="tienda.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div id='login'>
            <form action='login.php' method='post'>
                <fieldset >
                    <legend>Login</legend>
                    <div></div>
                    <div class='campo'>
                        <label for='usuario' >Usuario:</label><br/>
                        <input type='text' name='usuario' id='usuario' maxlength="50" /><br/>
                    </div>
                    <div class='campo'>
                        <label for='password' >Contraseña:</label><br/>
                        <input type='password' name='password' id='password' maxlength="50" /><br/>
                    </div>

                    <div class='campo'>
                        <input type='submit' name='enviar' value='Enviar' />
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>
