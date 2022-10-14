<?php
require_once __DIR__ . '/../assets/config/bootstrap_admin.php';
require __DIR__ . '/assets/functions/user_functions.php';

$q =htmlspecialchars($_POST['user']);

    $q_array = explode(' ',$q);

    $resultat = $pdo->query('SELECT * FROM membre  WHERE ((nom LIKE "%'.$q_array[0].'%")  AND (prenom LIKE "%'.$q_array[1].'%")) OR ((nom LIKE "%'.$q_array[1].'%")  AND (prenom LIKE "%'.$q_array[0].'%")) ORDER BY id_membre ASC');

    
        $response .= '<table class="table table-bordered text-center">';
        $response .= '<thead class="thead-dark">
                        <th scope="col">#id</th>
                        <th scope="col">Email</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Confirmation</th>
                        <th scope="col">Update</th>
                        <th scope="col">Delete</th>
                    </thead>';
        $response .= '<tbody>';
        while($Membre = $resultat->fetch()){
            $response .= ' <tr scope="row" class="table_tr">';
            $response .= '<td scope="row">'.$Membre['id_membre'].'</td>';
            $response .= '<td>'.$Membre['email'].'</td>';
            $response .= '<td>'.$Membre['nom'].'</td>';
            $response .= '<td>'.$Membre['prenom'].'</td>';
            $response .= '<td>';
                        if ($Membre['confirmation'] == 0){
            $response .= '<p class="btn btn-primary">User</p>';
                        }else{
            $response .= '<p class="btn btn-dark">Admin</p>';
                        }
            $response .= '</td>';
            $response .= '<td>';
                        if ($Membre['confirmation'] == 0){
            $response .= '<p class="btn btn-danger">Non</p>';
                        }else{
            $response .= '<p class="btn btn-success">Oui</p>';
                        }
            $response .= '</td>';
            $response .= '<td>';
            $response .= ' <a href="user.php?id='.$Membre['id_membre'].'" class="btn btn-info" data-toggle="modal" data-target="#'.$Membre['name'].'"> <i class="fas fa-edit"></i> Update </a>';
    
            //modal
    
            $response .= '<div class="modal fade" id="'.$Membre['name'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">';
            $response .= '<div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">';
            $response .= ' <h5 class="modal-title" >Modification Membre | #'.$Membre['id_membre'].'</h5>'; 
            $response .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>';  
            $response .= '</div>';
            $response .= '<form action="user.php?id='.$Membre['id_membre'].'" method="post" class="form-inline">';
            $response .= '<div class="container">
                            <div class="row">
    
                            <div class="col form-group form-control-lg">';
            $response .= '<label class="my-1 mr-2" for="confirmation">Confirmation Actuelle :';
                        if ($Membre['confirmation'] == 0){
            $response .= '<p class="btn btn-danger">Non</p>';
                            }else{
            $response .= '<p class="btn btn-success">Oui</p>';
                            }
            $response .= '</label>';
            $response .= '<select class="custom-select" name="confirmation">';
            $response .= '<option> Modifier </option>';
            $response .= '<option value="'. 1 .'">Oui</option>';
            $response .= '<option value="'. 2 .'">Non</option>';
            $response .= '</select>';
            $response .= '</div>';
            $response .= '</div>';
            $response .= '</div>';
            $response .= '</div>';
            $response .= '<div class="modal-footer">';
            $response .= ' <input type="submit" class="btn btn-primary" name="update" value="Valider" >';
            $response .= '</div>';
            $response .= ' </form>';
            $response .= '</div>';
            $response .= '</div>';
            $response .= '</div>';
            $response .= '</td>'; // fin update
            $response .= '<td>';
            $response .= '<a href="user.php?id='.$Membre['id_membre'].'" class="btn btn-danger" data-toggle="modal" data-target="#'.$Membre['name'].'>sup"><i class="fas fa-trash-alt"></i> Delete</a>';
            $response .= '<div class="modal fade" id="'.$Membre['name'].'sup" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">';
            $response .= '<div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">';
            $response .= '<h5 class="modal-title">Supprimer Membre | #'.$Membre['id_membre'].'</h5>';
            $response .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>';
            $response .= '</div>';
            $response .= '<div class="modal-body">';
            $response .= ' <form action="user.php?id='.$Membre['id_membre'].'" method="post">';
            $response .= '<p class="mb-2">Etes vous sur de vouloir supprimer le membre #'.$Membre['id_membre'].' ?</p>';
            $response .= '<div class="confirm_delete" id="confirm_delete">';
            $response .= '<input type="checkbox" class="delete_check mr-3" name="delete_check"/><label for="delete_check" class="delete_label">Je confirme la suppression</label>';
            $response .= '<input type="hidden" name="idSupr" value="'.$Membre['id_membre'].'">';
            $response .= ' </div>';
            $response .= ' </div>';
            $response .= ' <div class="modal-footer">';
            $response .= '  <input type="submit" class="btn btn-danger" name="delete_membre" value="Supprimer" >';
            $response .= ' </div>';
            $response .= ' </form>';
            $response .= ' </div>';
            $response .= ' </div>';
            $response .= ' </div>';
            $response .= ' </td>';
            
        }
        $response .= ' </tbody>';
        $response .= ' </table>';
        

            $valeur_retour['resultat']=$response;
            echo json_encode($valeur_retour);
?>
