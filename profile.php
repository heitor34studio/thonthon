<?php
    session_start();
    if(!isset($_SESSION['id_usuario'])){
        header('Location: login');
    }
    $id_usuario = addslashes($_SESSION['id_usuario']);
    require_once('lib/db.class.php');
    if(!isset($_GET['id'])){
        header('Location: http://localhost/twitter_clone/template/404');
    }else{
        if(strlen($_GET['id'])>4){
            header('Location: http://localhost/twitter_clone/template/404');
        }else{
            $id = addslashes($_GET['id']);    
            $objDB = new db();
            $link = $objDB->conecta_mysql();
            $sql = " SELECT u.*, us.* FROM usuarios AS u LEFT JOIN usuarios_seguidores AS us ON (us.id_usuario = $id_usuario AND u.id = us.seguindo_id_usuario) WHERE u.id = $id ";
            $resultado_id = mysqli_query($link, $sql);
            if($resultado_id){
                $registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
                if($registro == NULL){
                    $atv = 'NAO';
                }else{
                    $atv = $registro['ATIVADO'];
                }
                if($atv == 'NAO'){
                    header('Location: http://localhost/twitter_clone/template/404');
                }else{
                    $name = $registro['usuario'];
                    $bio = $registro['biografia'];
                    $pfp = $registro['pfp'];
                    $deuFollow = $registro['id_usuario_seguidor'];
                    $sql = " SELECT COUNT(CASE WHEN `id_usuario` = '$id' THEN 1 END) AS count1, COUNT(CASE WHEN `seguindo_id_usuario` = '$id' THEN 1 END) AS count2 FROM `usuarios_seguidores` ";
                    $qntds = mysqli_query($link, $sql);
                    if($qntds){
                        $finally = mysqli_fetch_array($qntds, MYSQLI_ASSOC);
                        $seguindo = $finally['count1'];
                        $seguidores = $finally['count2'];
                    }else{
                        $seguindo = '?';
                        $seguidores = '?';
                    }
                }
            }else{
                $name = 'Erro ao se conectar com o servidor.';
            }
        }
    }

        //FUNÇÃO CHAMA POSTS PRO PERFIL
    function puxaPosts(){
        $id_usuario = addslashes($_SESSION['id_usuario']);
        $objDB = new db();
        $link = $objDB->conecta_mysql();
        $id = addslashes($_GET['id']);
        $sql = " SELECT COUNT(*) AS 'oi' FROM publicacoes WHERE id_usuario = $id ORDER BY data_inclusao DESC ";
        $contador = mysqli_query($link, $sql);
        if($contador){
            $QNTD = mysqli_fetch_array($contador, MYSQLI_ASSOC);
            if($QNTD['oi'] > 0){
                $profileEnd = '';
            }else{
                $profileEnd = '<div class="text-center bg-secondary rounded h-100 p-4">
                            <div style="display: block;" class="h-100 rounded p-4 anim">
                            <center><img class="anim" src="img/pac'.$_SESSION['tema'].'.gif"></center>
                            </div>
                            <h3 class="mb-4">É... este usuário não postou nada ainda.</h3>
                            </div>';
            }
        }else{
            $profileEnd = 'Erro ao se conectar com o servidor. #profileEnd';
        }
        $sql = " SELECT p.*, u.* FROM publicacoes AS p JOIN usuarios AS u ON (p.id_usuario = u.id) WHERE id_usuario = $id ORDER BY data_inclusao DESC ";
        $resultado_posts = mysqli_query($link, $sql);
        if($resultado_posts){
            while($registro61 = mysqli_fetch_array($resultado_posts, MYSQLI_ASSOC)){
                    $id_public = $registro61['id_public'];
                    echo '<div id="post'.$id_public.'"><div class="bg-secondary rounded h-100 p-4">


                        <!-- User AND post -->


                        <div style="display: inline-flex;" class="align-items-center">
                            <div class="position-relative">
                                <a href="profile?id='.$registro61['id'].'"><img class="rounded-circle img-Rounded" src="img/user'.$registro61['pfp'].'"></a>
                            </div>
                            <div class="ms-3">
                                <a href="profile?id='.$registro61['id'].'"><h6 class="mb-0">'.$registro61['usuario'].': -<small style="color:#a1a1a1">'.$registro61['data_inclusao'].'</small></h6></a>'.$registro61['public'].'</div>
                        </div>
                        <div id="contents'.$id_public.'" style="display: contents;" class="align-items-center">


                            <!-- BUTTONS -->


                            <input type="text" class="far_away" id="link'.$id_public.'" name="link'.$id_public.'" 
                            value="http://localhost/thonthon/post?id='.$id_public.'">
                            <input type="text" class="far_away" id="txt'.$id_public.'" name="txt'.$id_public.'" 
                            value="'.$registro61['public'].'">';
                            if($id_usuario == $registro61['id']){
                                echo '
                                <button id="btn_delete'.$id_public.'" type="button" class="postBtns" style="display: inline;float: right;"><i class="fa-solid fa-trash-can"></i></button>';
                            }
                    echo '<button id="btn_link'.$registro61['id_public'].'" type="button" class="postBtns" style="display: inline;float: right;"><i class="fa-solid fa-share"></i></button>';
                    echo '<button id="btn_txt'.$id_public.'" type="button" class="postBtns" style="display: inline;float: right;"><i class="bi bi-files"></i></button>';

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
                            })
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
                            });;";
                            if($id_usuario == $registro61['id']){
                        
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
                echo $profileEnd;
        }else{
            echo 'Erro ao se conectar com o servidor.';
        }
    }
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <title>Thonthon - <?php echo $name; ?></title>
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

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div id="profile-mobile" class="bg-secondary rounded p-4" style="display: flex;justify-content: center;">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="bg-secondary rounded text-center h-100 p-4">
                            <h3 style="display:inline;"><?php echo $name; ?></h3>
                            <?php if($id == $id_usuario){echo '<a href="config?focus=username"><h3 style="display:inline;margin-left:10px;"><u><i class="bi bi-pencil-fill"></i></u></h3></a>';} ?>
                            <div id="pow" style="display: block; position: relative;" class="rounded p-4 anim" id="avatar">
                                <?php if($id == $id_usuario){echo '<a href="config?focus=avatar">';} ?>
                                    <img class="avatar rounded-circle anim" src="img/user<?php echo $pfp; ?>">
                                <?php if($id == $id_usuario){echo '</a>';} ?>
                                <?php if($id == $id_usuario){echo '<style>
                                    #pow:hover .middle {
                                      opacity: 1;
                                    }
                                    #pow:hover .avatar{
                                        opacity: 0.3;
                                    }
                                </style>
                                <a href="config?focus=avatar" class="cameraLink">
                                    <div class="middle">
                                        <div class="camera"><i class="bi bi-camera"></i></div>
                                    </div>
                                </a>';} ?>
                            </div>
                            <center>
                                <div style="max-width:350px">
                                    <span class="mb-4"><?php echo $bio; ?></span>
                                </div>
                            </center>
                        </div>
                    </div>
                    <div class="mb-3 d-flex text-center align-items-center" style="justify-content:center;flex-direction:column;">
                            <h4 class="mb-4">Seguindo | Seguidores</h4>
                            <h6 class="mb-4"><?php echo $seguindo; ?> | <?php echo $seguidores; ?></h6>
                            <?php 
                            if($id == $id_usuario){
                                echo '';
                            }else{
                                echo '<div id="contents'.$id.'">
                                    <button id ="btnFollow'.$id.'" type="button" class="btn btn-primary ';

                                if(isset($deuFollow) && !empty($deuFollow)){echo '';}else{echo'blu-btn';}
                                echo '">';

                                if(isset($deuFollow) && !empty($deuFollow)){echo 'Deixar de Seguir';}else{echo'Seguir';}

                                echo '</button>
                                    <script>
                                        $("#btnFollow'.$id.'").click(function(){
                                            var profileId = "'.$id.'";
                                            $.ajax({
                                                url: "lib/general.class.php",
                                                method: "post",
                                                data: { profileId: profileId },
                                                success: function(data){
                                                    $("#contents'.$id.'").val("");
                                                    $("#contents'.$id.'").html(data);
                                                },
                                                complete: function(){
                                                    window.location.reload();
                                                }
                                            });
                                        });
                                    </script></div>  ';
                                }
                                ?>

                        </div>
                    </div>    
                </div>
            <!-- Recent Sales End -->

            <!-- Message -->
            <div class="container-fluid pt-4 px-4">
                <?php puxaPosts(); ?>
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