<?php
include("../inc/front/init.inc.php");

//if(!utilisateur_est_admin()) // s'il l'utilisateur n'est pas admin
//{
	//header("location:../connexion.php");
	//exit(); // par sécurité, si on passe dans cette condition, cette ligne bloque l'exécution du code suivant.
//}


// SUPRESSION PRODUIT

if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    $article_a_suppr= $_GET['id_salle'];
    $suppression = $pdo->prepare('DELETE FROM salle WHERE id_salle = :id_salle');
    $suppression->bindPARAM(':id_salle', $article_a_suppr, PDO::PARAM_STR);
    $suppression->execute();
    
    //on change la valeur de GET provoquer un affichage directement.
    $_GET['action'] = 'voir';
}

//FIN SUPRESSION PRODUIT

// Le ou les fichiers joints via un formulaire seront dans la superglobale $_FILES car ceux ne sont pas des saisies classiques (donc protocole différent)
// $_FILES est un tableau array multidimensionnel



$categorie = "";
$ville = "";
$capacite = "";
$prix = "";
$periode = "";
$date_arrivee = "";
$date_depart = "";


// pour la modif uniquement
$id_salle ="";
//MODIFICATION PRODUIT
if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
$produit_a_modif = $_GET['id_salle'];
$recup_info = $pdo->prepare('SELECT * FROM salle WHERE id_salle =:id_salle');
$recup_info->bindPARAM(':id_salle', $produit_a_modif, PDO::PARAM_STR);
$recup_info->execute();
    
$produit_actuel = $recup_info->fetch(PDO::FETCH_ASSOC);
    
$categorie = $salle_actuel['categorie'];    
$ville = $salle_actuel['ville']; ;
$capacite = $salle_actuel['capacite']; ;
$prix = $salle_actuel['prix']; ;
$periode = $salle_actuel['periode']; ;
$date_arrivee = $salle_actuel['date_arrivee']; ;
$date_depart = $salle_actuel['date_depart']; ;
}
// FIN MODIFICATION PRODUIT



