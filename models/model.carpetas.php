<?php
    class ABMCarpetas{
        
        public function get_NumBibliorato_inicial($departamento,$dni){
            #Obtengo todas las personas del Depto ingresado
            $conn = conexion();
            #Debo agregar ORDER BY dni DESC para que al analizar los dni tome los que estan en un bibliorato xxxx.x
            $sentenciaSQL = $conn->prepare('SELECT `dni`,`bibliorato`,`nombre_departamento` 
                                        FROM `carpetas` 
                                        INNER JOIN `biblioratos` ON `bibliorato`=`num_bibliorato` 
                                        INNER JOIN `departamentos` USING(`id_departamento`)
                                        WHERE `nombre_departamento`=:depto
                                        ORDER BY dni DESC');
            $sentenciaSQL->execute(array(':depto'=>$departamento));
            $result = $sentenciaSQL->fetchAll();
            $carpetaEncontrada = $result[0]; #Asigna por defeto la carpeta con el dni más grande
          
            #Itero por todas las personas para encontrar la ubicacion de la nueva carpeta segun el dni
            foreach ($result as $carpeta){
                #Me detengo apenas encuentro uno mas grande, en el N° de bibliorato de esa carpeta irá la persona
                if($carpeta['dni'] < $dni){
                    $carpetaEncontrada = $carpeta;
                    break;
                }
            }
            return $carpetaEncontrada['bibliorato'];
        }

        public function get_NumBibliorato_anterior($departamento,$dni){
            #Obtengo todas las personas del Depto ingresado
            $conn = conexion();
            #Debo agregar ORDER BY dni DESC para que al analizar los dni tome los que estan en un bibliorato xxxx.x
            $sentenciaSQL = $conn->prepare('SELECT `dni`,`bibliorato` 
                                        FROM `carpetas` 
                                        INNER JOIN `biblioratos` ON  `bibliorato`=`num_bibliorato`
                                        INNER JOIN  `departamentos` USING(`id_departamento`)
                                        WHERE `nombre_departamento`=:depto ORDER BY dni DESC');
            $sentenciaSQL->execute(array(':depto'=>$departamento));
            $result = $sentenciaSQL->fetchAll();
            $carpetaEncontrada = end($result); #Asigna por defeto la carpeta con el dni más grande
            
            #Itero por todas las personas para encontrar la ubicacion de la nueva carpeta segun el dni
            foreach ($result as $carpeta){
                #Me detengo apenas encuentro uno mas grande, en el N° de bibliorato de esa carpeta irá la persona
                if($carpeta['dni'] == $dni){
                    $carpetaEncontrada = $carpeta;
                    break;
                }
            }
            return $carpetaEncontrada['bibliorato'];
        }

        public function agregarCarpeta($dni,$apellido,$nombre,$celular,$telefono,$email,$bibliorato,$inscAnterior,$ultimaInsc,$baja,$doc_faltante,$falta_carpeta){
            $conn = conexion();
            $sentenciaSQL = $conn->prepare('INSERT INTO `carpetas`(`id`, `dni`, `apellido`,`nombre`,`celular`,`telefono`,`email`,`bibliorato`,`inscripcion_anterior`,`ultima_inscripcion`,`dado_de_baja`,`falta_carpeta`,`documentacion_faltante`) VALUES (NULL,:dni,:apellido,:nombre,:celular,:telefono,:email,:bibliorato,:inscripcion_ant,:ult_insc,:dado_baja,:falta_carpeta,:doc_faltante)');
            #Indicar por arreglo asociativo el valor de los datos
            $sentenciaSQL->execute(array('dni'=>$dni,
                                        ':apellido'=>$apellido,
                                        ':nombre'=>$nombre,
                                        ':celular'=>$celular,
                                        ':telefono'=>$telefono,
                                        ':email'=>$email,
                                        ':bibliorato'=>$bibliorato,
                                        ':inscripcion_ant'=>$inscAnterior,
                                        ':ult_insc'=>$ultimaInsc,
                                        ':dado_baja'=>$baja,
                                        ':falta_carpeta'=>$falta_carpeta,
                                        ':doc_faltante'=>$doc_faltante));
            return $this->listarFiltrado(); 
        }

        public function obtenerCarpeta($id){
            $conn = conexion();
            $sentenciaSQL = $conn->prepare('SELECT `dni`,`apellido`, `nombre`,`celular`,`telefono`,`email`,`bibliorato`,`inscripcion_anterior`,`ultima_inscripcion`,`dado_de_baja`,`falta_carpeta`,`documentacion_faltante` FROM `carpetas` WHERE `id` = :identificador');
            $sentenciaSQL->execute(array(':identificador'=>$id));
            return $sentenciaSQL->fetch();
        }
 
        public function cambiarCarpeta($id,$dni,$apellido,$nombre,$celular,$telefono,$email,$bibliorato,$inscAnterior,$ultimaInsc,$doc_faltante,$falta_carpeta){
            $conn = conexion();
            $sentenciaSQL = $conn->prepare('UPDATE `carpetas` SET `dni`=:dni,`apellido`=:apellido,`nombre`=:nombre, `celular`=:celular, `telefono`=:telefono,`email`=:email, `bibliorato`=:bibliorato, `inscripcion_anterior`=:insc_ant,`ultima_inscripcion`=:ult_insc,`falta_carpeta`=:falta_carpeta,`documentacion_faltante`=:doc_faltante WHERE `id`=:identificador');
            $sentenciaSQL->execute(array(':identificador'=>$id,
                                        ':dni'=>$dni,
                                        ':apellido'=>$apellido,
                                        ':nombre'=>$nombre,
                                        ':celular'=>$celular,
                                        ':telefono'=>$telefono,
                                        ':email'=>$email,
                                        ':bibliorato'=>$bibliorato,
                                        ':insc_ant'=>$inscAnterior,
                                        ':ult_insc'=>$ultimaInsc,
                                        ':falta_carpeta'=>$falta_carpeta,
                                        ':doc_faltante'=>$doc_faltante));
            return $this->listarFiltrado();                
        }

        public function cambiarEstado($id,$estado){
            $conn = conexion();
            $sentenciaSQL = $conn->prepare('UPDATE `carpetas` SET `dado_de_baja`=:baja WHERE `id`=:identificador');
            $sentenciaSQL->execute(array(':identificador'=>$id,':baja'=>$estado));
            return $this->listarFiltrado();                                   
        }


        #Filtro por dni, apellido, nombre  o num de bibliorato
        public function listarFiltrado(){
            global $carpetas_config;
            $reg_por_pagina = $carpetas_config['registros_por_pagina'];
            // Determinamos desde qué registro se mostrará en pantalla
            $inicio = (pagina_actual() > 1) ? (pagina_actual() * $reg_por_pagina - $reg_por_pagina) : 0; 
            $conn = conexion();

            $filtro = isset($_SESSION['filtro']) ? $_SESSION['filtro'] : '';
            if ($filtro== 'bajas'){
                $filtro = 1;
                $sentenciaParaFiltrar = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * 
                                                    FROM `carpetas` 
                                                    WHERE dado_de_baja LIKE :filt
                                                    ORDER BY dni DESC
                                                    LIMIT {$inicio}, {$reg_por_pagina}");
            }
            elseif ($filtro == 'altas'){
                $filtro = 0;
                $sentenciaParaFiltrar = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * 
                                                    FROM `carpetas` 
                                                    WHERE dado_de_baja LIKE :filt
                                                    ORDER BY dni DESC
                                                    LIMIT {$inicio}, {$reg_por_pagina}");
            }
            elseif($filtro == 'todos'){
                $filtro = '';
                $sentenciaParaFiltrar = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * 
                                                    FROM `carpetas`  
                                                    WHERE apellido LIKE :filt OR nombre LIKE :filt OR dni LIKE :filt
                                                    ORDER BY dni DESC
                                                    LIMIT {$inicio}, {$reg_por_pagina}");
            }
            else{
                $sentenciaParaFiltrar = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * 
                                                    FROM `carpetas` 
                                                    WHERE apellido LIKE :filt OR nombre LIKE :filt 
                                                    OR dni LIKE :filt or bibliorato LIKE :filt
                                                    ORDER BY dni DESC
                                                    LIMIT {$inicio}, {$reg_por_pagina}");
            }
            $filtro = '%' . $filtro . '%';

            $sentenciaParaFiltrar->execute(array('filt' => $filtro));
            $resultado = $sentenciaParaFiltrar->fetchAll();

            #Ordenar resultado por numero de dni PROBLEMA, ORDENA POR PAGINA NO POR TODAS LAS CARPETAS
            #usort($resultado,"ordenar_por_DNI");

            $num_pag = $this->numero_paginas($conn);
            $ret[0] = $resultado;
            $ret[1] = $num_pag;

            return $ret;
        }

        # Función para calcular el número de páginas que tendrá la paginación.
        # Return: El numero de páginas
        private function numero_paginas($conexion){
            global $carpetas_config;
            $reg_por_pagina = $carpetas_config['registros_por_pagina'];

            // Calculamos el número de filas que devuelve la consulta
            $total_post = $conexion->prepare('SELECT FOUND_ROWS() as total');
            $total_post->execute();
            $total_post = $total_post->fetch()['total'];
            // Calculamos el número de páginas que habrá en la paginación
            $numero_paginas = ceil($total_post / $reg_por_pagina);
            return $numero_paginas;
        }


    }

    #Funcion para ordenar las carpetas por numero de DNI
    function ordenar_por_DNI($carpeta1,$carpeta2){
        if ($carpeta1['dni']==$carpeta2['dni']){ 
            return 0;
        }
        return ($carpeta1['dni']<$carpeta2['dni'])?-1:1;
    }

?>