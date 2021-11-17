<div class="container cuerpo_logueo">
    <div class="row mb-5">

    </div>
    <div class="row align-items-center">
        <div class="col-6 text-center">
            <img class="logo" src="views/img/logo.png" alt="logo">
            <h5>Junta de clasificación docente Rama Técnica, Agrotécnica, Monotécnica,  y de Capacitación Laboral</h5>
        </div>
        <div class="col-5">
            <?php
                if(!isset($_SESSION['usuario'])){
            ?>
            
            <fieldset class="p-4">
                <h2 class="titulo text-center">Iniciar sesión</h2>
                <form action="index.php?action=login&order=iniciar_sesion" method="POST" class="formulario">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" name ="usuario" id="usuario" placeholder="Usuario">
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="clave" id="clave" placeholder="Contraseña" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mb-3">Iniciar sesión</button>
                    </div>
                </form>
            </fieldset>

            <?php
                }
                else{

            ?>
            <fieldset class="p-4">
                <h2 class="titulo text-center">Usuario logueado</h2>
                <div class="row">
                    <div class="col-5 text-center">
                    <?php
                    echo '<img class="foto_perfil" src="data:image/jpeg;base64,'.base64_encode( $_SESSION['foto'] ).'"/>';
                    ?>                        
                    </div>
                    <div class="col-7">
                    <?php
                    echo "<div class=\"mb-3\">
                        <label for=\"nombre_u\" class=\"form-label\">Nombre y apellido</label>
                        <input type=\"text\" class=\"form-control\" name =\"nombre_u\" placeholder=\"$_SESSION[nombre_apellido]\" disabled>
                        </div>";
                    echo "<div class=\"mb-3\">
                        <label for=\"rol\" class=\"form-label\">Rol</label>
                        <input type=\"text\" class=\"form-control\" name =\"rol\" placeholder=\"$_SESSION[rol]\" disabled>
                        </div>";
                    ?>                        
                    </div>
                </div>
                <form action="index.php?action=login&order=cerrar_sesion" method="POST" class="formulario">
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger mb-3">Cerrar sesión</button>
                    </div>
                    <input type="hidden" name="logout" value="1">
                </form>
            </fieldset>
            <?php
                }
                if (isset($mensaje)){
                    if($mensaje == 'error'){
                        echo "<p class=\"text-center text-danger\">Usuario o contraseña incorrectos</p>";
                    }   
                }
            ?>
        </div>
    </div>
</div>
