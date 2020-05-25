<?php
require_login();
global $terminoController, $isManager;
$alphabetArr = range('A', 'Z');
$role = 'admin';

if(!$isManager) {
	$role = 'guest';
}
?>

<input type="hidden" class="role" value="<?php echo $role; ?>"/>

<div class="header-container-terminologia">
	<div class="title-container-terminologia">
		<div class="title-terminologia"><a role="<?php echo $role; ?>" class="title-link" type="button">TERMINOLOGÍA</a></div>
		<div class="subtitle-terminologia">Bienvenido a nuestro glosario</div>
	</div>
</div>
<div class="body-basic-container">
    <div class="alphabet-container">
        <ul class="pagination">
					<?php foreach ($alphabetArr as $a) {?>
              <li><a class="page-link-terminologia" href="#"><?php echo $a; ?></a></li>
					<?php } ?>
        </ul>
    </div>
    <div class="search-container">
        <div class="search-input-container input-group my-4 col-6 mx-auto">
            <input style="width: 75% !important; margin-top: 9% !important;
    margin-left: 8%; border: 0;" class="form-control search-input" type="search" placeholder="¿Qué estás buscando?" id="example-search-input">
            <span style="margin-top: 9%;height: 25%;background-color: white;margin-right:8%;" class="input-group-append">
                <button class="btn btn-outline-primary search-button search-button-term" type="button">
                    <i style="color: #184A7D !important" class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </div>
</div>
<div class="search-results-container hidden">
    <div class="search-results-container-title big-title"></div>
    <div class="search-results-subtitle"></div>
</div>
<div class="letter-results-container hidden">
    <div class="big-letter-results-container"></div>
    <div class="list-letter-results-container">
        <table class="list-letter-results-table">
        </table>
    </div>
</div>
<div class="termino-detail-container hidden">
    <div class="termino-detail-container-box">
        <div class="div-maintenance-termino-detail">
            <div class="termino-detail-container-name"></div>
                <?php
                $styleFloat="";
                $hidden = "hidden";
                if($role == 'admin') {
                    $styleFloat="float:left;";
                    $hidden = "";
                }
                ?>
            <hr style="margin-top:0;">
            <div style="<?php echo $styleFloat; ?>"  class="termino-detail-container-origin"></div>
            <div class="termino-detail-container-action <?php echo $hidden;?>"></div>
            <div class="termino-detail-container-description"></div>
            <div class="termino-detail-container-image">
                <a href="#" class="pop">
                    <img src="" id="img-detail" width="170px" height="150px" style="border-radius: 5%;">
                </a>
            </div>
        </div>
        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- The Close Button -->
            <span class="close-zoom-image">&times;</span>

            <!-- Modal Content (The Image) -->
            <img class="modal-content" id="img01">

            <!-- Modal Caption (Image Text) -->
            <div id="caption"></div>
        </div>
    </div>
    <div class="body-maintenance-content-button-cancel">
        <a class="terminos-sugeridos-button-cancel" href="#">Regresar</a>
    </div>
</div>
<style type="text/css">
    /* Style the Image Used to Trigger the Modal */
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {opacity: 0.7;}

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0, 0, 0, 0.57); /* Black w/ opacity */
    }

    /* Modal Content (Image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    /* Caption of Modal Image (Image Text) - Same Width as the Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation - Zoom in the Modal */
    .modal-content, #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @keyframes zoom {
        from {transform:scale(0)}
        to {transform:scale(1)}
    }

    /* The Close Button */
    .close-zoom-image {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close-zoom-image:hover,
    .close-zoom-image:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
        .modal-content {
            width: 100%;
        }
    }
</style>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("img-detail");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.onclick = function(){
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    }

    modal.onclick = function(ev){
        if(ev.target != this) return;
        modal.style.display = "none";
    };

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-zoom-image")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
</script>