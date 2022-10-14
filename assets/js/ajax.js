$(document).ready(function(){
// all post affiner votre recherche
    $("#category, #subcat, #country, #regions, #prix_min, #prix_max").change(function(){

        var category = $('#category').val();
        var subcat = $('#subcat').val();
        var country = $('#country').val(); 
        var regions = $('#regions').val();
        var pmin = $('#prix_min').val();
        var pmax = $('#prix_max').val();
       
    
            var parametres = 'category='+category+'&subcat='+subcat+'&country='+country+'&regions='+regions+'&prix_min='+pmin+'&prix_max='+pmax;
    
    
            $.post("assets/ajax/ajax_allpost.php", parametres, function(data){
    
                // console.log(data); // debug
                $('#resultat_global').html(data.resultat);
        
            },'json');
    
        
        
    });


//country + region    
    $("#country").change(function(){
        var id = $("#country").val();
        $.ajax({
            url:'assets/ajax/post_region.php',
            method: 'post',
            data: 'id='+id
        }).done(function(regions){
            // console.log(regions);
            regions = JSON.parse(regions);
            $('#regions').empty();
            regions.forEach(function(region){
                $('#regions').append('<option value="'+region.id_region+'">'+region.name_region+'</option>')
            });
        });
    });

//like
    $(function(){
        $(".favoris").click(function(e){
            e.preventDefault();
            var $form = $(this).closest('form');
            var idannonce = $form.find('input[name="idannonce"]').val();
            var iduser = $form.find('input[name="iduser"]').val();
            var parameters = 'idannonce='+idannonce+'&iduser='+iduser;
            $.ajax({
                url:'assets/ajax/ajax_like.php',
                method : 'post',
                data: parameters,
                dataType: 'JSON',
                success: function(data){
                    retour = $('.resultat'+idannonce).html(data.resultat);
                   return retour;
                }  
            });
        });
    });

    $(function(){
        $(".removefavori").click(function(e){
            e.preventDefault();
            var $form = $(this).closest('form');
            var idSupr = $form.find('input[name="idSupr"]').val();
            var iduser = $form.find('input[name="iduser"]').val();
            var idannonce = $form.find('input[name="idannonce"]').val();
            var parameters = "idSupr="+idSupr+"&idannonce="+idannonce+'&iduser='+iduser;
            $.ajax({
                url:'assets/ajax/delete_like.php',
                method : 'post',
                data: parameters,
                dataType: 'JSON',
                success: function(data){
                   retour = $('.resultat'+idannonce).html(data.resultat);
                   return retour;
                }  
            });
        });
    });

    $(function(){
        $(".favoris_fiche").click(function(e){
            e.preventDefault();
            var $form = $(this).closest('form');
            var idannonce = $form.find('input[name="idannonce"]').val();
            var iduser = $form.find('input[name="iduser"]').val();
            var parameters = 'idannonce='+idannonce+'&iduser='+iduser;
            $.ajax({
                url:'../assets/ajax/like_fiche.php',
                method : 'post',
                data: parameters,
                dataType: 'JSON',
                success: function(data){
                   retour = $('.resultat'+idannonce).html(data.resultat);
                   return retour;
                }  
            });
        });
    });

    $(function(){
        $(".removefavori_fiche").click(function(e){
            e.preventDefault();
            var $form = $(this).closest('form');
            var idSupr = $form.find('input[name="idSupr"]').val();
            var iduser = $form.find('input[name="iduser"]').val();
            var idannonce = $form.find('input[name="idannonce"]').val();
            var parameters = "idSupr="+idSupr+"&idannonce="+idannonce+'&iduser='+iduser;
            $.ajax({
                url:'../assets/ajax/delete_like_fiche.php',
                method : 'post',
                data: parameters,
                dataType: 'JSON',
                success: function(data){
                   retour = $('.resultat'+idannonce).html(data.resultat);
                   return retour;
                }  
            });
        });
    });

    $(function(){
        $(".news").click(function(e){
            e.preventDefault();
            var $form = $(this).closest('form');
            var email_news = $form.find('input[name="email_news"]').val();
            var ipUser = $form.find('input[name="ipUser"]').val();
            var parameters = "email_news="+email_news+"&ipUser="+ipUser;
            $.ajax({
                url:'assets/ajax/news.php',
                method : 'post',
                data: parameters,
                dataType: 'JSON',
                success: function(data){
                   retour = $('#resultat_news').html(data.resultat);
                   return retour;
                }  
            });
            $form.trigger('reset');
        });
    });

    $(function(){
        $(".news_fiche").click(function(e){
            e.preventDefault();
            var $form = $(this).closest('form');
            var email_news = $form.find('input[name="email_news"]').val();
            var ipUser = $form.find('input[name="ipUser"]').val();
            var parameters = "email_news="+email_news+"&ipUser="+ipUser;
            $.ajax({
                url:'../assets/ajax/news.php',
                method : 'post',
                data: parameters,
                dataType: 'JSON',
                success: function(data){
                   retour = $('#resultat_news').html(data.resultat);
                   return retour;
                }  
            });
            $form.trigger('reset');
        });
    });
    
    
});