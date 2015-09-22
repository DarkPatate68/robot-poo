<?php 
$lienPrecedent = 'messagerie';
if(isset($_SESSION['page_messagerie']))
{
	$lienPrecedent .= '-' . $_SESSION['page_messagerie'];
}

/*if(isset($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], $GLOBALS['PREFIXE'].'/membre/messagerie'))
{
	$temp = explode('/', $_SERVER['HTTP_REFERER']);
	$lienPrecedent = end($temp);
}*/
?>

<div id="tabs">
    <ul class="mui-tabs">
        <li><a href="<?php echo $lienPrecedent; ?>" class="icon-undo2" title="Retour à la boîte de réception"></a></li> 
        <li><a href="<?php echo "messagerie-ecrire-".$mail->id(); ?>" class="icon-pencil2" title="Répondre"></a></li> 
        <li><a href="javascript:void(0)" class="icon-bin" title="Mettre à la corbeille"></a></li> 
    </ul>
</div>

<div id="container">
	<div id="message">
	<table id="meta-mail">
		<tr><td><strong>Expediteur</strong></td><td><?php echo $mail->expediteur(); ?></td></tr>
		<tr><td><strong>Date</strong></td><td><?php echo $mail->date()->format('d/m/Y&\nb\sp;à&\nb\sp;H:i'); ?></td></tr>
		<tr><td><strong>Objet</strong></td><td><strong><?php echo \Library\Entities\FormatageTexte::monoLigne($mail->objet()); ?></strong></td></tr>
	</table>
	<?php echo \Library\Entities\FormatageTexte::multiLigne($mail->texte()); ?></div>
</div>

