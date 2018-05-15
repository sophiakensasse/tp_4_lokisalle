<?php
include("inc/front/init.inc.php");
//Deconnexionde l'utilisateur
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    // unset($_SESSION)['membre']); // supression de l'indice membre dans session immédiat!
    
    session_destroy(); // cette fonction est reconnu mais sera exécuté qu'à la fin de l'éxécution du script.
    
}


//if(utilisateur_est_connecte())
{
    //header('location:profil.php'); // avec la session destroy(); cette ligne est exécutée, nous sommmes redirigé vers profil, et c'est à ce moment là que la session sera détruite. 
    
    //exit();// securité permet de bloquer l'éxecution du code
   
}//


if(isset($_POST['pseudo']) &&  isset($_POST['mdp']))
{
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];


// requet en BDD selon le pseudo
$selection_membre = $pdo->prepare('SELECT * FROM membre WHERE pseudo = :pseudo');
$selection_membre->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
$selection_membre->execute();


// on vérifie s'il y a au moins une ligne ( s'il y a une ligne alors le pseudo existe!)

if($selection_membre->rowCount() > 0)
{
    $info_membre = $selection_membre->fetch(PDO::FETCH_ASSOC);
    // Verification de mot de asse avec la fonctionpredefinie password_verify() qui fonctionne avec password_hash() ( voir sur inscription!)
    if(password_verify($mdp,$info_membre['mdp']))
    {
        // si on rentre dans cette condition, le pseudo et le mot de passe sont corrects!
        // on enregistre les informations dans la session 
        $_SESSION['membre'] = array ();
        $_SESSION['membre']['id_membre'] =$info_membre['id_membre'];
        $_SESSION['membre']['pseudo'] =$info_membre['pseudo']; 
        $_SESSION['membre']['mdp'] =$info_membre['mdp']; 
        $_SESSION['membre']['nom'] =$info_membre['nom']; 
        $_SESSION['membre']['prenom'] =$info_membre['prenom']; 
        $_SESSION['membre']['email'] =$info_membre['email
        ']; 
        $_SESSION['membre']['civilite'] =$info_membre['civilite']; 
        $_SESSION['membre']['statut'] =$info_membre['statut']; 
        $_SESSION['membre']['date_enregistrement'] =$info_membre['date_enregistrement']; 
      
        //header('location:profil.php');                                               
                                                       
    }else { 
    $message .= '<div class="alert alert-danger" >Attention,<br>Pseudo indisponible</div>';
    }  
}else { 
    $message .= '<div class="alert alert-danger" >Attention,<br>Pseudo indisponible</div>';        
      }
}
include("inc/front/header.inc.php");
include("inc/front/nav.inc.php");
echo'<pre>';print_r($_SESSION); echo '</pre>'; 
   
?>
    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-play"></span> Connexion</h1>
         <?php echo $message;//afficher des messages utilisateur ?>
          
      </div>
<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<form method="post" action="">
				<div class="form-group">				
					<label for="pseudo">Pseudo</label>
					<input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo..." value="" >
				</div>
				<div class="form-group">				
					<label for="mdp">Mot de passe</label>
					<input value="" type="text" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe...">
				</div>
				<hr>
				<button type="submit" class="btn btn-success col-sm-12"><span class="glyphicon glyphicon-ok" name="inscription"></span> Connexion</button>
			</form>
		</div>
	  </div>

    </div><!-- /.container -->


<?php
include("inc/font/footer.inc.php");  




 