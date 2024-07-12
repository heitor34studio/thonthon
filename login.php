<?php
    session_start();
	$erro = isset($_SESSION['jooj']) ? $_SESSION['jooj'] : 0;
    $erro_ativacao = isset($_SESSION['jooj']) ? $_SESSION['jooj'] : 0;
	if(isset($_SESSION['id_usuario'])){
		header('Location: home');
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
                                <h3 style="margin: auto">Logue na sua Conta</h3>
                            </div>
                            <div id="erroDiverso">
                                 <?php 
                                    if($erro){
                                        echo '<font style="color:#ff0000;">'.$_SESSION['jooj'].'</font>';
                                        unset($_SESSION['jooj']);
                                    }
                                ?>
                            </div>
                            
                            <form method="post" action="lib/usuario.class.php" id="formLogin">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingText" placeholder="Renthon"
                                 name="user" maxlength="50">
                                <label for="floatingText"><i style="margin-right:5px" class="bi bi-file-person"></i>Nome de Usuário ou Email</label>

                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" maxlength="60">
                                <label for="floatingPassword"><i style="margin-right:5px" class="bi bi-lock-fill"></i>Senha</label>
                            </div>

                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4"id="zZz">Logar<i style="margin-left:5px;
                             font-size:20px"class="bi bi-arrow-right-circle"></i></button>
                            </form>
                            
                            <p class="text-center mb-0">Não tem uma conta? <a href="cadastrar" class="colorYe">Cadastre-se</a></p>
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