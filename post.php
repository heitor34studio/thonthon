<?php
    session_start();
    $id_usuario = addslashes($_SESSION['id_usuario']);
    if(!isset($_SESSION['id_usuario'])){
        header('Location: login');
    }
    require_once('lib/db.class.php');
    if(!isset($_GET['id'])){
        header('http://localhost/thonthon/404');
    }else{
        if(strlen($_GET['id'])>4){
            header('http://localhost/thonthon/404');
        }
    }
        function puxaPost(){
            $id_usuario = addslashes($_SESSION['id_usuario']);
            $id = addslashes($_GET['id']);    
            $objDB = new db();
            $link = $objDB->conecta_mysql();
            $sql = " SELECT u.usuario, u.pfp, p.* FROM usuarios AS u LEFT JOIN publicacoes AS p ON (u.id = p.id_usuario) WHERE p.id_public = $id ";
            $resultado_id = mysqli_query($link, $sql);
            if($resultado_id){
                $registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
                if($registro == NULL){
                    echo "<script>window.location.href='404'</script>";
                    die();
                }else{
                    $name = $registro['usuario'];
                    $texto = $registro['public'];
                    $pfp = $registro['pfp']; 
                    $id_de_quem_publicou = $registro['id_usuario'];
                    $data = $registro['data_inclusao'];
                    $id_public = $id;
                     echo '<div id="post'.$id_public.'"><div class="bg-secondary rounded h-100 p-4">


                        <!-- User AND post -->


                        <div style="display: inline-flex;" class="align-items-center">
                            <div class="position-relative">
                                <a href="profile?id='.$id_de_quem_publicou.'"><img class="rounded-circle img-post" src="img/user'.$pfp.'"></a>
                            </div>
                            <div class="ms-3">
                                <a href="profile?id='.$id_de_quem_publicou.'"><h3 class="mb-0">'.$name.'</h3><small style="color:#a1a1a1">'.$data.'</small></a><h4>'.$texto.'<h4></div>
                        </div>
                        <div id="contents'.$id_public.'" style="display: contents;" class="align-items-center">


                            <!-- BUTTONS -->


                            <input type="text" class="far_away" id="link'.$id_public.'" name="link'.$id_public.'" 
                            value="http://localhost/thonthon/post?id='.$id_public.'">
                            <input type="text" class="far_away" id="txt'.$id_public.'" name="txt'.$id_public.'" 
                            value="'.$texto.'">';
                            if($id_usuario == $id_de_quem_publicou){
                                echo '
                                <button id="btn_delete'.$id_public.'" type="button" class="postBtns" style="display: inline;float: right;"><i class="fa-solid fa-trash-can"></i></button>';
                            }
                    echo '<button id="btn_link'.$id.'" type="button" class="postBtns" style="display: inline;float: right;"><i class="fa-solid fa-share"></i></button>';
                    echo '<button id="btn_txt'.$id.'" type="button" class="postBtns" style="display: inline;float: right;"><i class="bi bi-files"></i></button>';

                    echo "<script>
                            $(document).ready(function(){
                            var postIdTrash".$id_public." = ".$id_public.";
                            $('#btn_link".$id_public."').click(function(){
                                $('#link".$id_public."').select();
                                document.execCommand('copy');
                                $.ajax({
                                    url: 'lib/successBtn.html',
                                    success: function(data){
                                        $('.customDiv').css('display', 'flex');
                                        $('.customDiv').html(data);
                                    }
                                });
                            });
                            $('#btn_txt".$id_public."').click(function(){
                                $('#txt".$id_public."').select();
                                document.execCommand('copy');
                                $.ajax({
                                    url: 'lib/successBtn1.html',
                                    success: function(data){
                                        $('.customDiv').css('display', 'flex');
                                        $('.customDiv').html(data);
                                    }
                                });
                            });";
                            if($id_usuario == $id_de_quem_publicou){
                        
                                echo "
                            $('#btn_delete".$id_public."').click(function(){
                                $.ajax({
                                    url: 'lib/deleteit.php',
                                    data: { postIdTrash: postIdTrash".$id_public." },
                                    method: 'post',
                                    success: function(data){
                                        $('.customDiv').css('display', 'flex');
                                        $('.customDiv').html(data);
                                    }
                                });
                            });";

                        }
                        echo "});
                        </script>
                        </div>";
                        echo '
                        </div>
                        <div class="bg-secondary rounded h-100" style="margin-top:-15px;padding-bottom:5px;">
                            <div style="display: inline-flex;" class="align-items-center">
                                <div class="position-relative">
                                    <div id="loveDiv'.$id_public.'">
                                        <button id="btn_love'.$id_public.'" type="button" class="postBtns" style="display: inline;margin-left: 30px;"><i class="bi bi-heart';





                                        $blob =  " SELECT * FROM likes WHERE id_de_quem_curtiu = $id_usuario AND id_public = $id_public ";
                                        $juuj = mysqli_query($link, $blob);
                                        if($juuj){
                                            $juj_registro = mysqli_fetch_array($juuj, MYSQLI_ASSOC);
                                            if(isset($juj_registro['id_public'])){
                                                echo '-fill';
                                            }else{echo '';}
                                        }else{
                                            echo '';
                                        }



                                  echo '"></i>
                                        </button>
                                        <h6 style="display: inline;">';


                                        $blob1 =  " SELECT COUNT(*) AS qntd_likes FROM likes WHERE id_public = $id_public ";
                                        $juuj1 = mysqli_query($link, $blob1);
                                        if($juuj1){
                                            $juuj_0 = mysqli_fetch_array($juuj1, MYSQLI_ASSOC);
                                            echo $juuj_0['qntd_likes'];
                                        }else{
                                            echo 'Erro ao conectar com o servidor.';
                                        }


                                        echo '</h6>
                                    <script>';

                        echo "
                                            var postId".$id_public." = ".$id_public.";
                                            $('#btn_love".$id_public."').click(function(){
                                            $.ajax({
                                                url: 'lib/likeConfig.class.php',
                                                method: 'post',
                                                data: { postIdLike: postId".$id_public." },
                                                success: function(data){
                                                    $('#loveDiv".$id_public."').html(data);
                                                }
                                            });
                                        });
                                    </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </br>
                        </div>";
                    }
            }else{
                echo 'Erro ao se conectar com o servidor. Contate o Dev. #POST001';
            }
        }
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Thonthon - Post</title>
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

    <!-- Additional Scripts -->
    <script type="text/javascript">
        $(document).ready(function(){
            
        });
    </script>
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
                    <a href="profile?id=<?php echo $_SESSION['id_usuario']; ?>" class="nav-item nav-link Ye <?php if($_SESSION['id_usuario']==$id){echo 'active';}else{echo'';}?>"><i class="fa-solid fa-user-large me-2"></i>Ver Perfil</a>
                    <a href="config" class="nav-item nav-link"><i class="fa-solid fa-gear me-2"></i>Configurações</a>
                    <a href="lib/usuario.class.php" class="nav-item nav-link Pur"><i class="fa-solid fa-right-to-bracket me-2"></i>Sair</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a class="sidebar-toggler flex-shrink-0">
                    <i style="color:rgba(99,7,237);" class="fa-solid fa-bars"></i>
                </a>
            </nav>
            <!-- Navbar End -->

            <!-- Message -->
            <div class="container-fluid pt-4 px-4">
                <?php puxaPost(); ?>
            </div>
            <!-- Message End -->
            
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

        <div class="customDiv" style="display: none;">
        </div>


        </div>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>