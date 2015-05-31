<h2>Ajouter un commentaire</h2>
<form action="" method="post">
  <p> 
	<?php echo $form; ?>
    <input type="submit" value="Commenter" />
  </p>
</form>
<?php 
if($droitImport)
	echo \Library\TextEditField::imageDialogImage($idTextEdit, '');
else
	echo \Library\TextEditField::imageDialogImageLambda($idTextEdit);
?>