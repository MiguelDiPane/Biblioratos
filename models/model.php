<?php
    #Resuelve el mapeo del nombre a la pagina
    class ResolverPagina{
        public function resolver($nombreModulo){
            #Asumo que si esta vacio voy a inicio
            if(empty($nombreModulo)){
                $nombreModulo = "view.login.form";
            }
            return "views/modules/$nombreModulo.php";
        }
    }


?>