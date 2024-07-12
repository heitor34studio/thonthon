<?php
    session_start();
	$erro = isset($_SESSION['jooj']) ? $_SESSION['jooj'] : 0;
    $okay = isset($_SESSION['gg']) ? $_SESSION['gg'] : 0;
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Thonthon - Cadastrar</title>
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
                                <h3 style="margin: auto">

                                <?php 
                                    if($okay){
                                        echo '<ajdhcflk style="color:#3fff00;">'.$_SESSION['gg'].'</ajdhcflk>';
                                    }else{
                                        echo 'Crie sua Conta';
                                    }
                                ?>

                                </h3>
                                
                            </div>
                            <div id="erroDiverso">
                                 <?php 
                                    if($erro){
                                        echo '<font style="color:#ff0000;">'.$_SESSION['jooj'].'</font>';
                                        unset($_SESSION['jooj']);
                                    }
                                ?>
                            </div>
                            
                            <?php 
                                if($okay){
                                    echo '';
                                    unset($_SESSION['gg']);
                                }else{
                                    echo '
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingText" placeholder="Renthon"
                                         name="nome" maxlength="20">
                                        <label for="floatingText"><i style="margin-right:5px" class="bi bi-file-person"></i>Nome de Usuário</label>

                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" maxlength="50">
                                        <label for="floatingInput"><i style="margin-right:5px" class="bi bi-mailbox"></i>Email</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="senha">
                                        <label for="floatingPassword"><i style="margin-right:5px" class="bi bi-lock-fill"></i>Senha</label>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input type="password" class="form-control" id="floatingPassword2" placeholder="PasswordRepeat" name="senha2">
                                        
                                        <script>';
                                        echo "
                                            $('.form-control').keyup(function(){
                                                var valueUser = $('#floatingText').val();
                                                var valueEmail = $('#floatingInput').val();
                                                var valuePass = $('#floatingPassword').val();
                                                var valuePass2 = $('#floatingPassword2').val();
                                                var type = this.id;
                                                    $.ajax({
                                                        url: 'lib/verifica.class.php',
                                                        method: 'post',
                                                        data: {valueUser: valueUser,valueEmail: valueEmail,valuePass: valuePass,valuePass2: valuePass2,type: type},
                                                        success: function(data){
                                                            $('#erroDiverso').val('');
                                                            $('#erroDiverso').html(data);
                                                        }
                                                    });
                                                });
                                        </script>";

                                        echo '<label for="floatingPassword2"><i style="margin-right:5px" class="bi bi-lock"></i>Repita a Senha</label>
                                    </div>

                                    <button disabled type="submit" class="btn btn-primary py-3 w-100 mb-4"id="zZz">Cadastrar<i style="margin-left:5px; font-size:20px"
                                    class="bi bi-arrow-right-circle"></i></button>
                                    <center><img src="img/load.gif" style="display:none;" id="load"></center>
                                    ';
                                }
                            ?>
                            
                            <p class="text-center mb-0">
                                <h4 style="margin: auto"><?php 
                                    if($okay){
                                        echo '(Cheque sua Caixa de Spam.)';
                                        unset($_SESSION['jooj']);
                                    }else{
                                        echo '';
                                    }
                                ?></h4>
                            </p>Já tem uma conta? <a href="login" class="colorYe">Logar</a>
                            <p class="text-center mb-0"><a href="index"class="colorBlue">&lt;&lt; Voltar</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign Up End -->
        </div>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </div>
</body>

</html>