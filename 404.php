<?php
    session_start();
    if(!isset($_SESSION['id_usuario'])){
        header('Location: login');
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Thonthon - 404 Pagina Nao Encontrada</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <?php 
    if(0 == $_SESSION['tema']){echo '<script src="lib/change-color.js" type="text/javascript"></script>';}?>
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="index" class="navbar-brand mx-4 mb-3">
                    <img style="width:200px"src="img/logo.png"></img>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="img/user<?php echo $_SESSION['pfp']; ?>" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $_SESSION['userName'];?></h6>
                        <?php if($_SESSION['id_usuario']==1){echo 'Admin';}else{echo'Membro Comum';}?>
                    </div>
                </div>
                <div class="navbar-nav w-100" >
                    <a href="home" class="nav-item nav-link"><i class="fa-solid fa-house me-2"></i>Home</a>
                    <a href="add" class="nav-item nav-link Pur"><i class="fa-solid fa-pen-to-square me-2"></i>Publicar</a>
                    <a href="search" class="nav-item nav-link Blue"><i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisar</a>
                    <a href="profile?id=<?php echo $_SESSION['id_usuario']; ?>" class="nav-item nav-link Ye"><i class="fa-solid fa-user-large me-2"></i>Ver Perfil</a>
                    <a href="config" class="nav-item nav-link "><i class="fa-solid fa-gear me-2"></i>Configurações</a>
                    <a href="lib/usuario.class.php" class="nav-item nav-link Pur"><i class="fa-solid fa-right-to-bracket me-2"></i>Sair</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class=" navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a class="sidebar-toggler flex-shrink-0">
                    <i style="color:rgba(99,7,237);" class="fa-solid fa-bars"></i>
                </a>
            </nav>
            <!-- Navbar End -->

            <!-- 404 Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-secondary rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6 text-center p-4">
                        <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
                        <h1 class="display-1 fw-bold">404</h1>
                        <h1 class="mb-4">Página não encontrada</h1>
                        <p class="mb-4">Desculpa, a página que você procurou não existe em nosso site!
                            Talvez ir para a home ou fazer uma pesquisa?</p>
                        <a class="btn btn-primary rounded-pill py-3 px-5" href="home">Ir para a Home</a>
                    </div>
                </div>
            </div>
            <!-- 404 End -->
            
            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="rgbShadow bg-secondary rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            Copyright &copy; | 2023 Thonthon
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            Feito Por:<a class="colorBlue" target="_blank" href="https://34productions.tech">&nbsp;34 Productions</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        </div>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
