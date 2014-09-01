<h1><?php echo $title; ?></h1>

Nouveau groupe dans lequel ce membre doit être placé :
<form action="" method="post">
<select name="groupe">
	<?php 
		foreach($listeGroupe as $groupe)
		{
			if($groupe->id() == 1)
				continue;
			if($groupe['id'] == $groupeMbr)
				echo '<option value="' . $groupe['id'] . '" selected>' . $groupe['nom'] . '</option>';
			else
				echo '<option value="' . $groupe['id'] . '">' . $groupe['nom'] . '</option>';
		}
	?>
	</select>
	<input type="hidden" name="id" value="<?php echo $idMbr; ?>" />
	<input type="submit" />
</form>
