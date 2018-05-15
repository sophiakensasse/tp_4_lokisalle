<?php
include("inc/front/init.inc.php");


// requete de récupération de toutes les catégories
$liste_categorie = $pdo->query("SELECT DISTINCT categorie FROM salle ORDER BY categorie");

// requete de récupération de tous les produits en BDD
if(isset($_GET['categorie']))
{
	$liste_produit = $pdo->prepare("SELECT * FROM salle WHERE categorie = :categorie ORDER BY titre");
	$liste_produit->bindParam(":categorie", $_GET['categorie'], PDO::PARAM_STR);
	$liste_produit->execute();
}
else {
	$liste_produit = $pdo->query("SELECT * FROM salle ORDER BY titre");
}



include("inc/front/header.inc.php");
include("inc/front/nav.inc.php");
// echo '<pre>'; print_r($_SERVER); echo '</pre>';
?>
    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-home"></span> Accueil</h1>
		<?php echo $message; // affichage des messages utilisateur  ?>
      </div>
	  
	  <div class="row">
		<div class="col-sm-2">
			<?php 
				// afficher toutes les catégories de la BDD dans une liste ul li (sous forme de lien <a href>)
								
				echo '<ul class="list-group">';
				while($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC))
				{
					echo '<li class="list-group-item"><a href="?categorie=' . $categorie['categorie'] . '">' . $categorie['categorie'] . '</a></li>';
				}
				echo '</ul>';
			?>
		</div>
		<div class="col-sm-10">
			<div class="row">
			<?php 
				
				// boucle while pour traiter notre objet $liste_produit et les afficher
			$compteur = 0;
			
while($salle_en_cours = $liste_categorie->fetch(PDO::FETCH_ASSOC))
{
	
	if($compteur%4 == 0 && $compteur != 0)
	{
		echo '</div><div class="row">';
	}
	$compteur++;
	
	// var_dump($produit_en_cours); echo '<hr>';
	echo '<div class="col-sm-3">';
	echo '  <div class="panel panel-primary">
				<div class="panel-heading"><img src="img/logo.png" class="img-responsive" alt="logo"></div>
				<div class="panel-body">
				<h3>' . $salle_en_cours['titre'] . '</h3>
				<hr>
				<img src="' . URL . $salle_en_cours['photo'] . '" class="img-responsive" alt="image produit">
				<hr>
				<a href="fiche_produit.php?id_produit=' . $salle_en_cours['id_produit'] . '" class="btn btn-success col-sm-12">Voir la fiche</a>
				</div>
			</div>';
	
	echo '</div>';
}	
			?>
			</div>
		</div>
	  </div>

    </div><!-- /.container -->


<?php
include("inc/front/footer.inc.php");  



