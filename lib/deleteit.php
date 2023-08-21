<?php session_start();
    if(!isset($_SESSION['id_usuario'])){
        header('Location: ../login');
    }
    require_once('db.class.php');
    $objDB = new db();
        $link = $objDB->conecta_mysql();
        $id_post = addslashes($_POST['postIdTrash']);
        $sql = " SELECT * FROM publicacoes WHERE id_public = $id_post ";
        $jooj = mysqli_query($link,$sql);
        if($jooj){
            $registro = mysqli_fetch_array($jooj, MYSQLI_ASSOC);
            $post = $registro['public'];
        }else{
            echo 'Erro ao se conectar com o servidor.';
        }
         ?>

            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4" id="divMoldal">
                        <div style="text-align: center;" class="bg-secondary rounded p-4 mx-3 rgbShadow">
                            <button type="button" class="btn" style="margin-bottom: 10px;" id="closeModal"><i style="font-size:20px;" class="bi bi-x-octagon"></i></button>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $('#closeModal').click(function(){
                                        $('#divMoldal').css('animation', 'fadeOut 0.3s linear');
                                        setTimeout(function(){
                                        $('.customDiv').html('');
                                        $('.customDiv').css('display','none');
                                        }, 300);
                                    });
                                });
                            </script>
                            <div class="d-flex align-items-center justify-content-between mb-3" id="title">
                                <h3 style="margin: auto">Deseja mesmo apagar essa publicação?</h3>
                            </div>
                            <div class="form-floating mb-3">
                                <p class="text-center mb-0">
                                    <?php echo '"'.$post.'"'; ?>
                                </p>
                            </div>

                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4" id="reallyDel<?php echo $id_post; ?>">Apagar<i style="margin-left:5px;
                             font-size:20px"class="bi bi-arrow-right-circle" ></i></button>
                             <script type="text/javascript">
                                $(document).ready(function(){   
                                    var postIdTrash<?php echo $id_post; ?> = <?php echo $id_post; ?>;
                                    $('#reallyDel<?php echo $id_post; ?>').click(function(){
                                        $.ajax({
                                            url: 'lib/likeConfig.class.php',
                                            data: { postIdTrash: postIdTrash<?php echo $id_post; ?> },
                                            method: 'post',
                                            success: function(data){
                                                $('#title').val('');
                                                $('#title').html(data);
                                                $('#reallyDel<?php echo $id_post; ?>').remove();
                                                $('#post<?php echo $id_post; ?>').remove();
                                            }
                                        });
                                    });
                                });
                             </script>
                        </div>
                    </div>