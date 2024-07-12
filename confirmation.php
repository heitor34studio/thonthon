<?php
    session_start();
    require_once('lib/db.class.php');
    date_default_timezone_set('America/Sao_Paulo');
    if(!isset($_GET['type'])){
        header('Location: https://renthonthon.000webhostapp.com/login');
    }else{
        $type = addslashes($_GET['type']);
    }



    function confirmarEmail(){
        if(!isset($_GET['email'])){
            header('Location: http://localhost/twitter_clone/template/login');
        }else{
            if(strlen(addslashes($_GET['email']))>50){
                echo 'O email ultrapassa o limite de caracteres';
            }
            $email = addslashes($_GET['email']);
            $objDB = new db();
            $link = $objDB->conecta_mysql();
            $sql = " UPDATE `usuarios` SET `ATIVADO` = 'SIM' WHERE `usuarios`.`email` = '$email' ";
            if(mysqli_query($link, $sql)){
                echo '<ajdhcflk style="color:#3fff00;">Sua conta foi ativada com sucesso!</ajdhcflk>';
            }else{
                echo 'Não foi possível ativar seu email. Contate o Dev.';
            }
        }
        
    }


    function alterarEmail(){
        if(!isset($_GET['newEmail'])){
            header('Location: https://renthonthon.000webhostapp.com/login');
        }else{
            if(strlen(addslashes($_GET['newEmail']))>50){
                echo 'O email ultrapassa o limite de caracteres.';
                die();
            }
            if(strlen(addslashes($_GET['id']))>1){
                echo 'O id do usuario ultrapassa o limite de caracteres.';
                die();
            }
            $newEmail = addslashes($_GET['newEmail']);
            $objDB = new db();
            $link = $objDB->conecta_mysql();
            $sql = " SELECT COUNT(*) AS jaexiste FROM usuarios  WHERE email = '$newEmail' ";
            $emailExiste = mysqli_query($link, $sql);
            if($emailExiste){
                $qntd = mysqli_fetch_array($emailExiste, MYSQLI_ASSOC);
                if($qntd['jaexiste'] == 1){
                    echo 'Erro. Este email já está sendo utilizado.';
                }else{
                    $date = new DateTime(date("Y-m-d H:i:s", time()));
                    $tempo = $date->format('20y-m-d H:i:s');
                    $id_usuario = addslashes($_GET['id']);
                    $sql = " SELECT * FROM config WHERE id_user = $id_usuario ";
                    $uuu = mysqli_query($link, $sql);
                    if($uuu){
                        $registroUUU = mysqli_fetch_array($uuu, MYSQLI_ASSOC);
                        if($registroUUU['emTrocaEmail'] == 'SIM'){
                            if(isset($registroUUU['horaAlteracaoE'])){
                                $date2 = new DateTime(date($registroUUU['horaAlteracaoE']));
                                $interval = $date->diff($date2);
                                $total = $interval->format('%m') *43200 + $interval->format('%d') * 1440 + $interval->format('%h') * 60 + $interval->format('%i');
                                if($total < 60){
                                    $sql = " UPDATE `usuarios` SET `email` = '$newEmail' WHERE `usuarios`.`id` = '$id_usuario' ";
                                    if(mysqli_query($link, $sql)){
                                        $sql = " UPDATE `config` SET `emTrocaEmail` = 'NAO', `horaAlteracaoE` = NULL WHERE `config`.`id_user` = '$id_usuario' ";
                                        if(mysqli_query($link, $sql)){
                                            echo '<ajdhcflk style="color:#3fff00;">Seu email foi alterado com sucesso!</ajdhcflk>';
                                        }
                                    }else{
                                        echo 'Não foi possível alterar seu email. Contate o Dev.';
                                    }
                                }else{
                                    echo 'Erro. O link de ativação expirou, tente novamente.';
                                }
                            }
                        }else{
                            echo 'Esta conta não requisitou uma troca de Email.';
                        }
                    }else{
                        echo 'Erro ao se conectar com o servidor. #DateTime';
                    }
                }
            }else{
                echo 'Erro ao se conectar com o servidor.';
            }
        }
    }


?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Thonthon - Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <div class="register">
        <div class="container-fluid position-relative d-flex p-0">
            <!-- Spinner Start -->
            <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only"></span>
                </div>
            </div>
            <!-- Spinner End -->
            

            <!-- Sign Up Start -->
            <div class="container-fluid">
                <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div style="text-align: center;" class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3 rgbShadow">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 style="margin: auto"><?php 

                                if($type == 'newEmail'){
                                    alterarEmail();
                                }elseif($type == 'activate'){
                                    confirmarEmail();
                                }else{
                                    header('Location: https://renthonthon.000webhostapp.com/login');
                                }

                                 ?></h3>
                            </div>
                            <a href="login" class="colorYe">Fazer Login</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign Up End -->
        </div>

        
    </div>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>