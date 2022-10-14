<?php foreach (recupererFlash() as $messages): ?>


<div id="toats" class="notif alert-<?= $messages['type'];?>" onload="killToats()">
    <div class="toats_headers">
        <a class="toats_die">
            <i class="icon ion-md-close"></i>
        </a>
            <h5><i class="fas fa-exclamation-circle"></i> Notification :</h5>
    </div>
    <div class="toats_core">
        <p>
        <?= $messages['message'];?>
        </p>
    </div>          
</div>

<script>
    const toats = document.querySelector('.notif');
    const close_toats = document.querySelector('.toats_die');

    setTimeout(function(){ document.querySelector(".notif").classList.add("hiden");}, 3000 );

    close_toats.addEventListener('click', ()=>{
    toats.classList.add('hiden');
    });
</script>

<?php endforeach ?> 