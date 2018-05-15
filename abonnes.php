<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////

$id_abonne = "";
$prenom = "";

//Recupération de la table Livre dans la BDD
$recuperation_abonne = $pdo->query("SELECT * FROM abonne");

//AJOUTER PRENOM////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["prenom"]) && isset($_POST["ajouter_prenom"]))
{
    $prenom = $_POST["prenom"];
    
    $erreur = false;
    
    //Vérifier que les champs ne soient pas vide
    if(empty($prenom))
    {
        $erreur = true;
        $message .= "<div class='alert alert-danger'>Merci de remplir tous les champs.</div>";
    }
    ///////////////////////////////////////////////

    //Notre requete d'ajout de livre////////////////////////////

    if($erreur == false)
    {
        $ajouter_prenom = $pdo->prepare("INSERT INTO abonne (prenom) VALUES (:prenom)");
        $ajouter_prenom->bindParam(":prenom", $titre, PDO::PARAM_STR);

        $ajouter_prenom->execute();
    }
    
///////////////////////////////////////////////////////////////////////////////
}//////////////// FIN D'AJOUT PRENOM///////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////


//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");
?>

<div class="container">

    

    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-home"></span> Abonnés</h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>

</div><!-- /.container -->

<!--BOUTONS------------------------------------------------------------->
<div class="row">
    
		<div class="col-sm-12 text-center">
			<a href="?action=ajouter_abonne" class="btn btn-warning">Ajouter un abonné</a>
			<a href="?action=voir_abonne" class="btn btn-primary">Voir les abonnés</a>
			<hr>
		</div>
</div>
<!--FIN DE BOUTONS----------------------------------------------------->

    
    
<!--TABLEAU------------------------------------------------------------>
<?php if(isset($_GET['action']) && $_GET['action'] == 'voir_abonne') // VOIR TABLEAU
{?>
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <?php
                    // Récupération du nombre de colonne, pour afficher les noms dans des <th>
                    $nb_col = $recuperation_abonne->columnCount();
                    ?>

                    <!--Création des <th> avec le nom des colonnes-->
                    <tr>
                        <?php
                        for($i = 0; $i < $nb_col; $i++)
                        {
                            $colonne_en_cours = $recuperation_abonne->getColumnMeta($i);
                            echo '<th style="padding:5px;">' . $colonne_en_cours['name'] . '</th>';

                        }
                        ?>
                        <th style="padding:5px;"> Modification </th>
                        <th style="padding:5px;"> Suppression </th>
                    </tr>

                    <!--Création des <td> avec les valeurs correspondant aux colonnes-->

                    <?php

                    while($ligne_en_cours = $recuperation_abonne->fetch(PDO::FETCH_ASSOC))
                    {
                        echo "<tr>";

                        $compteur = 1;

                        foreach($ligne_en_cours AS $valeur)
                        {
                            echo "<td style='padding:5px;'>" . $valeur . "</td>";

                            $compteur++;

                            if($compteur > $nb_col)
                            {
                                echo "<td><a href='?action=modification'><span class='glyphicon glyphicon-pencil'></span><a></td>";
                                echo "<td><a href='?action=suppression'><span class='glyphicon glyphicon-trash'></span><a></td>";

                                $compteur = 1;

                            }//fin de if

                        }//fin de foreach
                        echo "</tr>";

                    }//fin de while

                    ?>
                </table>
            </div>
        </div><!--FIN DE DIV CLASS ROW -->
    </div><!-- FIN DE CONTAINER -->
<?php } // FIN DE IF VOIR TABLEAU?>
<!--FIN DE TABLEAU----------------------------------------------------->

<!--FORMULAIRE AJOUTER LIVRE------------------------------------------->

<?php if(isset($_GET['action']) && $_GET['action'] == 'ajouter_abonne') // FORMULAIRE AJOUTER EMPRUNT
{?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <form method="post" action="">
                
                <!--AUTEUR------------------------------------------------------>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom?>">
                </div>
                <!--FIN AUTEUR-------------------------------------------------->

                <!--BOUTON D'ENVOI---------------------------------------------->
                <button type="submit" class="btn btn-success col-sm-12" name="ajouter_abonne" > Ajouter l'abonné</button>

            </form>

        </div><!--FIN DE COL-->

    </div><!--FIN DE DIV ROW-->
</div> <!--FIN DE CONTAINER-->

<?php } // FIN DE IF FORMULAIRE AJOUTER EMPRUNT?>

<!--FIN FORMULAIRE AJOUTER LIVRE------------------------------------->


<?php
include("inc/footer.inc.php");