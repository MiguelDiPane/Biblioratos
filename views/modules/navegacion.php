
<!--Pasa parametros al archivo php Con signo ? separa los parametros, 
    variable1=valor1&variable2=valor2 Estos parametros van por el metodo GET-->
    
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo ROOT_PATH;?>">
        <img class="logo_nav" src="views/img/logo_nav.png" alt="logo_nav">
        Sistema de Biblioratos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center ">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=abmBiblioratos">Biblioratos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=abmCarpetas">Carpetas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=login">
                    <?php
                    if(isset($_SESSION['usuario'])){
                        echo "$_SESSION[usuario]";
                        echo '<img class="foto_perfil_nav" src="data:image/jpeg;base64,'.base64_encode( $_SESSION['foto'] ).'"/>';
                    }
                    else{
                        echo "Ingresar";
                    }
                    ?></a>                    
                </li>
            </ul>
        </div>
    </div>
</nav>
