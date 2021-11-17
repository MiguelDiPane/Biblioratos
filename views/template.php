<!--#Tiene como fin dar una macroestructura a todo el sitio-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset=utf-8>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/css/bootstrap.min.css">
    <link rel="stylesheet" href="views/css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Mi sistema</title>
</head>
<body>
    <header>

    </header>

    <?php include "modules/navegacion.php";?>

    <section>
        <?php
            $mvc = new MVController();
            $mvc-> route(); 
        ?>
    </section>

    <!--<footer class="container-fluid">
        <div class="row pt-4">
            <div class="col">
                <p>Sistema de Biblioratos</p>
            </div>
        </div>   
    </footer> -->

    <!--Scripts de bootrstrap-->
    <script src="views/js/bootstrap.min.js"></script>
    <script src="views/js/popper.min.js"></script>
</body>
</html>