<div class="container-fluid px-5 cuerpo_pagina">
    <h1 class="text-center">Biblioratos</h1>
    <div class="row pt-4 justify-content-between">
    <div class="col-7">
            <div class="row justify-content-between">
                <div class="col">
                    <form action="index.php?action=abmBiblioratos&order=filtrar" method="POST" class="d-flex">
                    <input name="filtroBib" class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </form>
                </div>
                <div class="col-auto">
                <form action="index.php?action=abmBiblioratos&order=filtrar" method="POST" class="d-flex">
                    <select class="form-select me-2" name="id_departamento" id="selector">
                        <?php
                            echo "<option disabled selected>Seleccione un departamento</option>";
                            
                            #Decodificar el json para volverlo array
                            $departamentos = json_decode($departamentos,true);
                            foreach($departamentos as $depto){
                                #Para mostrar el departamento al modificar carpeta, departamento viene del formulario
                                if(isset($departamento) and ($departamento == $depto)){
                                    echo "<option selected value=\"$depto[nombre_departamento]\">$depto[nombre_departamento]</option>";
                                }
                                else{
                                    echo "<option value=\"$depto[nombre_departamento]\">$depto[nombre_departamento]</option>";
                                }
                                
                            }
                        ?>
                    </select>
                    <button class="btn btn-outline-success ms-2" type="submit">Mostrar</button>                 
                </div>
            </div>

        
        </div>
    </div>
    <div class="row mt-3">
        <div class="col" id="tablaFija">
            <table class="table">
                <tr>
                    <th>Número</th>
                    <th>Departamento</th>
                    <th>Cantidad carpetas</th>
                    <th class="text-center">Bibliorato disponible para carga de carpetas o lleno físicamente</th>                 
                </tr>
                <?php
                #Muestra las filas segun la cantidad de biblioratos
                    if(isset($lista_biblioratos)){
                        foreach($lista_biblioratos as $fila){
                            $orden = $fila['lleno_fisicamente']  ? array('orden'=>'Vaciar','color'=>'btn-danger') : 
                                                                array('orden'=>'Llenar','color'=>'btn-success');
                            echo "<tr>
                                <td>$fila[num_bibliorato]</td>
                                <td>$fila[nombre_departamento]</td>
                                <td>$fila[cantidad_carpetas]</td>";

                            if(isset($_SESSION['admin'])){
                                if ($orden['orden'] == "Vaciar"){
                                    echo "<td class=\"text-center\">
                                    <a href=\"index.php?action=abmBiblioratos&order=llenar&numeroBib=$fila[num_bibliorato]\" 
                                    class=\"btn $orden[color]\">Lleno
                                    <img class =\" mx-2 icono\" src=\"views/img/candado_cerrado.svg\" alt=\"candado_abierto\">
                                    </a></td>
                                    </tr>";
                                }
                                else{
                                    echo "<td class=\"text-center\">
                                    <a href=\"index.php?action=abmBiblioratos&order=llenar&numeroBib=$fila[num_bibliorato]\" 
                                    class=\"btn $orden[color]\">Disponible
                                    <img class =\" mx-2 icono\" src=\"views/img/candado_abierto.svg\" alt=\"candado_abierto\">
                                    </a></td>
                                    </tr>";                                    
                                }
                            }
                            #Deshabilitar posibilidad de cliquear el boton, es seguro de esta manera? CONSULTAR
                            else{
                                if ($orden['orden'] == "Vaciar"){
                                    echo "<td class=\"text-center\">
                                    <a disabled ='true' href=\"#\" 
                                    class=\"btn $orden[color]\">Lleno
                                    <img class =\" mx-2 icono\" src=\"views/img/candado_cerrado.svg\" alt=\"candado_abierto\">
                                    </a></td>
                                    </tr>";
                                }
                                else{
                                    echo "<td class=\"text-center\">
                                    <a disabled ='true' href=\"#\" 
                                    class=\"btn $orden[color]\">Disponible
                                    <img class =\" mx-2 icono\" src=\"views/img/candado_abierto.svg\" alt=\"candado_abierto\">
                                    </a></td>
                                    </tr>";                                    
                                }                                
                            }
                        }
                    }
                ?>
            </table>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-auto">
            <ul class="pagination">
            <?php 
                $rango = 3;
                #Mostrar las X páginas anteriores y las X siguientes a la actual (segun rango)
                $min = (pagina_actual() - $rango) > 1 ? pagina_actual() - $rango : 1;
                $max = (pagina_actual() + $rango) < $numero_paginas ? pagina_actual() + $rango : $numero_paginas;


                #Mostramos el boton para retroceder una página
                if (pagina_actual() == 1){
                    echo "<li class=\"page-item disabled\">
                            <a class=\"page-link\" aria-label=\"Previous\" href=\"#\">
                                <span aria-hidden=\"true\">&laquo;</span>
                            </a>
                        </li>";
                } 
                else{
                    $pagina = pagina_actual() - 1;
                    echo "<li class=\"page-item\">
                            <a class=\"page-link\" aria-label=\"Previous\" href=\"index.php?action=abmCarpetas&p=$pagina\">
                                <span aria-hidden=\"true\">&laquo;</span>
                            </a>
                        </li>";
                } 

                # si la primera del grupo no es la página 1, mostramos la 1 y los ...
                if ($min != 1) {
                    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?action=abmBiblioratos&p=1\">1</a></li>";
                    echo "<li class=\"page-item disabled\"><a class=\"page-link\">...</a></li>";
                }

                # Creamos un elemento li por cada página que tengamos de primera a última
                for ($i = $min; $i <= $max; $i++){
                    #Agregamos la clase active en la pagina actual
                    if (pagina_actual() === $i){
                        echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">$i</a></li>";
                    }
                    else{
                        echo "<li class=\"page-item\"> <a class=\"page-link\" href=\"index.php?action=abmBiblioratos&p=$i\">$i</a></li>";
                    }
                }

                # si la ultima del grupo no es la ultima de la lista, mostramos la ultima y los ...
                if ($max != $numero_paginas) {
                    echo "<li class=\"page-item disabled\"><a class=\"page-link\">...</a></li>";
                    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?action=abmBiblioratos&p=$numero_paginas\">$numero_paginas</a></li>";
                }

                #Mostramos el boton para avanzar una pagina
                if (pagina_actual() == $numero_paginas){
                    echo "<li class=\"page-item disabled\">
                        <a class=\"page-link\" href=\"#\" aria-label=\"Next\">
                            <span aria-hidden=\"true\">&raquo;</span>
                        </a>
                        </li>";
                }
                else{
                    $pagina = pagina_actual() + 1;
                    echo "<li class=\"page-item\">
                        <a class=\"page-link\" href=\"index.php?action=abmBiblioratos&p=$pagina\" aria-label=\"Next\">
                            <span aria-hidden=\"true\">&raquo;</span>
                        </a>
                        </li>";
                }
            ?>
            </ul>
        </div>
    </div>
</div>
