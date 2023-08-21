<?php
    session_start();
?>
<style>
    .container1 {
      margin: 20px auto;
      max-width: 640px;
    }

    img {
      max-width: 100%;
    }

    .cropper-view-box,
    .cropper-face {
      border-radius: 50%;
    }
    .row{
      justify-content: center;
    }

    /* The css styles for `outline` do not follow `border-radius` on iOS/Safari (#979). */
    .cropper-view-box {
      outline: 0;
      box-shadow: 0 0 0 1px #39f;
    }
  </style>
    <div class="container1">
      <div class="row">
        <div class="col-lg-6" align="center">
          <label onclick="start_cropping()"><h6 class="mb-4">Escolha uma Foto de Perfil Personalizada:</h6></label>
          <div id="display_image_div">
            <img name="display_image_data" id="display_image_data" src="lib/img/dummy-image.png" alt="Picture">
          </div>
          <input type="hidden" name="cropped_image_data" id="cropped_image_data">
          <br>
          <input type="file" name="browse_image" id="browse_image" class="form-control">
          <button type="submit" style="margin-top: 10px;" class="mb-4 btn btn-primary" name="submitImg" id="crop_button">Enviar<i style="margin-left:5px;
            font-size:20px" class="bi bi-arrow-right-circle"></i>
          </button>
          <center><img src="img/load<?php if($_SESSION['tema'] == 0){echo 'Gr';} ?>.gif" style="display:none;" id="loadCrop"></center>
        </div>
        <div class="col-lg-6" align="center" style="display: none;">
          <label>Preview</label>
          <div id="cropped_image_result">
            <img style="width: 350px;" src="lib/img/dummy-image.png" />
          </div>
        </div>
      </div>
      <!--  end row -->
    </div>
    <!-- end container -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        $("body").on("change", "#browse_image", function(e) {
              var files = e.target.files;
              var done = function(url) {
                $('#display_image_div').html('');
                $("#display_image_div").html(' <img name="display_image_data" id="display_image_data" src="'+url+'" alt="Uploaded Picture"> ');
                };
                if (files && files.length > 0) {
                  file = files[0];
                  if(file.size > 2000000){
                    alert('A imagem não deve passar de 2MB.')
                    return false;
                  }
                  if (URL) {
                    done(URL.createObjectURL(file));
                  } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                      done(reader.result);
                    };
                    reader.readAsDataURL(file);
                  }
                }
                var load = document.getElementById('loadCrop');
                var image = document.getElementById('display_image_data');
                var button = document.getElementById('crop_button');
                var result = document.getElementById('cropped_image_result');
                var croppable = false;
                var cropper = new Cropper(image, {
                  aspectRatio: 1,
                  viewMode: 1,
                  ready: function() {
                    croppable = true;
                  },
                });
                button.onclick = function() {
                  button.style.display='none';
                  load.style.display='block';
                  var croppedCanvas;
                  var roundedCanvas;
                  var roundedImage;
                  if (!croppable) {
                    return;
                  }
                  // Crop
                  croppedCanvas = cropper.getCroppedCanvas();
                  // Round
                  roundedCanvas = getRoundedCanvas(croppedCanvas);
                  // Show
                  roundedImage = document.createElement('img');
                  roundedImage.src = roundedCanvas.toDataURL()
                  result.innerHTML = '';
                  result.appendChild(roundedImage);
                  var base64data = $('#cropped_image_result img').attr('src');
                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        url: "lib/usuario.class.php",
                        data: {  image: base64data , submitImg : 'submitImg' },
                        success: function(response) {
                            if (response.status == true) {
                                window.location.href="config?focus=avatar";
                            } else {
                                alert(response.msg);
                            }
                        }
                    });
                };
              });
      
            function getRoundedCanvas(sourceCanvas) {
              var canvas = document.createElement('canvas');
              var context = canvas.getContext('2d');
              var width = sourceCanvas.width;
              var height = sourceCanvas.height;
              canvas.width = width;
              canvas.height = height;
              context.imageSmoothingEnabled = true;
              context.drawImage(sourceCanvas, 0, 0, width, height);
              context.globalCompositeOperation = 'destination-in';
              context.beginPath();
              context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
              context.fill();
              return canvas;
            }
          
      
      </script>