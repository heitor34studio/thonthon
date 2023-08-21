<?php
	session_start();
	if(!isset($_SESSION['id_usuario'])){
		header('Location: ../login');
	}
	require_once('db.class.php');


	//FUNÇÃO ADICIONA POST
	function addPost(){
		$texto_publi = addslashes($_POST['texto']);
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		$sql = " SELECT COUNT(*) AS qntd_posts FROM publicacoes WHERE id_usuario = $id_usuario ";
		$jooj = mysqli_query($link,$sql);
		if($jooj){
			$result = mysqli_fetch_array($jooj, MYSQLI_ASSOC);
			$qntd_posts = $result['qntd_posts'];
		}
		if($texto_publi == ''){
			echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro! Está faltando 
			algo no seu post.</span>';
			echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
			die();	
		}elseif(strlen($texto_publi)>140){
			echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro! Seu post ultrapassa 140 caracteres.</span>';
			echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
			die();
		}elseif($qntd_posts >= 5){
			echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro! Você já atingiu o máximo de posts testes.</span>';
			echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();})</script>';
			die();
		}
		$sql = " INSERT INTO publicacoes(id_usuario, public)values('$id_usuario', '$texto_publi')";
		if(mysqli_query($link, $sql)){
		    $sql = " SELECT * FROM publicacoes WHERE id_usuario = '$id_usuario' AND public = '$texto_publi' ";
			$checagem = mysqli_query($link, $sql);
			if($checagem){
				$yup = mysqli_fetch_array($checagem, MYSQLI_ASSOC);
				$post_link = 'post?id='.$yup['id_public'];
			}else{
				$post_link = 'profile?id='.$id_usuario;
			}
			$qntd_posts = $qntd_posts + 1;
			echo '<a href="#" id="successR" class="btn btn-outline-success m-2">Successo! Seu post de número '.$qntd_posts.'/5 foi publicado.</a>';
			echo '<script id="scrip">$("#successR").click(function(){$("#successR").remove();window.location.href="'.$post_link.'";$("#scrip").remove();}) </script>';
		}else{
			echo '<span id="errorS" type="button" class="btn btn-outline-danger m-2">Erro! Seu post não foi publicado.</span>';
			echo '<script id="scrip">$("#errorS").click(function(){$("#errorS").remove();$("#scrip").remove();}) </script>';
		}
	}


	//FUNÇÃO PESQUISAR
	function doSearch(){
		$search = addslashes($_POST['search']);
		$option = addslashes($_POST['option']);
		if($search == ''){
			die();
		};
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		if($option == 'option1'){
			$sql = " SELECT u.*, us.* FROM usuarios AS u LEFT JOIN usuarios_seguidores AS us ON (us.id_usuario = $id_usuario AND u.id = us.seguindo_id_usuario) WHERE u.usuario like '%$search%' AND u.ATIVADO <> 'NAO' ";
			$resultado_id = mysqli_query($link, $sql);
			if($resultado_id){
				while($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){


					//EXIBINDO USUÁRIOS e BOTÃO SEGUIR ou DEIXAR DE SEGUIR


					echo '<div class="bg-secondary rounded h-100 p-4">
	                    <div style="display: inline-flex;" class="align-items-center">
	                        <div class="position-relative">
	                            <a href="profile?id='.$registro['id'].'"><img class="rounded-circle img-Rounded" src="img/user'.$registro['pfp'].'"></a>
	                        </div>
	                        <div class="ms-3">
	                            <a href="profile?id='.$registro['id'].'"><h6 class="mb-0">'.$registro['usuario'].'</h6></a>';
	                            if($registro['id']==1){echo 'Admin';}else{echo'Membro Comum';}
	                echo '</div>
	                    </div>';

	                if($registro['id'] !== $id_usuario){
	                	echo '
	                    <div id="contents'.$registro['id'].'" style="display: contents;" class="align-items-center">
	                        <button id ="btnFollow'.$registro['id'].'" type="button" class="btn btn-primary';

	                         if(isset($registro['id_usuario_seguidor']) && !empty($registro['id_usuario_seguidor'])){echo '';}else{echo' blu-btn';}

	                echo '" style="display: inline;float: right;">'; 

	                        if(isset($registro['id_usuario_seguidor']) && !empty($registro['id_usuario_seguidor'])){echo 'Deixar de Seguir';}else{echo'Seguir';}


	                echo "</button>
	                	<script>$('#btnFollow".$registro['id']."').click(function(){
	                		var profileId = ".$registro['id'].";
	                		$.ajax({
                        url: 'lib/general.class.php',
                        method: 'post',
                        data: { profileId: profileId },
                        success: function(data){
                        	$('#contents".$registro['id']."').val('');
                            $('#contents".$registro['id']."').html(data);
                        }
                    	});
                    	});</script>
	                    </div>";
	                }
	                	echo "</div>
	                	<br>";
				}
			}else{
				echo 'Erro na consulta no banco de dados';
			}
		}else{

			//EXIBINDO POSTS

			$sql = " SELECT p.*, u.* FROM publicacoes AS p JOIN usuarios AS u ON (p.id_usuario = u.id) WHERE public like '%$search%' ";
			$resultado_id = mysqli_query($link, $sql);
			if($resultado_id){
				while($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){
					$id_public = $registro['id_public'];
					echo '<div id="post'.$id_public.'"><div class="bg-secondary rounded h-100 p-4">


						<!-- User AND post -->


	                    <div style="display: inline-flex;" class="align-items-center">
	                        <div class="position-relative">
	                            <a href="profile?id='.$registro['id'].'"><img class="rounded-circle img-Rounded" src="img/user'.$registro['pfp'].'"></a>
	                        </div>
	                        <div class="ms-3">
	                            <a href="profile?id='.$registro['id'].'"><h6 class="mb-0">'.$registro['usuario'].': -<small style="color:#a1a1a1">'.$registro['data_inclusao'].'</small></h6></a>'.$registro['public'].'</div>
	                    </div>
	                    <div id="contents'.$id_public.'" style="display: contents;" class="align-items-center">


	                    	<!-- BUTTONS -->


	                    	<input type="text" class="far_away" id="link'.$id_public.'" name="link'.$id_public.'" 
					  		value="http://localhost/thonthon/post?id='.$id_public.'">
					  		<input type="text" class="far_away" id="txt'.$id_public.'" name="txt'.$id_public.'" 
                            value="'.$registro['public'].'">';

					  		if($id_usuario == $registro['id']){
	                        	echo '
	                        	<button id="btn_delete'.$id_public.'" type="button" class="postBtns" style="display: inline;float: right;"><i class="fa-solid fa-trash-can"></i></button>';
	                        }
	                echo '<button id="btn_link'.$registro['id_public'].'" type="button" class="postBtns" style="display: inline;float: right;"><i class="fa-solid fa-share"></i></button>';
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
							if($id_usuario == $registro['id']){
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
							echo "
						});
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
				echo 'Erro na consulta no banco de dados';
			}

		}
	}


	//FUNÇÃO SEGUIR/DEIXAR DE SEGUIR
	function followUnfollow(){
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$FLLW_UNF_id = addslashes($_POST['profileId']);
		if($id_usuario == '' || $FLLW_UNF_id == ''){
			die();
		}
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		$sql = " SELECT * FROM usuarios_seguidores AS answer WHERE id_usuario = $id_usuario AND seguindo_id_usuario = $FLLW_UNF_id ";
		$resultado_id = mysqli_query($link, $sql);
		if($resultado_id){
			$registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC);
			if(isset($registro['id_usuario'])){
				$sql = " DELETE FROM usuarios_seguidores WHERE id_usuario = $id_usuario AND seguindo_id_usuario = $FLLW_UNF_id ";
				$resultado_id2 = mysqli_query($link, $sql);
				if($resultado_id2){

					//EXIBINDO BOTÃO SEGUIR

					echo '<button id="btnFollow'.$FLLW_UNF_id.'" type="button" class="btn btn-primary blu-btn" style="display: inline;float: right;">Seguir</button>';
					echo "<script>$('#btnFollow".$FLLW_UNF_id."').click(function(){
	                		var profileId = ".$FLLW_UNF_id.";
	                		$.ajax({
		                        url: 'lib/general.class.php',
		                        method: 'post',
		                        data: { profileId: profileId },
		                        success: function(data){
		                        	$('#contents".$FLLW_UNF_id."').val('');
		                            $('#contents".$FLLW_UNF_id."').html(data);
		                        }
	                    	});
	                    	});</script>
		                    </div>
		                	</div>
		                	<br>";
				}else{
					echo 'Erro ao Deixar de Seguir. Favor contactar o Dev.';
				}
			}else{

				//EXIBINDO BOTÃO DEIXAR DE SEGUIR

				$sql = " INSERT INTO usuarios_seguidores(id_usuario, seguindo_id_usuario)values($id_usuario, $FLLW_UNF_id) ";
				$resultado_id2 = mysqli_query($link, $sql);
				if($resultado_id2){
					echo '<button id="btnFollow'.$FLLW_UNF_id.'" type="button" class="btn btn-primary" style="display: inline;float: right;">Deixar de Seguir</button>';
					echo "<script>$('#btnFollow".$FLLW_UNF_id."').click(function(){
	                		var profileId = ".$FLLW_UNF_id.";
	                		$.ajax({
		                        url: 'lib/general.class.php',
		                        method: 'post',
		                        data: { profileId: profileId },
		                        success: function(data){
		                        	$('#contents".$FLLW_UNF_id."').val('');
		                            $('#contents".$FLLW_UNF_id."').html(data);
		                        }
	                    	});
	                    	});</script>
		                    </div>
		                	</div>
		                	<br>";
				}else{
					echo 'Erro ao Seguir. Favor contactar o Dev.';
				}
			}
		}else{
			echo 'Erro ao conectar com o banco de dados!';
		}
	}


	//FUNÇÃO CHAMA POSTS PRA HOME
	function puxaPosts(){
		$id_usuario = addslashes($_SESSION['id_usuario']);
		$objDB = new db();
		$link = $objDB->conecta_mysql();
		$sql = " SELECT COUNT(*) AS 'oi' FROM publicacoes AS p JOIN usuarios AS u ON (p.id_usuario = u.id) WHERE id_usuario = $id_usuario OR id_usuario IN (select seguindo_id_usuario from usuarios_seguidores where id_usuario = $id_usuario) ORDER BY data_inclusao DESC ";
		$contador = mysqli_query($link, $sql);
		if($contador){
			$QNTD = mysqli_fetch_array($contador, MYSQLI_ASSOC);
			if($QNTD['oi'] > 0){
				$homeTxt = 'É isso! Siga mais pessoas para ver mais posts.';
			}else{
				$homeTxt = 'Siga pessoas ou poste algo para ver os posts aqui!.';
			}
		}else{
			$homeTxt = 'Erro ao se conectar com o servidor. #homeTxt';
		}
		$sql = " SELECT p.*, u.* FROM publicacoes AS p JOIN usuarios AS u ON (p.id_usuario = u.id) WHERE id_usuario = $id_usuario OR id_usuario IN (select seguindo_id_usuario from usuarios_seguidores where id_usuario = $id_usuario) ORDER BY data_inclusao DESC ";
		$resultado_id = mysqli_query($link, $sql);
		if($resultado_id){
			while($registro = mysqli_fetch_array($resultado_id, MYSQLI_ASSOC)){
					$id_public = $registro['id_public'];
					echo '<div id="post'.$id_public.'"><div class="bg-secondary rounded h-100 p-4">


						<!-- User AND post -->


	                    <div style="display: inline-flex;" class="align-items-center">
	                        <div class="position-relative">
	                            <a href="profile?id='.$registro['id'].'"><img class="rounded-circle img-Rounded" src="img/user'.$registro['pfp'].'"></a>
	                        </div>
	                        <div class="ms-3">
	                            <a href="profile?id='.$registro['id'].'"><h6 class="mb-0">'.$registro['usuario'].': -<small style="color:#a1a1a1">'.$registro['data_inclusao'].'</small></h6></a>'.$registro['public'].'</div>
	                    </div>
	                    <div id="contents'.$id_public.'" style="display: contents;" class="align-items-center">


	                    	<!-- BUTTONS -->


	                    	<input type="text" class="far_away" id="link'.$id_public.'" name="link'.$id_public.'" 
					  		value="http://localhost/thonthon/post?id='.$id_public.'">
					  		<input type="text" class="far_away" id="txt'.$id_public.'" name="txt'.$id_public.'" 
                            value="'.$registro['public'].'">';
					  		if($id_usuario == $registro['id']){
	                        	echo '
	                        	<button id="btn_delete'.$id_public.'" type="button" class="postBtns" style="display: inline;float: right;"><i class="fa-solid fa-trash-can"></i></button>';
	                        }
	                echo '<button id="btn_link'.$registro['id_public'].'" type="button" class="postBtns" style="display: inline;float: right;"><i class="fa-solid fa-share"></i></button>';
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

							if($id_usuario == $registro['id']){
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
							echo "
						});
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
				echo '<div class="text-center bg-secondary rounded h-100 p-4">
				<h3 class="mb-4">'.$homeTxt.'</h3>
				<div style="display: block;" class="h-100 rounded p-4 anim" id="bigPhoto">
                            <center><img class="bigImg rounded anim chosen-one" src="img/home'.$_SESSION['tema'].'.jpg"></center>
                        </div>
                        </div>';
		}else{
			echo 'Erro ao se conectar com o servidor.';
		}
	}


	//ESCOLHENDO A FUNÇÃO CERTA
	if(isset($_POST['texto'])){
		addPost();
	}elseif(isset($_POST['search'])){
		doSearch();
	}elseif(isset($_POST['profileId'])){
		followUnfollow();
	}elseif(isset($_POST['home'])){
		puxaPosts();
	}
?>