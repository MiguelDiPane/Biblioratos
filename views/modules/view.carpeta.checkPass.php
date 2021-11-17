<div class="container cuerpo_logueo">
    <div class="row justify-content-center">
        <div class="col-5 mb-5">
        <h2 class="text-center">
            <?php 
                if($operacion == 'baja'){
                    if($carpeta['dado_de_baja'] == 0){
                        echo "Dar de baja";
                    }
                    else{
                        echo "Dar de alta";
                    }
                    
                }
            ?> 
        </h2>
        <h5 class="text-center">
            <?php 
                echo "$carpeta[apellido] $carpeta[nombre]";
            ?> 
        </h5>
        
        <form action=<?php 
            if($operacion == 'baja'){
                echo "index.php?action=abmCarpetas&order=baja&id=$id&estado=$carpeta[dado_de_baja]";
            }
        ?> 
        method="POST" class="formulario mt-5"> 
                <div class="mb-3">
                <label for="clave" class="form-label">Ingrese su contraseña para confirmar</label>
                <input type="password" class="form-control" name="clave" id="clave" placeholder="Contraseña" required>
                </div>
                <div class="row justify-content-between mt-4">
                    <div class="col-auto">
                        <?php
                            echo "<a href=\"index.php?action=abmCarpetas&order=modificar&id=$id\" type=\"button\" class=\"btn btn-primary\">
                            Cancelar</a>";
                        ?>

                    </div>
                    <div class="col-auto">
                        <?php
                            if($operacion == 'baja'){
                                if($carpeta['dado_de_baja'] == 0){
                                    echo "<button type=\"submit\" class=\"btn btn-danger\">Dar de baja</button>";
                                }
                                else{
                                    echo "<button type=\"submit\" class=\"btn btn-success\">Dar de alta</button>";
                                }
                            }                           
                        ?>
                    </div>
                </div>                               
        </form>
        <?php
        if (isset($mensaje)){
            if($mensaje == 'error'){
                echo "<p class=\"mt-5 text-center text-danger\">Contraseña incorrecta</p>";
            }   
        }
        ?>
        </div>
    </div>
</div>

