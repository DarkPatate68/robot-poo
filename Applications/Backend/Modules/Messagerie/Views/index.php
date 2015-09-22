<h1><?php echo $title; ?></h1>

<div id="messagerie-button-box"><button class="mui-btn mui-btn-raised mui-btn-primary" onclick="location.href='messagerie-ecrire';">Nouveau message</button>
								<button id="bt-suppr" class="mui-btn mui-btn-raised icon-bin disparait" title="Mettre à la corbeille"></button>
								<button id="bt-lu" class="mui-btn mui-btn-raised icon-eye disparait" title="Basculer lu/non lu"></button>
								<button id="bt-repondre" class="mui-btn mui-btn-raised icon-pencil2 disparait" title="Répondre"></button></div>

<div id="tabs">
    <ul class="mui-tabs">
        <li><a href="javascript:void(0)" rel="messagerie-reception" onClick="loadit(this)">Boîte de réception</a></li> 
        <li><a href="javascript:void(0)" rel="equipe" onClick="loadit(this)">Boîte d'envoi</a></li>
        <li><a href="javascript:void(0)" rel="https://zestedesavoir.com" onClick="loadit(this)">Corbeille</a></li>  
    </ul>
</div>

<!-- <iframe id="container"></iframe> -->

<div id="container"></div>
