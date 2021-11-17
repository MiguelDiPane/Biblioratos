<?php

    require_once 'models/model.login.php';

    class LoginController{

        public function init(){
            $model = new LoginModel();
            if(isset($_GET['order'])){
                $order = $_GET['order'];

                if($order == 'iniciar_sesion'){
                    if(isset($_POST['usuario']) && trim($_POST['usuario']) != ''){
                        $usuario = $model->login($_POST['usuario'],$_POST['clave']);
                        #Usuario distinto de false, se logueo correctamente
                        if($usuario != false){
                            $_SESSION['usuario'] = $usuario['usuario'];
                            $_SESSION['rol'] = $usuario['rol'];
                            $_SESSION['foto'] = $usuario['foto'];
                            $_SESSION['nombre_apellido'] = $usuario['nombre_apellido'];
                            #Habilito al rol administrador a que vea los botones de llenar bibliorato
                            if ($usuario['rol'] == 'Administrador'){
                                $_SESSION['admin'] = True;
                            }
                            $mensaje = 'loginOK';
                        }
                        else{
                            $mensaje = 'error';
                        }
                    }
                    
                    #Reload de la página al iniciar sesión, para mostrar el nombre del usuario    
                    if(isset($mensaje) and $mensaje == 'loginOK'){
                        $usuario = $_SESSION['usuario'];
                        $_SESSION['usuario'] = $usuario;
                    }
                    echo '<script>window.location="index.php?action=login"</script>';
                }

                elseif($order == 'cerrar_sesion'){ 
                    $model->logout();
                    echo '<script>window.location="index.php?action=login"</script>';
                }
            }

            else{
                include "views/modules/view.login.form.php";
            }
        }
    }


?>