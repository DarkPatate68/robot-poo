<h2>Profil de <?php echo \Library\Entities\FormatageTexte::monoLigne($page->usuel()); ?></h2>
    
    <?php if($user->membre()->groupeObjet()->droits('mod_groupe')) 
    		echo '<a href="membre/groupe-changer-membre-' . $page->id() . '">Changer le membre de groupe</a><br/>';
    	?>
    	
<div class="membre">    
    <div class="membre_photo">
        <?php echo '<img src="images/membres/' . $page->avatar() . '" alt="photo" />'; ?>
    </div>
    
    <div class="membre_donnees">
        <div><strong><?php echo $page->prenom() . ' ' . $page->nom() . '</strong> <em>alias</em> <strong>' . $page->pseudo(); ?></strong></div>
        <div><?php echo $page->section(); ?></div>
        <div>Inscrit depuis le <?php echo $page->dateInscription()->format('d/m/Y'); ?></div>
        <div>Groupe : <?php echo $page->groupeObjet()->nom(); ?></div>
        <div>Membre <?php echo $page->actif()?'actif':'inactif';?></div>
        <?php if($page->biographie() != '')
        {?>
        <div class="membre_bio">
            <strong>Biographie :</strong><br/>
            <?php echo $page->biographie();?>
        </div>
        <?php } ?>
    </div>	
</div>

