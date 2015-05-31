<h1><?php echo $title; ?></h1>

<form action="" method="post" enctype="multipart/form-data">
  <p> 
	<label for="image"><span class="tooltip">Image :<span>Uniquement les formats JPEG, PNG, GIF et BMP.</span></span> </label>
	   <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
	   <input type="file" name="image" id="image" required/><br/>
	   
	<label for="alt"><span class="tooltip">Légende :<span>Est affiché sous l'image pour une figure (faire commencer par '_' pour ne pas l'afficher).<br/>Est également affiché pour les personnes non-voyantes, c'est pour ça qu'une légende est <strong>obligatoire</strong>.</span></span> </label>
	   <input type="text" name="alt" id="alt" /><br/>
    
    <label for="hauteur"><span class="tooltip">Taille image : <span>Ne rien mettre pour garder la taille par défaut. <br/><strong>Redimensionnement uniquement pour les images JPEG et PNG.</strong> <br/>Mettre qu'une seule dimension pour garder l'échelle.</span></span> <strong>(hauteur)</strong></label>
	   <input type="number" name="hauteur" id="hauteur" min="0" /><br/>
    <label for="largeur"><span class="invisible">Taille image : </span><strong>(largeur)</strong></label>
	   <input type="number" name="largeur" id="largeur" min="0" /><br/>
	   
	<label for="domaine">Domaine :</label>
	<select name="domaine" id="domaine">
	<?php 
	       foreach($domaines as $domaineDossier => $domaineTexte)
	       {
	           echo '<option value="' . $domaineDossier . '">' . $domaineTexte . '</option>';
	       }
	?>
    </select><br/><br/>

    <input type="submit" value="Importer" name="importer" />
  </p>
</form>