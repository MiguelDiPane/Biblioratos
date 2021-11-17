<?php
    require_once 'config/config.php';
    
    #Mapea actions que recibo con metodos dentro de la clase
    $map_action = array(
        'abmBiblioratos' => 'abmBiblioratosController',
        'login' => 'loginController',
        'abmCarpetas' => 'abmCarpetasController'
    ); 

    class MVController{
        #Invoco el template, toma todo el contenido del template y lo pega
        public function template(){
            include "views/template.php";
        }
        #Permite enroutar a paginas estaticas o dinamicas
        public function route(){
            global $map_action;
            global $tiempo_sesion;

            if (isset($_GET['action'])){
                $accion = $_GET['action'];
            }
            else{
                $accion = "login";
            }

            #Para cerrar la sesión según el Tiempo de inactividad
            
            if (!isset($_SESSION['tiempo'])) {
                $_SESSION['tiempo']=time();
            }
            else if (time() - $_SESSION['tiempo'] > $tiempo_sesion) {
                session_unset();
                /* Aquí redireccionas a la url especifica */
                #Hago echo para que se resetee tambien el nav, con la foto de perfil
                echo '<script>window.location="index.php?action=login"</script>';
                
            }
            $_SESSION['tiempo']=time(); //Si hay actividad seteamos el valor al tiempo actual

            #Invocacion dinamica
            $this->{$map_action[$accion]}($accion); #Expande la cadena de caracteres entre {} e invoca el metodo
            #Igual a $this->paginaEstatica[$accion]($accion)
        }

        public function paginaEstatica($accion){
            $model = new ResolverPagina();
            include $model->resolver($accion);
        }

        public function loginController(){
            require_once "controllers/controller.login.php";
            $loginController = new loginController();
            $loginController->init();
        }

        public function abmCarpetasController(){
            if (isset($_SESSION['usuario'])){
                require_once "controller.abmCarpetas.php";
                $controller = new ABMCarpetasController();
                $controller->init();
            }
            else{
                include "views/modules/view.login.noLogueado.php";
            }
        }

        public function abmBiblioratosController(){
            if (isset($_SESSION['usuario'])){
                require_once "controller.abmBiblioratos.php";
                $controller = new ABMBiblioratosController();
                $controller->init();                
            }
            else{
                include "views/modules/view.login.noLogueado.php";
            }

        }
    }


?>