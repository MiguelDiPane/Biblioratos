<?php
    require_once "models/model.carpetas.php";
    require_once "models/model.login.php";
    require_once "models/model.biblioratos.php"; 
    require_once "models/model.departamentos.php";

    class ABMCarpetasController{
        public function init(){
            $model = new ABMCarpetas();
            $modelBib = new ABMBiblioratos();
            $modelDeptos = new Departamentos();
            #Segun la orden es lo que va a mostrar
            if(isset($_GET['order'])){
                $order = $_GET['order'];
                if($order == 'nuevo'){
                    $departamentos = $modelDeptos->list();
                    include "views/modules/view.carpeta.form.php";
                }
                elseif($order == 'insertar'){
                    try{
                        $dni = $_POST['dni'];
                        $apellido = $_POST['apellido'];
                        $nombre = $_POST['nombre'];
                        $celular = $_POST['celular'];
                        $telefono = $_POST['telefono'];
                        $email = $_POST['email'];
                        $departamento = $_POST['departamento'];
                        $inscAnterior = $_POST['inscAnterior'];
                        $ultimaInsc = $_POST['ultimaInsc'];
                        $baja = 0; //Al insertar la carpeta, el docente esta dado de alta
                        $falta_carpeta = 0; //La carpeta no esta extraviada al cargarla en el sistema
                        $doc_faltante = isset($_POST['doc_faltante']) ? $_POST['doc_faltante'] : '';
    
                        $num_bibliorato = $model->get_NumBibliorato_inicial($departamento,$dni);
                        $num_bibliorato = $modelBib->agregar_carpeta_a_bibliorato($num_bibliorato,$departamento);
    
                        
                        $model->agregarCarpeta($dni,$apellido,$nombre,$celular,$telefono,$email,
                                            $num_bibliorato,$inscAnterior,$ultimaInsc,$baja,$doc_faltante,$falta_carpeta);
                        $_SESSION['filtro'] = $dni;
                        $this->listar($model);
                    }
                    catch (PDOException $e) {
                        $dniDuplicado = True;
                        include "views/modules/view.carpeta.form.php";
                    }
                    
                    
                }
                elseif($order == 'checkPass'){
                    $id = $_GET['id'];
                    $carpeta = $model->obtenerCarpeta($id);
                    $operacion = $_GET['tipo'];
                    include "views/modules/view.carpeta.checkPass.php";
                }
                elseif($order == 'modificar'){
                    $id = $_GET['id'];
                    $carpeta = $model->obtenerCarpeta($id);
                    if($carpeta){
                        $bibliorato = $modelBib->obtenerBibliorato($carpeta['bibliorato']);
                        $departamentos = $modelDeptos->list();
                        $departamento = $bibliorato['nombre_departamento'];
                        include "views/modules/view.carpeta.form.php";
                    }
                    else{
                        echo "No está la carpeta buscada";
                    }
                }
                elseif($order == 'cambiar'){
                    $id = $_GET['id'];
                    $dni = $_POST['dni'];
                    $apellido = $_POST['apellido'];
                    $nombre = $_POST['nombre'];
                    $celular = $_POST['celular'];
                    $telefono = $_POST['telefono'];
                    $email = $_POST['email'];
                    $departamento_anterior = $_POST['departamento_anterior'];
                    $departamento = $_POST['departamento'];

                    $inscAnterior = $_POST['inscAnterior'];
                    $ultimaInsc = $_POST['ultimaInsc'];
                    $baja = isset($_POST['dado_de_baja']) ? 1 : 0;
                    $falta_carpeta = $_POST['falta_carpeta'];
                    $doc_faltante = $_POST['doc_faltante'];

                    #Llamar a función getNumBibliorato($departamento,$dni)
                    #------------------------------------------------#
                    #Actualizar bibliorato donde se agregó la carpeta#
                    #------------------------------------------------#
                    $num_bibliorato_anterior = $model->get_NumBibliorato_anterior($departamento_anterior,$dni); 
                    $modelBib->quitarCarpeta($num_bibliorato_anterior);
                    $num_bibliorato = $model->get_NumBibliorato_inicial($departamento,$dni);                  
                    $num_bibliorato = $modelBib->agregar_carpeta_a_bibliorato($num_bibliorato,$departamento);

                    $model->cambiarCarpeta($id,$dni,$apellido,$nombre,
                                            $celular,$telefono,$email,
                                            $num_bibliorato,$inscAnterior,$ultimaInsc,
                                            $doc_faltante,$falta_carpeta);
                    $_SESSION['filtro'] = $dni;
                    $this->listar($model);                      
                }
                elseif ($order =='filtrar'){
                    $_SESSION['filtro'] = isset($_POST['filtro']) ?
                    trim($_POST['filtro']) : '';
                    $this->listar($model);
                }
                elseif($order == 'baja'){
                    $id = $_GET['id'];
                    $modelLogin = new LoginModel();
                    $check = $modelLogin->login($_SESSION['usuario'],$_POST['clave']);
                    if ($check){
                        $estado = !$_GET['estado']; #Invierto el valor de estado para dar de baja o de alta
                        $model->cambiarEstado($id,$estado);
                        $this->listar($model);
                    }
                    else{
                        $mensaje = 'error';
                        $operacion = 'baja';
                        $carpeta = $model->obtenerCarpeta($id);
                        include "views/modules/view.carpeta.checkPass.php";
                    }
                    
                }
            }

            #Si order no esta pedida, mostrar solo la lista
            else{
                $this->listar($model);
            }

        }

        private function listar($model){
            $filas_carpetas = $model->listarFiltrado();
            #lista_carpetas es usado por el view.abmCarpetas.lista.php
            $lista_carpetas = $filas_carpetas[0];
            $numero_paginas = $filas_carpetas[1];
            include "views/modules/view.abmCarpetas.lista.php";
        }
    }





?>