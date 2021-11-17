<div class="container-fluid px-5 cuerpo_pagina">
    <?php
        if(!isset($carpeta)){
            echo "<h2 class=\"titulo text-center\">Nueva carpeta</h2>";
        }
        else{
            echo "<h2 class=\"titulo text-center ps-5 ms-5\">Modificar carpeta en bibliorato N° $carpeta[bibliorato]</h2>";
        }
    ?>

    <div class="row pt-4 justify-content-center">
        <div class="col-9">
            <form 
                <?php
                    if(!isset($carpeta)){
                        echo "action=\"index.php?action=abmCarpetas&order=insertar\"";
                    }
                    else{
                        echo "action=\"index.php?action=abmCarpetas&order=cambiar&id=$id\"";
                    }
                ?>
                method="POST" 
                class="formulario ">
                <div class="row justify-content-between">
                    <fieldset class="col-4">
                        <legend class="w-auto">Datos personales</legend>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control <?php if(isset($dniDuplicado)) echo 'bg-danger text-light'?>" name ="dni" id="dni" placeholder="Documento"
                            value="<?php 
                            if (isset($dniDuplicado)){
                                echo "EL DNI YA SE ENCUENTRA EN EL SISTEMA"; 
                            }
                            else{
                                echo isset($carpeta['dni']) ? $carpeta['dni'] : '';
                            }
                            ?>">
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" name ="apellido" id="usuario" placeholder="Apellido"
                            value="<?php echo isset($carpeta['apellido']) ? $carpeta['apellido'] : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name ="nombre" id="nombre" placeholder="Nombre"
                            value="<?php echo isset($carpeta['nombre']) ? $carpeta['nombre'] : '' ?>">
                        </div>
                    </fieldset>
                    <fieldset class="col-3">
                        <legend>Datos de contacto</legend>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" id="telefono" placeholder="Teléfono"
                            value="<?php echo isset($carpeta['telefono']) ? $carpeta['telefono'] : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="text" class="form-control" name="celular" id="celular" placeholder="Celular"
                            value="<?php echo isset($carpeta['celular']) ? $carpeta['celular'] : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Correo electrónico"
                            value="<?php echo isset($carpeta['email']) ? $carpeta['email'] : '' ?>">
                        </div>
                    </fieldset>
                    <fieldset class="col-4">
                        <legend>Datos de inscripción</legend>
                        <div class="mb-3">
                            <label for="inscAnterior" class="form-label">Inscripción anterior</label>
                            <input type="text" class="form-control" name="inscAnterior" id="inscAnterior"
                            value="<?php echo isset($carpeta['inscripcion_anterior']) ? $carpeta['inscripcion_anterior'] : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="ultimaInsc" class="form-label">Última inscripción</label>
                            <input type="text" class="form-control" name="ultimaInsc" id="ultimaInsc"
                            value="<?php echo isset($carpeta['ultima_inscripcion']) ? $carpeta['ultima_inscripcion'] : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="departamento" class="form-label">Departamento</label>
                            <select class="form-select me-2" name="departamento" id="selector">
                                <?php
                                if(!isset($departamento)){
                                echo "<option disabled selected>Seleccione un departamento</option>";
                                }
                                #Decodificar el json para volverlo array
                                $departamentos = json_decode($departamentos,true);
                                foreach($departamentos as $depto){

                                    #Para mostrar el departamento al modificar carpeta, departamento viene del formulario
                                    if($departamento == $depto['nombre_departamento']){
                                        echo "<option selected value=\"$departamento\">$departamento</option>";
                                    }
                                    else{
                                        echo "<option value=\"$depto[nombre_departamento]\">$depto[nombre_departamento]</option>";
                                    }
                                    
                                }
                                ?>
                            </select>
                            <input type="hidden" name="departamento_anterior" value="<?php echo isset($departamento) ? $departamento : '' ?>">
                        </div>
                    </fieldset>
                </div>
                <div class="row pt-3">
                    <fieldset class="col">
                    <div class="mb-3">
                        <legend class="w-auto">Otros datos</legend>
                        <div class="mb-3">
                        <label for="doc_faltante" class="form-label">Documentación faltante</label>
                        <textarea class="form-control" name="doc_faltante" id="doc_faltante" rows="3" 
                        placeholder="Documentación faltante o comentarios"><?php echo isset($carpeta['documentacion_faltante']) ? $carpeta['documentacion_faltante'] : ''; ?></textarea>
                        </div>
                    </div>
                    <?php 
                    if(isset($carpeta)){
                    echo "<div class=\"mb-3\">
                        <label for=\"falta_carpeta\" class=\"form-label\">Carpeta extraviada</label>
                        <select class=\"form-select me-2\" name=\"falta_carpeta\" id=\"selector\">";
                        if($carpeta['falta_carpeta'] == 1){
                            echo "<option selected value=\"1\">Si</option>
                            <option value=\"0\">No</option>";
                        }
                        else{
                            echo "<option value=\"1\">Si</option>
                            <option selected value=\"0\">No</option>";
                        }
                            
                    echo"</select>
                    </div>";
                    }
                    ?>
                    </fieldset>
                </div>
                <div class="row mt-4 <?php if(isset($carpeta)) echo "justify-content-between"; else echo "justify-content-center";?>">
                    <?php
                        if(isset($carpeta)){
                            echo "<div class=\"col-4 pt-4 mb-2 text-center\">
                                <div class=\"col-auto\">";
                            #Para mostrar boton de dar de baja o dar de alta
                            if ($carpeta['dado_de_baja'] == 0){
                                echo "<a href=\"index.php?action=abmCarpetas&order=checkPass&tipo=baja&id=$id\" class=\"btn btn-danger\">
                                Dar de baja</a>";
                            }
                            else{
                                echo "<a href=\"index.php?action=abmCarpetas&order=checkPass&tipo=baja&id=$id\" class=\"btn btn-success\">
                                Dar de alta</a>";
                            }
                            echo "</div></div>";
                        }
                    ?>
                    <div class="col-4 pt-4 mb-2 text-center">
                        <button type="submit" class="btn btn-primary mb-3">
                            <?php
                                if(!isset($carpeta)){
                                    echo "Agregar";
                                }
                                else{
                                    echo "Guardar cambios";
                                }
                            ?>
                        </button>
                    </div>
                </div>
                            
            </form>
        </div>
    </div>

</div>




