<?php
include("inc/front/init.inc.php");
//Verification si l'utilisateur est connecté
//if(!utilisateur_est_connecte())
//{
    //si l'utilisateur n'est pas connecté, on le redirige sur connexion.php
    //header('location:connexion.php');
   // exit();
//}//



include("inc/front/header.inc.php");
include("inc/front/nav.inc.php");
?>
    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-user"></span> Profil</h1>
		<?php echo $message; // affichage des messages utilisateur  ?>
      </div>
	  
	  <div class="row">
		<div class="col-sm-8">
			<div class="list-group">
			    <p class="list-group-item active">Bonjour <b><?php echo ucfirst($_SESSION['membre']['pseudo']); ?></b></p>
                
			    <p class="list-group-item"><b>Nom:</b> <?php echo ucfirst($_SESSION['membre']['nom']); ?></p>
			    <p class="list-group-item"><b>Prénom:</b> <?php echo ucfirst($_SESSION['membre']['prenom']); ?></p>
			    <p class="list-group-item"><b>Email:</b> <?php echo ucfirst($_SESSION['membre']['email']); ?></p>
			    <p class="list-group-item"><b>Sexe:</b> <?php echo ucfirst($_SESSION['membre']['sexe']); ?></p>
			    <p class="list-group-item"><b>Adresse de livraison:</b> <?php echo $_SESSION['membre']['adresse'] . ' ' . $_SESSION['membre']['code_postal'] . ' ' . ucfirst($_SESSION['membre']['ville']); ?></p>
			</div>
            <hr>
            <?php
            // function declare dans function.inc.php
            if(utilisateur_est_admin())
            {
                echo '<h2> Vous êtes adminisatrateur </h2>';
            }else{
                echo '<h2> Vous êtes membres </h2>';
            }
            ?>
		</div>
		<div class="col-sm-4">
			<img src="img/Profil1.png" class="img-thumbnail" alt="image de profil">
		</div>
	  </div>
	  

    </div><!-- /.container -->


<?php
include("inc/front/footer.inc.php");  