if( isset($_POST['categorie']) && 
	isset($_POST['ville']) && 
	isset($_POST['capacite']) && 
	isset($_POST['prix']) && 
	isset($_POST['periode']) && 
	isset($_POST['date_arrivee']) && 
	isset($_POST['date_depart']))
{
	$categorie = $_POST['categorie'];
    $ville = $_POST['ville'];
	$capacite = $_POST['capacite'];
	$prix = $_POST['prix'];
	$periode = $_POST['periode'];
	$date_arrivee = $_POST['date_arrivee'];
	$date_depart = $_POST['date_depart'];
	
	// vérification de la disponibilité de la référence
	$erreur = false;
	
	$verif_reference = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
	$verif_reference->bindParam(":categorie", $categorie, PDO::PARAM_STR);
	$verif_reference->execute();
	
	// s'il y a une ligne dans $verif_reference alors la reference existe déjà !
	if($verif_reference->rowCount() > 0 && empty($id_salle))
	{
		$erreur = true;
		$message .= '<div class="alert alert-danger" style="">Attention,<br>La référence existe déjà, veuillez en choisir une autre</div>';
	}
	
	
	// vérification s'il n'y a pas eu d'erreur sur les contrôle au dessus.
	if(!$erreur)
	{
		$photo_bdd = '';
		
		// récupération de la photo
		if(!empty($_FILES['photo']['name']))
		{
			// mise en place du src
			$photo_bdd = 'img/' . $reference . $_FILES['photo']['name'];
			
			$chemin = RACINE_SERVEUR . $photo_bdd;
			// copy() est une fonction prédéfinie permettant de copier un fichier depuis un emplacement (1er argument) vers un emplacement cible (2eme argument)
			copy($_FILES['photo']['tmp_name'], $chemin);			
		}
		
		// enregistrement en BDD du produit
        if(empty($id-salle))
           
        {
          $enregistrement = $pdo->prepare("INSERT INTO salle (categorie, ville, capacite, prix, periode, date_arrivee, date_depart) VALUES (:categorie, :ville, :capacite, :prix, :periode, :date_arrivee, :date_depart, '$photo_bdd')");  
        }else{
            $enregistrement= $pdo->prepare("UPDATE salle SET categorie = :categorie, ville = :ville, capacite = :capacite, prix = :prix, periode = :periode, date_arrivee = :date_depart, photo = '$photo_bdd', WHERE id_salle = :id_salle"); 
            $enregistrement->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);
        }
		
		$enregistrement->bindParam(":categorie", $categorie, PDO::PARAM_STR);
		$enregistrement->bindParam("ville", $ville, PDO::PARAM_STR);
		$enregistrement->bindParam(":capacite", $capacite, PDO::PARAM_STR);
		$enregistrement->bindParam(":prix", $prix, PDO::PARAM_STR);
		$enregistrement->bindParam(":periode", $periode, PDO::PARAM_STR);
		$enregistrement->bindParam(":date_arrivee", $date_arrivee, PDO::PARAM_STR);
		$enregistrement->bindParam(":date_depart", $date_depart, PDO::PARAM_STR);		
		$enregistrement->execute();
	}	
	
}
include("../inc/front/header.inc.php");
include("../inc/front/nav.inc.php");
// echo '<pre>'; print_r($_POST); echo '</pre>';
// echo '<pre>'; print_r($_FILES); echo '</pre>';
// echo '<pre>'; print_r($_SERVER); echo '</pre>';
?>
    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-th-list"></span> Gestion boutique</h1>
		<?php echo $message; // affichage des messages utilisateur  ?>
      </div>
	  
	  <div class="row">
		<div class="col-sm-12 text-center">
			<a href="?action=ajouter" class="btn btn-warning">Ajouter une salle</a>
			<a href="?action=voir" class="btn btn-primary">voir les produits</a>
			<hr>
		</div>
		
		<!-- FORMULAIRE AJOUT OU MODIFICATION PRODUIT -->
		<?php 
		if(isset($_GET['action']) && ($_GET['action'] == 'ajouter' || $_GET ['action'] == 'modification'))
		{ 
		?>	
		
		<div class="col-sm-4 col-sm-offset-4">
			<form method="post" action="" enctype="multipart/form-data">
			<!-- enctype="multipart/form-data" est obligatoire s'il y a des pièces jointes dans le formulaire -->
                
            <!-- on rejoute un champ caché (type hidden) pour avoir l'id_produit lors d'une modification--> 
            <imput type='hidden' name='id_salle' value='<?php echo $id_produit;?>'>
                
                
				<div class="form-group">				
					<label for="categorie">Categorie</label>
					<input type="text" class="form-control" id="categorie" name="categorie" placeholder="categorie..." value="<?php echo $categorie; ?>" >
				</div>
				<div class="form-group">				
					<label for="ville">Ville</label>
					<input type="text" class="form-control" id="ville" name="ville" placeholder="ville..." value="<?php echo $ville; ?>" >
				</div>
				<div class="form-group">				
					<label for="capacite">Capacite</label>
					<input type="text" class="form-control" id="capacite" name="capacite" placeholder="capacite..." value="<?php echo $capacite; ?>" >
				</div>
				<div class="form-group">
					<label for="prix">Prix</label>
					<textarea id="prix" name="prix" class="form-control" rows="3"><?php echo $prix; ?></textarea>
				</div>
				<div class="form-group">				
					<label for="periode">Periode</label>
					<input type="text" class="form-control" id="periode" name="periode" placeholder="periode..." value="<?php echo $periode; ?>" >
				</div>
				<div class="form-group">				
					<label for="date_arrivee">Date arrivee</label>
					<input type="text" class="form-control" id="date_arrivee" name="date_arrivee" placeholder="date_arrivee..." value="<?php echo $date_arrivee; ?>" >
				</div>						
				<div class="form-group">				
					<label for="date_depart">Date depart</label>
					<input type="text" class="form-control" id="date_depart" name="date_depart" placeholder="date_depart..." value="<?php echo $date_depart; ?>" >
				</div>
				<div class="form-group">				
					<label for="photo">Photo</label>
					<input type="file" class="form-control" id="photo" name="photo" >
				</div>
				<hr>
				<button type="submit" class="btn btn-info col-sm-12"><span class="glyphicon glyphicon-ok" name="ajouter"></span> Ajouter</button>
				
			</form>
			
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>
		
		<?php
		} 
		?>
		<!-- / FIN FORMULAIRE AJOUT PRODUIT -->
		
		<!-- TABLEAU AFFICHAGE PRODUIT -->
		<?php 
		if(isset($_GET['action']) && $_GET['action'] == 'voir')
		{ 
			$les_produits = $pdo->query("SELECT * FROM produit ORDER BY categorie");
			
			echo '<div class="col-sm-12">';			
			echo '<table class="table table-bordered">';
			
			echo '<tr>';
			echo '<th>id_salle</th>';
            echo '<th>Catégorie</th>';
			echo '<th>Ville</th>';
			echo '<th>Capacite</th>';
			echo '<th>Prix</th>';
			echo '<th>Periode</th>';
			echo '<th>Date_arrivee</th>';
			echo '<th>Date_depart</th>';
            echo '<th>Modif</th>';
			echo '<th>Suppr</th>';
			echo '</tr>';
			
			while($salle = $les_salles->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
				
				echo '<td>' . $id_salle['id_salle'] . '</td>';
				echo '<td>' . $categorie['categorie'] . '</td>';
				echo '<td>' . $capacite['capacite'] . '</td>';
				echo '<td>' . $prix['prix'] . '</td>';			
				echo '<td>' . $periode['periode'] . '</td>';
				echo '<td>' . $date_arrivee['date_arrivee'] . '</td>';
				echo '<td>' . $salle['public'] . '</td>';
				echo '<td><img src="' . URL . $salle['photo'] . '" alt="image produit" class="img-responsive" width="100"></td>';
				echo '<td>' . $date_depart['date_depart'] . '</td>';
				
				
				echo '<td><a href="?action=modification&id_produit=' . $salle['id_salle'] . '" class="btn btn-warning"><span class="glyphicon glyphicon-refresh"></span></a></td>';
				
				echo '<td><a href="?action=suppression&id_produit=' . $salle['id_salle'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes vous sur ?\'));" ><span class="glyphicon glyphicon-trash"></span></a></td>';				
				
				echo '</tr>';
			}
			
			echo '</table>';			
			echo '</div>';
	
		} 
		?>
		<!-- / FIN TABLEAU AFFICHAGE PRODUIT -->
	  </div>

    </div><!-- /.container -->


<?php
include("../inc/footer.inc.php");  




 