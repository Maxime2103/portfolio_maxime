<?php 

    //me connecte à la base de donnée 
    //crée notre variable $pdo 
     include("Dbase.php");
    //crée notre propre fonction style var_dump()
    //reçoit la variable à afficher en argument

    //tout en haut du fichier, on traite le formulaire 
    //seulement s'il est soumis

    //on récupère les données du formulaire dans nos variables
    //à nous

    //si on a des données dans $_POST, 
    //c'est que le form a été soumis
    if(!empty($_POST)){
        //par défaut, on dit que le formulaire est entièrement valide
        //si on trouve ne serait-ce qu'une seule erreur, on 
        //passera cette variable à false
        $formIsValid = true;
        
        // A MODIFIER !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $nom_entreprise = $_POST['nom_entreprise'];
        $adresse = $_POST['adresse'];
        $email = $_POST['email'];
        $numero = $_POST['numero'];
        $comment = $_POST['comment'];

        //tableau qui stocke nos éventuels messages d'erreur
        // $errors = [];

        //si le lastname est vide...
        if(empty($nom) ){
            //on note qu'on a trouvé une erreur ! 
            $formIsValid = false;
            $errors[] = "Veuillez renseigner votre nom de famille !";
        }
        //mb_strlen calcule la longueur d'une chaîne
        elseif(mb_strlen($nom) <= 1){
            $formIsValid = false;
            $errors[] = "Votre nom de famille est court, très court. Veuillez le rallonger !";
        }
        elseif(mb_strlen($nom) > 30){
            $formIsValid = false;
            $errors[] = "Votre nom de famille est trop long !";
        }

        //exactement pareil pour le prénom
        //si le prenom est vide...
        if(empty($prenom) ){
            //on note qu'on a trouvé une erreur ! 
            $formIsValid = false;
            $errors[] = "Veuillez renseigner votre prénom !";
        }
        //mb_strlen calcule la longueur d'une chaîne
        elseif(mb_strlen($prenom) <= 1){
            $formIsValid = false;
            $errors[] = "Votre prénom est court, très court. Veuillez le rallonger !";
        }
        elseif(mb_strlen($prenom) > 30){
            $formIsValid = false;
            $errors[] = "Votre prénom est trop long !";
        }

        //validation de l'email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $formIsValid = false;
            $errors[] = "Votre email n'est pas valide !";
        }

        if(empty($nom_entreprise) ){
            //on note qu'on a trouvé une erreur ! 
            $formIsValid = false;
            $errors[] = "Veuillez renseigner le nom de votre entreprise";
        }
        //mb_strlen calcule la longueur d'une chaîne
        elseif(mb_strlen($nom_entreprise) <= 1){
            $formIsValid = false;
            $errors[] = "Le nom est trop court";
        }
        elseif(mb_strlen($nom_entreprise) > 30){
            $formIsValid = false;
            $errors[] = "Le nom est trop long !";
        }

        
        if(mb_strlen($nom_entreprise) <= 1){
            $formIsValid = false;
            $errors[] = "Adresse trop courte";
        }
        elseif(mb_strlen($nom_entreprise) > 120){
            $formIsValid = false;
            $errors[] = "Adresse trop longue";
        }


        //validation du numero
        // if($numero < 10){
        //     $formIsValid = false;
        //     $errors[] = "Numéro de téléphone trop court.";
        // }
        elseif($numero > 10){
            $formIsValid = false;
            $errors[] = "Numéro de téléphone trop long.";
        }
        //si on n'a pas reçu quelque chose qui ressemble à un nombre
        elseif(!is_numeric(str_replace(" ", "", $numero))){
            $formIsValid = false;
            $errors[] = "Des nombres s'il vous plaît !";
        }
//si le formulaire est toujours valide... 
if ($formIsValid == true){
    //on écrit tout d'abord notre requête SQL, dans une variable
    $sql = "INSERT INTO contact 
            (nom, prenom, email, nom_entreprise, adresse, numero, comment, date)
            VALUES 
            (:nom, :prenom, :email, :nom_entreprise, :adresse, :numero, :comment, NOW())";

    /*
    injection SQL dans le champs de mot de passe : 
    pass', '44', NOW()); DROP DATABASE kinoa; --
    */
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":nom" => $nom,
        ":prenom" => $prenom, 
        ":email" => $email,
        ":nom_entreprise" => $nom_entreprise, 
        ":adresse" => $adresse, 
        ":numero" => $numero, 
        ":comment" => $comment, 
    ]);
}
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Me contacter</title>
    <link rel="stylesheet" href="css/resume.css">
</head>
<body>
    <!-- seuls les formulaires de recherche doivent être en get -->
    <form method="post">
        <div> 
            <label for="nom">Votre nom</label>
            <input type="text" name="nom" id="nom">
        </div>
        <div> 
            <label for="prenom">Votre prénom</label>
            <input type="text" name="prenom" id="prenom">
        </div>
        <div> 
            <label for="email">Votre email</label>
            <input type="email" name="email" id="email">
        </div>
        <div> 
            <label for="nom_entreprise">Le nom de votre entreprise</label>
            <input type="nom_entreprise" name="nom_entreprise" id="nom_entreprise">
        </div>
        <div> 
            <label for="adresse">L'adresse de votre entreprise</label>
            <input type="text" name="adresse" id="adresse">
        </div>
        <div> 
            <label for="numero">Numéro de téléphone</label>
            <input type="tel" name="numero" id="numero">
        </div>
        <div>
            <label for="comment">Commentaires</label>
            <textarea id="comment" name="comment"></textarea>
            
        </div>


        <?php 
        //affiche les éventuelles erreurs de validations
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div>' . $error . '</div>'    ;
            }
        }   
        ?>

        <button>Envoyer</button>
    </form>
</body>
</html>