<div class="container-fluid px-5 cuerpo_pagina">
    <h1 class="text-center">Carpetas</h1>
    <div class="row pt-4 justify-content-between">
        <div class="col-6">
            <div class="row justify-content-between">
                <div class="col">
                    <form action="index.php?action=abmCarpetas&order=filtrar" method="POST" class="d-flex">
                    <input name="filtro" class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </form>
                </div>
                <div class="col">
                <form action="index.php?action=abmCarpetas&order=filtrar" method="POST" class="d-flex">
                    <select class="form-select me-2" name="filtro" id="selector">
                        <option selected value="todos">Todos</option>
                        <option value="altas">Dados de alta</option>
                        <option value="bajas">Dados de baja</option>
                    </select>
                    <button class="btn btn-outline-success" type="submit">Mostrar</button>
                    </form>                    
                </div>
            </div>

        
        </div>

        <div class="col-auto">
            <a class="btn btn-primary" href="index.php?action=abmCarpetas&order=nuevo">Nueva carpeta</a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col" id="tablaFija">
        <?php
        if(isset($lista_carpetas)){
        if (empty($lista_carpetas)){
            echo "<h4 class=\"mt-4\">No se encontraron coincidencias</h4>";
        }
        else{
            echo "<table class=\"table\">
                <tr>
                    <th>DNI</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>Celular</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>N° Bibliorato</th>
                    <th>Inscripción anterior</th>
                    <th>Última inscripción</th>
                    <th>Extraviada</th>
                    <th>Documentación faltante</th>
                    <th>Modificar</th>
                </tr>";
                
                #Muestra las filas segun la cantidad de carpetas
                foreach($lista_carpetas as $fila){
                    if (isset($fila['falta_carpeta'])){
                        $faltaCarpeta= 'No';
                        if($fila['falta_carpeta'] == 1){
                            $faltaCarpeta = 'Si';
                        }
                    }

                    echo "<tr>
                        <td>$fila[dni]</td>
                        <td>$fila[apellido]</td>
                        <td>$fila[nombre]</td>
                        <td>$fila[celular]</td>
                        <td>$fila[telefono]</td>
                        <td>$fila[email]</td>
                        <td>$fila[bibliorato]</td>
                        <td>$fila[inscripcion_anterior]</td>
                        <td>$fila[ultima_inscripcion]</td>
                        <td>$faltaCarpeta</td>
                        <td>$fila[documentacion_faltante]</td>
                        <td class=\"text-center\">
                            <a href=\"index.php?action=abmCarpetas&order=modificar&id=$fila[id]\">
                                <img class=\"icono\" src=\"views/img/pencil.svg\" alt=\"modificar\">
                            </a>
                        </td>
                        </tr>";
                }
                echo "</table>";
            }
        }
        ?>
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
                    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?action=abmCarpetas&p=1\">1</a></li>";
                    echo "<li class=\"page-item disabled\"><a class=\"page-link\">...</a></li>";
                }
                # Creamos un elemento li por cada página que tengamos
                for ($i = $min; $i <= $max; $i++){
                    #Agregamos la clase active en la pagina actual
                    if (pagina_actual() === $i){
                        echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">$i</a></li>";
                    }
                    else{
                        echo "<li class=\"page-item\"> <a class=\"page-link\" href=\"index.php?action=abmCarpetas&p=$i\">$i</a></li>";
                    }
                }

                # si la ultima del grupo no es la ultima de la lista, mostramos la ultima y los ...
                if ($max != $numero_paginas) {
                    echo "<li class=\"page-item disabled\"><a class=\"page-link\">...</a></li>";
                    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"index.php?action=abmCarpetas&p=$numero_paginas\">$numero_paginas</a></li>";
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
                        <a class=\"page-link\" href=\"index.php?action=abmCarpetas&p=$pagina\" aria-label=\"Next\">
                            <span aria-hidden=\"true\">&raquo;</span>
                        </a>
                        </li>";
                }
            ?>
            </ul>
        </div>
    </div>

</div>


