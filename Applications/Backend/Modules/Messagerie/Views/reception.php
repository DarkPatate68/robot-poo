<table id="reception">
   <tr>
       <th class="reception-action"></th>
       <th class="reception-objet">Objet</th>
       <th class="reception-expediteur">Expéditeur</th>
       <th class="reception-date">Date</th>
   </tr>

   <?php 
   $i=0;
   foreach ($listeMail as $mail)
   {
   	$classe = '';
   	if($mail->lu() == 0)
   	{
   		$classe = 'class="reception-nonLu';
   		if($i%2==0)
   			$classe .= ' paire"';
   		else
   			$classe .= '"';
   	}
   	else if($i%2==0)
   	{
   		$classe = 'class="paire"';
   	}
   		
   ?>
   <tr <?php echo $classe;?>>
   		<td><input type="checkbox" onclick="selection(<?php echo $mail->id(); ?>)"/></td>
   		<td><a href="messagerie-lire-<?php echo $mail->id(); ?>"><?php echo $mail->objet(); ?></a></td>
   		<td><span style="white-space: nowrap"><?php echo $mail->expediteur(); ?></span></td>
   		<td><?php echo $mail->date()->format('d/m/Y&\nb\sp;à&\nb\sp;H:i'); ?></td>
   </tr>
   <?php $i++;} ?>
</table>

