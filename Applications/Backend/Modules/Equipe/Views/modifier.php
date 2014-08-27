<h2><?php echo $title; ?></h2>

<div class="note">
	<ol>
	<li>NB : <strong>[[PRÉNOM@NOM]]</strong> (cractère alphabétique, ainsi que le tiret et l'espace uniquement – pour le nom et le prénom).</li>
	<li>Pour garder l'ancienne photo, ne pas toucher au champ <em>Photo</em>.</li>
	</ol>
</div>

<form method="post" action="" enctype="multipart/form-data">
<table>
	<tr><td><label for="annee">Année :</label></td>
	<td><select id="annee" name="annee" readonly>
				<option><?php echo $membre['archive']; ?></option>
		</select>
	</td>
	</tr>
	
	<tr><td><label for="membre">Membre (pseudo) :</label></td><td><input id="membre" name="membre" type="text" value="<?php echo $nom; ?>" readonly />
    </td>
    </tr>
    
	<tr><td><label for="classe">Classe :</label></td><td><input type="text" id="classe" name="classe" value="<?php echo $membre['classe']; ?>"/></td></tr>
	<tr><td><label for="fonction">Fonction :</label></td>
		<td>
			<select name="fonction" id="fonction">
				<option value="president" <?php echo ($fonction == 'president')?'selected':''; ?>>Président</option>
				<option value="vice-president" <?php echo ($fonction == 'vice-president')?'selected':''; ?>>Vice-Président</option>
				<option value="tresorier" <?php echo ($fonction == 'tresorier')?'selected':''; ?>>Trésorier</option>
				<option value="secretaire" <?php echo ($fonction == 'secretaire')?'selected':''; ?>>Secrétaire</option>
				<option value="webmaster" <?php echo ($fonction == 'webmaster')?'selected':''; ?>>Webmaster</option>
				<option value="membre" <?php echo ($fonction == 'membre')?'selected':''; ?>>Membre</option>
			</select>
		</td>
	</tr>
	<tr><td><label for="description">Description :</label></td><td><textarea id="description" name="description" rows="10" cols="70"><?php echo $membre['description']; ?></textarea></td></tr>
	<tr><td><label for="photo">Photo :</label></td><td><input type="file" name="photo" id="photo"/></td></tr>
	<tr><td><label for="suppr_photo">Supprimer la photo :</label></td><td><input type="checkbox" name="suppr_photo" id="suppr_photo"></td></tr>
</table>
	<input type="hidden" name="id" value="<?php echo $membre['id']; ?>" />
	<input type="submit"/>
</form>
