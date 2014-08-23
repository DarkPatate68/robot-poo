<h1>Modification du profil</h1>

<form action="" method="post" enctype="multipart/form-data">
<div>
	  <label for="pseudo">Pseudo * </label>
	  <input type="text" name="pseudo" id="pseudo" value="<?php echo $user->membre()->pseudo() ?>" readonly/><br />
	  
	  <label for="nom">Nom * </label>
	  <input type="text" name="nom" id="nom" value="<?php echo $user->membre()->nom() ?>" required/><br />
	  
	  <label for="prenom">Prénom * </label>
	  <input type="text" name="prenom" id="prenom" value="<?php echo $user->membre()->prenom() ?>" required/><br />
	  
	  <label for="usuel">Utiliser *</label>
		<select name="usuel" id="usuel">
			<option value="usuel_pseudo" <?php if($user->membre()->usuel() == $user->membre()->pseudo()) echo 'selected';?>>Pseudo</option>
			<option value="usuel_prenom_nom" <?php if($user->membre()->usuel() != $user->membre()->pseudo()) echo 'selected';?>>Prénom + nom</option>
		</select><br/>
		
	  <label for="telephone"><span class="abbr_info" title="Le téléphone ne sera pas affiché sur le site. Il peut être utile au bureau."
			>Téléphone</span> </label>
	  <input type="tel" name="telephone" id="telephone" value="<?php echo $user->membre()->telephone() ?>" pattern="^(\+33|0)[1-9]([-. ]?[0-9]{2}){4}$"/><br />
	  
	  <label for="section">Votre section * </label>
       <select name="section" id="section">
	   <?php
			foreach(\Library\Entities\Section::$SECTIONS_INSA as $section)
			{
				if($user->membre()->section() == $section)
					echo '<option value="' . $section . '" selected>' . $section . '</option>';
				else
					echo '<option value="' . $section . '">' . $section . '</option>';
			}
	   ?>
       </select><br/>
	   
	   <label for="tshirt">Taille de T-Shirt </label>
       <select name="tshirt" id="tshirt">
	   <?php
			foreach(\Library\Entities\Tshirt::$TSHIRT as $tshirt)
			{
				if($user->membre()->tshirt() == $tshirt)
					echo '<option value="' . $tshirt . '" selected>' . $tshirt . '</option>';
				else
					echo '<option value="' . $tshirt . '">' . $tshirt . '</option>';
			}
	   ?>
       </select><br/>
	   
	  <label for="bio">Biographie</label><br/>
	  <textarea name="bio" id="bio" rows="15" cols="60"><?php echo $user->membre()->biographie() ?></textarea><br />
	  
	  
	  <label for="avatar">Avatar </label> <input type="file" id="avatar" name="photo" /><br/>
	  <label>Avatar actuel</label> <img src="<?php echo '../images/membres/' . $user->membre()->avatar(); ?>" alt="avatar"/><br/>
	  
	  <br/><br/>
	  <em>* Champ obligatoire.</em>
	  <br/>
	  <input type="submit" value="Modifier" />
</div>
</form>