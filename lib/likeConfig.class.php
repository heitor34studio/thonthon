<?php
	session_start();
	if(!isset($_SESSION['id_usuario'])){
		header('Location: ../login');
	}
	require_once('db.class.php');


	//FUNÇÃO ATUALIZA LIKE
	function atualizaLike(){
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$id_public = addslashes($_POST['postIdLike']);
		$sql = " SELECT * FROM likes WHERE id_de_quem_curtiu = $id_usuario AND id_public = $id_public ";
		$resultado_id_0 = mysqli_query($link, $sql);
		if($resultado_id_0){
			$registro_0 = mysqli_fetch_array($resultado_id_0, MYSQLI_ASSOC);
			if(isset($registro_0['id_public'])){
				$sql = " DELETE FROM likes WHERE id_de_quem_curtiu = $id_usuario AND id_public = $id_public ";
				$resultado_id = mysqli_query($link, $sql);
				if($resultado_id){
					echo '<button id="btn_love'.$id_public.'" type="button" class="postBtns" style="display: inline;margin-left: 30px;"><i class="bi bi-heart"></i>
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
	                    echo '</h6>';
	                    echo "<script>
                        					var postId = ".$id_public.";
                        					$('#btn_love".$id_public."').click(function(){
                                            $.ajax({
                                                url: 'lib/likeConfig.class.php',
                                                method: 'post',
                                                data: { postIdLike: postId },
                                                success: function(data){
                                                    $('#loveDiv".$id_public."').html(data);
                                                }
                                            });
                                        });
                                    </script>";
				}else{
					echo 'Erro ao conectar com o servidor.';
				}
			}else{
				$sql = " INSERT INTO likes(id_de_quem_curtiu, id_public)values('$id_usuario', '$id_public') ";
				$resultado_id = mysqli_query($link, $sql);
				if($resultado_id){
					echo '<button id="btn_love'.$id_public.'" type="button" class="postBtns" style="display: inline;margin-left: 30px;"><i class="bi bi-heart-fill"></i>
	                    </button>
	                    <h6 style="display: inline;">';
	                    $blob6 =  " SELECT COUNT(*) AS qntd_likes FROM likes WHERE id_public = $id_public ";
	                    $juuj6 = mysqli_query($link, $blob6);
	                    if($juuj6){
						$juuj_6 = mysqli_fetch_array($juuj6, MYSQLI_ASSOC);
						echo $juuj_6['qntd_likes'];
	                    }else{
	                        echo 'Erro ao conectar com o servidor.';
	                    }
	                    echo '</h6>';
	                    echo "<script>
                        					var postId = ".$id_public.";
                        					$('#btn_love".$id_public."').click(function(){
                                            $.ajax({
                                                url: 'lib/likeConfig.class.php',
                                                method: 'post',
                                                data: { postIdLike: postId },
                                                success: function(data){
                                                    $('#loveDiv".$id_public."').html(data);
                                                }
                                            });
                                        });
                                    </script>";
				}else{
					echo 'Erro ao conectar com o servidor.';
				}
			}
		}else{
			echo 'Erro ao se conectar com o servidor.';
		}
	}


	//FUNÇÃO DELETA POST
	function deletePost(){
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$id_public = addslashes($_POST['postIdTrash']);
			$objDB = new db();
			$link = $objDB->conecta_mysql();
			$sql = " DELETE FROM `publicacoes` WHERE `publicacoes`.`id_public` = '$id_public' 
			AND id_usuario = '$id_usuario' ";
			$resultado = mysqli_query($link, $sql);
			if($resultado){
				echo '<h3 style="margin: auto;color:#3fff00">Post apagado com sucesso!</h3>';
				}else{
				echo '<h3 style="margin: auto;color:#ff0000">Erro ao se conectar com servidor.</h3>';
			}
	}


	//FUNÇÃO EXIBIR CONTEÚDO CERTO NA PÁGINA DAS CONFIGURAÇÕES
	function exibeConfig(){
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$tipo = addslashes($_POST['btnId']);
		if($tipo == 'btnProfile'){
			$objDB = new db();
			$link = $objDB->conecta_mysql();
			$sql = " SELECT newName FROM `usuarios` WHERE id = $id_usuario ";
			$resultado = mysqli_query($link, $sql);
			if($resultado){
				$registro = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
				if($registro['newName'] == 'NAO'){
					$mensagem = 'Atenção! Você pode alterar seu nome de usuário uma única vez.';
					$disabled = '';
				}else{
					$mensagem = 'Você já alterou seu nome de usuário.';
					$disabled = 'disabled';
				}
				echo '<div class="bg-secondary text-center rounded p-4">
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Nome de Usuário</h6>
                                <font style="color:#ff0000;" id="msgName">'.$mensagem.'</font>
                            </div>
                            <input maxlength="20" type="text" class="form-control" '.$disabled.' placeholder="Insira o novo nome de usuário:" name="username" id="username" value="'.$_SESSION["userName"].'">
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Biografia</h6>
                            </div>
                            <textarea class="form-control" maxlength="200" placeholder="Diga mais um pouco sobre você" name="bio" id="bio" style="height: 150px;">'.$_SESSION["bio"].'</textarea>
                        </div>
                        <div class="bg-secondary">
                            <button type="submit" class="btn btn-primary py-3 b-4" id="change">Salvar!<i style="margin-left:5px;
                                 font-size:20px"class="bi bi-arrow-right-circle"></i>
                            </button>
                            <script type="text/javascript">';
						echo "$(document).ready(function(){
                                    $('#change').click(function(){
                                        $.ajax({
                                            url: 'lib/likeConfig.class.php',
                                            method: 'post',
                                            data: { username: $('#username').val(), bio: $('#bio').val() },
                                            success: function(data){
                                                $('#final').html(data);
                                            }
                                        });
                                    });
                                });
                            </script>";
            		echo '</div>
                        <div class="bg-secondary" id="final">
                        </div>
                   	    </div>';
			}else{
				echo 'Erro ao se conectar com servidor.';
			}
		}elseif($tipo == 'btnAcc'){
			echo '<div class="bg-secondary rounded p-4">
                        <h6 class="mb-4">Alterar E-mail</h6>
                        <div class="input-group mb-3">
                            <input aria-describedby="basic-addon2" maxlength="50" type="email" class="form-control" placeholder="Insira o novo email:" name="username" id="newEmail" value="'.$_SESSION['userEmail'].'">
                            <button type="button" class="btn btn-primary blu-btn"id="emailChange">Ir!<i style="margin-left:5px;
                                font-size:20px"class="bi bi-arrow-right-circle"></i></button>
                            <center><img src="img/load'; if($_SESSION['tema'] == 0){echo 'Gr';} echo '.gif" style="display:none;" id="load2"></center>
                            <script type="text/javascript">
                            
                                $(document).ready(function(){';
            echo "
            							$('#emailChange').click(function(){
                                        var type = 'changeEmail';
                                        $.ajax({
                                            url: 'phpmailer/index.php',
                                            method: 'post',
                                            data: { email: $('#newEmail').val(), type: type },
                                            success: function(data){
                                                $('#emailFinal').html(data);
                                            },
                                            beforeSend: function(){
                            					$('#load2').css({display:'block'});
                            					$('#emailChange').remove();
                        					},
                        					complete: function(){
                            					$('#load2').css({display:'none'});
                        					}
                                        });
                                    });
                                });
                            </script>
                        </div>";
            echo '
                        <div class="bg-secondary text-center" id="emailFinal">
                        </div>
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0">Alterar sua Senha</h6>
                            </div>
                            <input maxlength="60" type="password" class="form-control" placeholder="Insira a nova senha:" name="senha1" id="senha1">
                            </br>
                            <input maxlength="60" type="password" class="form-control" placeholder="Repita a nova senha:" name="senha2" id="senha2">
                        </div>
                        <div class="bg-secondary text-center">
                            <button type="submit" class="btn btn-primary py-3 b-4" id="password">Alterar!<i style="margin-left:5px;
                                 font-size:20px"class="bi bi-arrow-right-circle"></i>
                            </button>
                            <script type="text/javascript">
                                $(document).ready(function(){';
            echo "
                                    $('#password').click(function(){
                                        $.ajax({
                                            url: 'lib/likeConfig.class.php',
                                            method: 'post',
                                            data: { senha1: $('#senha1').val(), senha2: $('#senha2').val() },
                                            success: function(data){
                                                $('#senhaFinal').html(data);
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>";
            echo '
                        <div class="bg-secondary text-center" id="senhaFinal">
                        </div>';


                        echo '<div class="bg-secondary rounded p-4">
                        <h6 class="mb-4">Se Quiser Deletar Sua Conta Digite "DELETAR" Abaixo:</h6>
                        <div class="input-group mb-3">
                            <input aria-describedby="basic-addon2" maxlength="7" type="text" class="form-control" placeholder="Digite DELETAR" name="delete" id="delete">
                            <button type="button" class="btn btn-primary red-btn"id="btnDelete">Ir!<i style="margin-left:5px;
                                font-size:20px"class="bi bi-arrow-right-circle"></i></button>
                            <center><img src="img/load'; if($_SESSION['tema'] == 0){echo 'Gr';} echo '.gif" style="display:none;" id="load3"></center>
                            <script type="text/javascript">
                            
                                $(document).ready(function(){';
            echo "
                                        $('#btnDelete').click(function(){
                                        var delete123 = 'delete';
                                        $.ajax({
                                            url: 'lib/likeConfig.class.php',
                                            method: 'post',
                                            data: { msg: $('#delete').val(), delete: delete123 },
                                            success: function(data){
                                                $('#delFinal').html(data);
                                            },
                                            beforeSend: function(){
                                                $('#load3').css({display:'block'});
                                                $('#btnChange').remove();
                                            },
                                            complete: function(){
                                                $('#load3').css({display:'none'});
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>";
            echo '
                        <div class="bg-secondary text-center" id="delFinal">
                        </div>';


            echo '        </div>';
		}elseif($tipo == 'btnPfp'){
            $half = " ";
            $poha = array("1.jpg","2.jpg","3.jpg","4.jpg","5.jpg");
            $cu = $_SESSION['pfp'];
            if(!in_array($cu,$poha)){$half = 'style="border-radius: 50% !important;"';}
			echo '<div style="padding-top: 50px;padding-bottom: 45px;" class="bg-secondary text-center rounded">
                        <script>
                            $(document).ready(function(){
                                var url = "lib/crop.php";
                                $.ajax({
                                    url: url,
                                    success: function(data){
                                        $("#div_crop").html(data);
                                    }
                                });
                            });
                        </script>
                        <div id="div_crop">
                        </div>
                        <h6 class="mb-4">Escolha uma Foto de Perfil Padrão:</h6>
                        <div style="display: block;" class="h-100 rounded p-4 anim" id="bigPhoto">
                            <center><img class="bigImg rounded anim chosen-one" src="img/user'.$_SESSION['pfp'].'" '.$half.'></center>
                        </div>
                        <div style="display: inline;" class="h-100 rounded p-4">
                            <a type="button" id="User1" class="btnTheme">
                                <img id="imgUser1" class="imgsPfp rounded anim ';
                									if($_SESSION['pfp'] == '1.jpg'){
                										echo ' chosen-one';
                									}
            echo '" src="img/user1.jpg">
                            </a>
                        </div>
                        <div style="display: inline;" class="h-100 rounded p-4">
                            <a type="button" id="User2" class="btnTheme">
                                <img id="imgUser2" class="imgsPfp rounded anim ';
                									if($_SESSION['pfp'] == '2.jpg'){
                										echo ' chosen-one';
                									}
            echo '" src="img/user2.jpg">
                            </a>
                        </div>
                        <div style="display: inline;" class="h-100 rounded p-4">
                            <a type="button" id="User3" class="btnTheme">
                                <img id="imgUser3" class="imgsPfp rounded anim ';
                									if($_SESSION['pfp'] == '3.jpg'){
                										echo ' chosen-one';
                									}
            echo '" src="img/user3.jpg">
                            </a>
                        </div>
                        <div style="display: inline;" class="h-100 rounded p-4">
                            <a type="button" id="User4" class="btnTheme">
                                <img id="imgUser4" class="imgsPfp rounded anim ';
                									if($_SESSION['pfp'] == '4.jpg'){
                										echo ' chosen-one';
                									}
            echo '" src="img/user4.jpg">
                            </a>
                        </div>
                        <div style="display: inline;" class="h-100 rounded p-4">
                            <a type="button" id="User5" class="btnTheme">
                                <img id="imgUser5" class="imgsPfp rounded anim ';
                									if($_SESSION['pfp'] == '5.jpg'){
                										echo ' chosen-one';
                									}
            echo '" src="img/user5.jpg">
                            </a>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $(".btnTheme").click(function(){
                                    var pfp = this.id;
                                    $(".imgsPfp").removeClass("chosen-one");
                                    $("#img" + pfp).addClass("chosen-one");
                                    $.ajax({
                                        url: "lib/likeConfig.class.php",
                                        method: "post",
                                        data: { changingPfp: pfp },
                                        success: function(data){
                                            $("#bigPhoto").html(data);
                                        }
                                    });
                                });    
                            });
                        </script>
                        <div class="bg-secondary text-center p-4">
                            <button type="button" class="btn btn-primary py-3 b-4" id="subPfp">Escolher<i style="margin-left:5px;
                                 font-size:20px" class="bi bi-arrow-right-circle"></i>
                            </button>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $("#subPfp").click(function(){
                                        var elementPfp = document.getElementsByClassName("imgsPfp");
                                        for(let i = 0; i < 5; i++){
                                            if(elementPfp[i].classList.contains("chosen-one")){
                                                var idid = elementPfp[i].id;
                                                $.ajax({
                                                    url: "lib/likeConfig.class.php",
                                                    method: "post",
                                                    data: { pfpId: idid },
                                                    success: function(data){
                                                        $("#pfpFinal").html(data);
                                                    }
                                                });
                                            }   
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <div class="bg-secondary text-center" id="pfpFinal">
                        </div>
                    </div>';
		}else{
			echo '<div style="padding-top: 55px;padding-bottom: 45px;" class="bg-secondary text-center rounded">
                        <h6 class="mb-4">Escolha a Cor do Layout:</h6>
                        <div style="display: inline;" class="h-100 rounded p-4">
                            <a type="button" id="Dark" class="btnTheme">
                                <img id="imgDark" class="imgs rounded anim ';
                									if($_SESSION['tema'] == 1){
                										echo 'chosen-one';
                									}
            echo '" src="img/black.png">
                            </a>
                        </div>
                        <div style="display: inline;" class="h-100 rounded p-4">
                            <a type="button" id="Light" class="btnTheme">
                                <img id="imgLight" class="imgs rounded anim ';
                                					if($_SESSION['tema'] == 0){
                										echo 'chosen-one';
                									}
            echo '" src="img/white.png">
                            </a>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $(".btnTheme").click(function(){
                                    var thisId = this.id;
                                    $(".imgs").removeClass("chosen-one");
                                    $("#img" + thisId).addClass("chosen-one");
                                });    
                            });
                        </script>
                        <div class="bg-secondary text-center p-4">
                            <button type="button" class="btn btn-primary py-3 b-4" id="subTheme">Escolher<i style="margin-left:5px;
                                 font-size:20px"class="bi bi-arrow-right-circle"></i>
                            </button>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $("#subTheme").click(function(){
                                        var img = document.getElementById("imgDark");
                                        if(img.classList.contains("chosen-one")){
                                            var tem = 1;
                                        }else{
                                            var tem = 0;
                                        }
                                        $.ajax({
                                            url: "lib/likeConfig.class.php",
                                            method: "post",
                                            data: { imgOpt: tem },
                                            success: function(data){
                                                $("#temaFinal").html(data);
                                            }
                                        });
                                    });
                                });
                            </script>
                        </div>
                        <div class="bg-secondary text-center" id="temaFinal">
                        </div>
                    </div>';
		}
	}


	//FUNÇÃO ALTERA O PERFIL, USERNAME E BIO
	function alteraProfile(){
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$usuario = addslashes($_SESSION['userName']);
		$nome = addslashes($_POST['username']);
		$bio = addslashes($_POST['bio']);
		$nome_size = strlen($usuario);
		$bio_size = strlen($bio);
		if($bio_size > 200 || $nome_size > 20 || $nome_size < 4){
			echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro! Sua biografia ou nome de usuário ultrapassam o limite de caracteres permitidos.</span>';
			echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
			die();
		}
		if($usuario == $nome){
			$sql = " UPDATE `usuarios` SET `biografia` = '$bio' WHERE `usuarios`.`id` = $id_usuario ";
			$resultado5 = mysqli_query($link, $sql);
			if($resultado5){
				$_SESSION['bio'] = $bio;
				echo '<span id="successR" class="btn btn-outline-success m-2">Successo! Sua bio foi atualizada com sucesso!</span>';
				echo '<script id="scrip">$("#successR").click(function(){$("#successR").remove();$("#scrip").remove();}) </script>';
			}else{
				echo 'Erro ao se conectar com o servidor.';
			}
		}else{
			$sql = " SELECT newName FROM `usuarios` WHERE id = $id_usuario ";
			$resultWOW = mysqli_query($link, $sql);
			if($resultWOW){
				$registroWOW = mysqli_fetch_array($resultWOW, MYSQLI_ASSOC);
				if($registroWOW['newName'] == 'NAO'){
					$userExistente = 0;
					$sql = " SELECT COUNT(*) AS jaexiste FROM usuarios  WHERE usuario = '$nome' AND usuario != '$usuario' ";
					$resultado = mysqli_query($link, $sql);
					if($resultado){
						$result = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
						$userExistente = $result['jaexiste'];
						if($userExistente == 0){
							$sql = " UPDATE `usuarios` SET `usuario` = '$nome', `biografia` = '$bio', `newName` = 'YES' WHERE `usuarios`.`id` = $id_usuario ";
							$resultado2 = mysqli_query($link, $sql);
							if($resultado2){
								$_SESSION['userName'] = $nome;
								$_SESSION['bio'] = $bio;
								echo '<span id="successR" class="btn btn-outline-success m-2">Successo! Seu perfil foi atualizado com sucesso!</span>';
								echo '<script id="scrip">
								$(document).ready(function(){
									$("#msgName").html("Você já alterou seu nome de usuário.");
									$("#username").prop("disabled", true);
									})
								$("#successR").click(function(){$("#successR").remove();$("#scrip").remove();}) </script>';
							}else{
								echo 'Erro ao se conectar com o servidor.';
							}
						}else{
							echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro! Este nome de usuário já está sendo utilizado.</span>';
							echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
							die();
						}
					}else{
						echo 'Erro ao se conectar com o servidor.';
					}
				}else{
					die();
				}
			}else{
				echo 'Erro ao se conectar com o servidor.';
			}
		}
	}


	//FUNÇÃO ALTERA SENHAS, VE SE ESTÃO IGUAIS E CRIPTOGRAFA
	function alteraSenha(){
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$senha1 = addslashes($_POST['senha1']);
		$senha2 = addslashes($_POST['senha2']);
		$tamanho = strlen($senha1);
		$tamanho2 = strlen($senha2);
		if($tamanho < 8 || $tamanho2 < 8 ){
			echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro. As senhas devem conter no mínimo 8 caracteres.</span>';
			echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
			die();
		}
		if($tamanho > 60 || $tamanho > 60){
			echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro. As senhas ultrapassam o limite de caracteres.</span>';
			echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
			die();
		}
		if($senha1 == $senha2){
			$senhaCrypt = password_hash($senha1, PASSWORD_BCRYPT);
			$objDB = new db();
			$link = $objDB->conecta_mysql();
			$sql = " UPDATE `usuarios` SET `senha` = '$senhaCrypt' WHERE `usuarios`.`id` = $id_usuario ";
			if(mysqli_query($link, $sql)){
				echo '<span id="errorS" type="button" class="btn btn-outline-success m-2">Sucesso. Sua senha foi alterada.</span>';
				echo '<script id="scrip">$(document).ready(function(){$("#senha1").val("");$("#senha2").val("");});$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
				die();
			}else{
				echo 'Erro ao se conectar com o servidor';
			}
		}else{
			echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro. As senhas não se coincidem.</span>';
			echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
			die();
		}
	}


	//FUNÇÃO ALTERA TEMA
	function alteraTema(){
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$opcao = addslashes($_POST['imgOpt']);
		if(strlen($opcao) > 1){
			die();
		}
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		$sql = " UPDATE `usuarios` SET `tema` = '$opcao' WHERE `usuarios`.`id` = $id_usuario ";
		if(mysqli_query($link, $sql)){
			$_SESSION['tema'] = $opcao;
			echo '<script>window.location.reload();</script>';
		}else{
			echo 'Erro ao se conectar com o servidor.';
		}
	}


	//FUNÇÃO ESCOLHE FOTO
	function escolheFoto(){
		$opcao = addslashes($_POST['changingPfp']);
		if(strlen($opcao) > 5){
			die();
		}
		$opop = strtolower($opcao);
		echo '<center><img class="bigImg rounded anim chosen-one" src="img/'.$opop.'.jpg"></center>';
	}


	//FUNÇÃO ALTERA FOTO DE PERFIL
	function alteraPfp(){
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$opcao = addslashes($_POST['pfpId']);
		if(strlen($opcao) > 8){
			die();
		}
		$num = substr($opcao, 7);
        $num = $num.'.jpg';
		$objDB = new db();
		$link = $objDB->conecta_mysql();
        $sql = " SELECT * FROM `usuarios` WHERE `usuarios`.`id` = $id_usuario ";
        $query = mysqli_query($link, $sql);
        if($query){
            $registro_query = mysqli_fetch_array($query, MYSQLI_ASSOC);
            $default_pfps = array("1.jpg","2.jpg","3.jpg","4.jpg","5.jpg");
            $pfp_do_sujeito = $registro_query['pfp'];
            if(in_array($pfp_do_sujeito, $default_pfps)){
                $sql = " UPDATE `usuarios` SET `pfp` = '$num' WHERE `usuarios`.`id` = $id_usuario ";
                if(mysqli_query($link, $sql)){
                    $_SESSION['pfp'] = $num;
                    echo '<script>window.location.reload();</script>';
                }else{
                    echo 'Erro ao se conectar com o servidor.';
                }
            }else{
                $pfp_do_sujeito = 'user'.$pfp_do_sujeito;
                $filename = '../img/'.$pfp_do_sujeito;
                if(file_exists($filename)){
                    unlink($filename);
                    $sql = " UPDATE `usuarios` SET `pfp` = '$num' WHERE `usuarios`.`id` = $id_usuario ";
                    if(mysqli_query($link, $sql)){
                        $_SESSION['pfp'] = $num;
                        echo '<script>window.location.reload();</script>';
                    }else{
                        echo 'Erro ao se conectar com o servidor.';
                    }
                }else{
                    echo "Ocorreu um erro ao remover sua imagem personalizada antiga. Contate o Dev.";
                }
            }
        }else{
            echo 'Erro ao se conectar com o servidor.';
        }
	}

    //FUNÇÃO APAGA CONTA
    function deletaAccount(){
        $id_usuario = addslashes($_SESSION['id_usuario']);
        $delete = addslashes($_POST['delete']);
        $msg = addslashes($_POST['msg']);
        if(strlen($delete)>6){
            echo 'Erro';
            die();
        }
        if($delete !== 'delete'){
            echo 'Erro2';
            die();
        }
        if(strlen($msg)>7){
            echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro. A palavra inserida ultrapassa o limite de caracteres.</span>';
            echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
        }else{
            if($msg == 'DELETAR'){
                $objDB = new db();
                $link = $objDB->conecta_mysql();
                $sql = "  DELETE FROM `usuarios_seguidores` WHERE `usuarios_seguidores`.`id_usuario` = $id_usuario ";
                if(mysqli_query($link, $sql)){
                    $sql = " DELETE FROM `usuarios_seguidores` WHERE `usuarios_seguidores`.`seguindo_id_usuario` = $id_usuario ";
                    if(mysqli_query($link, $sql)){;
                        $sql = " DELETE FROM `usuarios` WHERE `usuarios`.`id` = $id_usuario ";
                        if(mysqli_query($link, $sql)){
                            $sql = " DELETE FROM `publicacoes` WHERE `publicacoes`.`id_usuario` = $id_usuario ";
                            if(mysqli_query($link, $sql)){
                                $sql = " DELETE FROM `likes` WHERE `likes`.`id_de_quem_curtiu` = $id_usuario ";
                                if(mysqli_query($link, $sql)){
                                    $sql = "  DELETE FROM `config` WHERE `config`.`id_user` = $id_usuario ";
                                    if(mysqli_query($link, $sql)){
                                        echo '<script>window.location.href="lib/usuario.class.php"</script>';
                                    }else{
                                        echo 'Erro ao se conectar com o servidor.';
                                    }
                                }else{
                                    echo 'Erro ao se conectar com o servidor.';
                                }
                            }else{
                                echo 'Erro ao se conectar com o servidor.';
                            }
                        }else{
                            echo 'Erro ao se conectar com o servidor.';
                        }
                    }else{
                        echo 'Erro ao se conectar com o servidor.';
                    }
                }else{
                    echo 'Erro ao se conectar com o servidor.';
                }
            }else{
                echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro. A palavra inserida está errada. (Certifique-se que escreveu em maiúsculas)</span>';
                echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
                die();
            }
        }
    }


	//ESCOLHENDO A FUNÇÃO CERTA
	if(isset($_POST['postIdLike'])){
		atualizaLike();
	}elseif(isset($_POST['postIdTrash'])){
		deletePost();
	}elseif(isset($_POST['btnId'])){
		exibeConfig();
	}elseif(isset($_POST['bio'])){
		alteraProfile();
	}elseif(isset($_POST['senha1'])){
		alteraSenha();
	}elseif(isset($_POST['imgOpt'])){
		alteraTema();
	}elseif(isset($_POST['changingPfp'])){
		escolheFoto();
	}elseif(isset($_POST['pfpId'])){
		alteraPfp();
	}elseif(isset($_POST['delete'])){
        deletaAccount();
    }
	
?>