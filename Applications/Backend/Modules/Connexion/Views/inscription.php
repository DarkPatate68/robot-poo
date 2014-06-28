<h1>Inscription</h1>

<form action="" method="post">
<div id="inscription">
	  <label for="pseudo">Pseudo * </label>
	  <input type="text" name="pseudo" id="pseudo" required=""/><br />
	  
	  <label for="nom" >Nom * </label>
	  <input type="text" name="nom" id="nom" required=""/><br />
	  
	  <label for="prenom">Prénom * </label>
	  <input type="text" name="prenom" id="prenom" required=""/><br />
	   
	  <label for="mdp">Mot de passe * </label>
	  <input type="password" name="mdp" id="mdp" required=""/><br />
	  
	  <label for="mdp2">Répéter le mot de passe * </label>
	  <input type="password" name="mdp2" id="mdp2" required=""/><br />
	  
	  <label for="courriel">Courriel * </label>
	  <input type="email" name="courriel" id="courriel" required=""/><br />
	  
	  <label for="section">Votre section * </label>
       <select name="section" id="section" required="">
	   <?php
			foreach(\Library\Entities\Section::$SECTIONS_INSA as $section)
			{
				echo '<option value="' . $section . '">' . $section . '</option>';
			}
	   ?>
       </select>
	  
	  <br/><br/>
	  <em>* Champ obligatoire.</em>
	  <br/>
	  <input type="submit" value="Inscription" />
</div>
</form>