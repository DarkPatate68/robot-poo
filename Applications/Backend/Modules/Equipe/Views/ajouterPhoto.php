<h2><?php echo $title; ?></h2>

<?php 
if(file_exists('images/equipe/' . str_ireplace("/", "-", $archive) . '.jpg'))
{ ?>
<div class="note centre">
	<h3>Ancienne photo</h3>
	<img src="../images/equipe/<?php echo str_ireplace("/", "-", $archive); ?>.jpg" alt="Photo de groupe" width="800px"/>
</div>
<?php } ?>

<form method="post" action="" enctype="multipart/form-data">
<p>
	<label for="photo">Photo :</label><input type="file" name="photo" id="photo"/><br/>
	<input type="hidden" name="archive" value="<?php echo $archive; ?>"/>
	<input type="submit"/>
</p>
</form>

