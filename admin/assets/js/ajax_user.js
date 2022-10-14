$(document).ready(function(){
    $('#search').click(function(e){
        e.preventDefault();
        ajax(); 
    });

    function ajax()
    {
        var user = $('#search_user').val(); 

        var parameters = "user="+ user; 

        $.post("assets/ajax_admin/ajax_user.php", parameters, function(data){
            $('#resultat').html(data.resultat); 
            $('#search_user').val(""); 
        },'json'); 
    }
}); 

