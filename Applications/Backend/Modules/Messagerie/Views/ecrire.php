<?php 
$lienPrecedent = "messagerie";
if(isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], $GLOBALS['PREFIXE'].'/membre/messagerie'))
{
	$temp = explode('/', $_SERVER['HTTP_REFERER']);
	$lienPrecedent = end($temp);
}
?>
<h1>Écrire un courriel</h1>

<div class="note">
<strong>Attention :</strong> toutes les options de mises en forme ne seront pas forcément prisent en compte lors de la lecture du courriel. Tenez vous
en à de la mise en page basique (gras, italique, liste à puce, images...).<br/>
De plus, certaines personnes ne recevront qu'une version en texte brut (sans mise en forme) en fonction des paramètres de leur client de messagerie.<br/><br/>
Séparez les destinataires avec un point-virgule (;).
</div>

<form action="" method="post">
  <p> 
  	<label for="destinataire">Destinataire :</label><input type="text" name="destinataire" id="destinataire" style="width:300px;" value="<?php echo $mail->expediteur(); ?>"/> <button class="ss-bt-code icon-users" title="Contacts"></button><br/>
	<?php echo $form; ?>
    <a href="<?php echo $lienPrecedent; ?>" class="mui-btn mui-btn-danger lien-btn">Annuler</a>
    <input type="submit" value="Envoyer" name="envoyer" class="mui-btn mui-btn-primary"/>

  </p>
</form>
