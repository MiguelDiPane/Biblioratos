<?php
    class ABMBiblioratos{

        #Agrega la carpeta al bibliorato, de necesitar crea uno nuevo
        public function agregar_carpeta_a_bibliorato($num_bibliorato,$departamento){
            $bibliorato = $this->obtenerBibliorato($num_bibliorato);
            $lleno_fisicamente = $bibliorato['lleno_fisicamente'];
            
            while ($lleno_fisicamente == 1){
                $num_bibliorato += 0.1; #Cada nuevo bib sera .1,.2,... (php hace cast entre string y float)

                #Chequeo si existe en la tabla biblioratos, sino lo agrego a la tabla
                $bibliorato = $this->obtenerBibliorato($num_bibliorato);
                if ($bibliorato == false){
                    $this->agregarBibliorato($num_bibliorato,$departamento,0,0); #cant carpetas = 0, lleno fisicamente = 0
                    $lleno_fisicamente = 0;
                }
                else{
                    $lleno_fisicamente = $bibliorato['lleno_fisicamente'];
                }
            } 
            $this->agregarCarpeta($num_bibliorato);

            return $num_bibliorato;
        }

        public function agregarBibliorato($num_bibliorato,$nombre_departamento,$cantidad_carpetas,$lleno_fisicamente){
            $conn = conexion();
            $sentenciaSQL = $conn->prepare('SELECT `id_departamento` 
                                            FROM `departamentos`
                                            WHERE `nombre_departamento` = :nombre');
                                            
            $sentenciaSQL->execute(array(':nombre'=>$nombre_departamento));
            $id_departamento = $sentenciaSQL->fetch();

            $sentenciaSQL = $conn->prepare('INSERT INTO `biblioratos`(`num_bibliorato`, `id_departamento`,`cantidad_carpetas`,`lleno_fisicamente`) VALUES (:num,:id_departamento,:cant_carpetas,:lleno)');
            #Indicar por arreglo asociativo el valor de los datos
            $sentenciaSQL->execute(array(':num'=>$num_bibliorato,
                                        ':id_departamento'=>$id_departamento[0],
                                        ':cant_carpetas'=>$cantidad_carpetas,
                                        ':lleno'=>$lleno_fisicamente));
            return $this->listarBibFiltrado(); 
        }

        public function obtenerBibliorato($num_bibliorato){
            $conn = conexion();
            $sentenciaSQL = $conn->prepare('SELECT `id_departamento`, `cantidad_carpetas`,`lleno_fisicamente`, `nombre_departamento` 
                                        FROM `biblioratos` 
                                        INNER JOIN `departamentos`
                                        USING(`id_departamento`) 
                                        WHERE `num_bibliorato` = :num');
            $sentenciaSQL->execute(array(':num'=>$num_bibliorato));
            return $sentenciaSQL->fetch();

            
        }

        public function agregarCarpeta($num_bibliorato){
            $bibliorato = $this->obtenerBibliorato($num_bibliorato);
            $conn = conexion();
            $cantidad_carpetas = $bibliorato['cantidad_carpetas'] + 1;
            if ($cantidad_carpetas == 30){
                $lleno = 1;
            }
            else{
                $lleno = 0;    
            }
            $sentenciaSQL = $conn->prepare('UPDATE `biblioratos` SET  `cantidad_carpetas`=:cant, `lleno_fisicamente`=:lleno WHERE `num_bibliorato` = :num');
            $sentenciaSQL->execute(array(':num'=>$num_bibliorato,
                                        ':cant'=>$cantidad_carpetas,
                                        ':lleno'=>$lleno)); 
            return $lleno;       
        }

        public function quitarCarpeta($num_bibliorato){
            $bibliorato = $this->obtenerBibliorato($num_bibliorato);
            $cantidad_carpetas = $bibliorato['cantidad_carpetas'] - 1;
            $conn = conexion();
            if ($cantidad_carpetas == 0){
                $lleno = 0;
            }
            else{
                $lleno = $bibliorato['lleno_fisicamente'];
            }
            $sentenciaSQL = $conn->prepare('UPDATE `biblioratos` SET  `cantidad_carpetas`=:cant, `lleno_fisicamente`=:lleno WHERE `num_bibliorato` = :num');
            $sentenciaSQL->execute(array(':num'=>$num_bibliorato,
                                        ':cant'=>$cantidad_carpetas,
                                        ':lleno'=>$lleno));       
        }
        #Para cuando físicamente este lleno aunque tenga menos de 30 carpetas, o para fisicamente poner que aun tiene espacio
        public function llenarBibliorato($num_bibliorato,$estado){
            $conn = conexion();
            #Estado es 1 (lleno), 0 (con espacio aun)
            if ($estado == 1) $nuevo_estado = 0;
            else $nuevo_estado = 1;
            $sentenciaSQL = $conn->prepare('UPDATE `biblioratos` SET  `lleno_fisicamente`=:lleno WHERE `num_bibliorato` = :num');
            $sentenciaSQL->execute(array(':num'=>$num_bibliorato,
                                        ':lleno'=>$nuevo_estado));
            return $this->listarBibFiltrado(); 
        }

        #Filtro por dni, apellido, nombre  o num de bibliorato
        public function listarBibFiltrado(){
            global $carpetas_config;
            $reg_por_pagina = $carpetas_config['registros_por_pagina'];
            // Determinamos desde qué registro se mostrará en pantalla
            $inicio = (pagina_actual() > 1) ? (pagina_actual() * $reg_por_pagina - $reg_por_pagina) : 0; 
            $conn = conexion();


            $filtro = isset($_SESSION['filtroBib']) ? $_SESSION['filtroBib'] : '';
            $sentenciaParaFiltrar = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS 
                                            `num_bibliorato`,`nombre_departamento`, `cantidad_carpetas`,`lleno_fisicamente` 
                                            FROM `biblioratos` 
                                            INNER JOIN `departamentos`
                                            USING(`id_departamento`)
                                            WHERE `num_bibliorato` LIKE :filt 
                                            OR `nombre_departamento` LIKE :filt 
                                            LIMIT {$inicio}, {$reg_por_pagina}");
            $filtro = '%' . $filtro . '%';

            $sentenciaParaFiltrar->execute(array('filt' => $filtro));
            $resultado = $sentenciaParaFiltrar->fetchAll();
            $num_pag = $this->numero_paginas($conn);
            $ret[0] = $resultado;
            $ret[1] = $num_pag;
            return $ret;
        }

        # Función para calcular el número de páginas que tendrá la paginación.
        # Return: El numero de páginas
        private function numero_paginas($conexion)
        {
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



?>