<?php
namespace Library;

/**
 * Classe représentat un textarea. Il peut être paramétré
 * @author Siméon
 * @date 29/06/2014
 */
class TextEditField extends Field
{
	protected $cols; /**< Représente le nombre de colonne du textarea. Attntion, le paramètre width est prioritaire dessus */
	protected $rows; /**< Représente le nombre de ligne du textarea. */
	protected $width = false; /**< Largeur en pourcentage du textarea, s'il est égal à @b false alors c'est le paramètre cols qui est utilisé. */
	protected $height = false; /**< Hauteur en pixel du textarea, s'il est égal à @b false alors c'est le paramètre rows qui est utilisé. */
	protected $boutonsEdition = true; /**< Active ou non les boutons de mise en forme (gras, italique...) */
	protected $app; /**< Représente l'application */

	/**
	 * Construit le textarea en remplissant utilisant les différents paramètres rentré.
	 * @see \Library\Field::buildWidget()
	 */
	public function buildWidget()
	{
		$widget = '';

		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}

		if($this->boutonsEdition)
			$widget .= $this->buttons();
		else
			$widget .= '<label for="' . $this->name . '">'.$this->label.'</label>';
		
		if($this->width === false)
		    $txtStyle = '';
		else
		    $txtStyle = 'style="width:' . (int) $this->width . '%;';
		
		if($this->height !== false)
			$txtStyle .= ' height:' . (int) $this->height . 'px;"';
		else
			$txtStyle .= '"';
		
		
		$widget .= '<textarea name="'.$this->name.'" id="' . $this->name . '" ' . $txtStyle;

		if (!empty($this->cols) && $this->cols > 0)
		{
			$widget .= ' cols="'.$this->cols.'"';
		}

		if (!empty($this->rows) && $this->rows > 0)
		{
			$widget .= ' rows="'.$this->rows.'"';
		}

		$widget .= '>';

		if (!empty($this->value))
		{
			$widget .= $this->value;
		}

		
		if($this->boutonsEdition)
			return $widget.'</textarea>' . $this->buttonsEnd();
		else
			return $widget.'</textarea>';		
	}

	/**
	 * Accesseur en écriture du paramètre colonne (cols) du textarea (paramètre HTML)
	 * @param int $cols
	 */
	public function setCols($cols)
	{
		$this->cols = (int) $cols;
	}

	/**
	 * Accesseur en écriture du paramètre ligne (rows) du textarea (paramètre HTML)
	 * @param int $rows
	 */
	public function setRows($rows)
	{
		$this->rows = (int) $rows;
	}
	
	/**
	 * Accesseur en écriture du paramètre BoutonEdition
	 * @param bool $ed Si oui ou non, on affiche les boutons de mise en forme
	 */
	public function setBoutonsEdition($ed)
	{
		$this->boutonsEdition = (bool) $ed;
	}
	
	/**
	 * Accesseur en écriture de la longeur (width) du textarea (paramètre CSS). Ce nombre est un pourcentage
	 * S'il est inférieur ou égal à 0, il est ramené à @b false (inutilisé) ; s'il est supérieur à 100, il est ramené
	 * à 100.
	 * @param int $width
	 */
	public function setWidth($width)
	{
	    $this->width = (int) $width;
	    if($this->width <= 0)
	        $this->width = false;
	    else if($this->width >= 100)
	        $this->width = 100;
	}
	
	/**
	 * Accesseur en écriture de l'application
	 * @param Application $app
	 */
	public function setApp(\Library\Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Accesseur en écriture de la hauteur (height) du textarea (paramètre CSS). Ce nombre est en pixel
	 * S'il est inférieur ou égal à 0, il est ramené à @b false (inutilisé)
	 * @param int $height
	 */
	public function setHeight($height)
	{
		$this->height = (int) $height;
		if($this->height <= 0)
			$this->height = false;
	}
	
	/**
	 * Retourne la première partie des boutons de mise en forme du message (la partie au-dessus du textarea)
	 * @return string
	 */
	private function buttons()
	{
		$GLOBALS['bdo'] = true;
		$txtBoiteDialogue = $this->txtBtDialogue();
		$buttons = '
		<div>
        <div class="barre-outil">
			<button type="button" title="Gras" class="bt-editeur icon-bold" onclick="insertTag(\'**\',\'**\',\''.$this->name.'\');"></button>
            <button type="button" title="Italique" class="bt-editeur icon-italic" onclick="insertTag(\'*\',\'*\',\''.$this->name.'\');"></button>
            <button type="button" title="Barré" class="bt-editeur icon-strikethrough" onclick="insertTag(\'~~\',\'~~\',\''.$this->name.'\');"></button>
            <button type="button" title="Exposant" class="bt-editeur icon-superscript" onclick="insertTag(\'^\',\'^\',\''.$this->name.'\');"></button>
            <button type="button" title="Indice" class="bt-editeur icon-subscript" onclick="insertTag(\'~\',\'~\',\''.$this->name.'\');"></button>

            <button type="button" title="Aligner à gauche" class="bt-editeur icon-paragraph-left" style="margin-left: 30px;" onclick="insertTag(\'<-\',\'<-\',\''.$this->name.'\');"></button>
            <button type="button" title="Centrer" class="bt-editeur icon-paragraph-center" onclick="insertTag(\'->\',\'<-\',\''.$this->name.'\');"></button>
            <button type="button" title="Aligner à droite" class="bt-editeur icon-paragraph-right" onclick="insertTag(\'->\',\'->\',\''.$this->name.'\');"></button>
            		
            <button type="button" title="Liste" class="bt-editeur icon-list" style="margin-left: 30px;" onclick="insertTag(\'- \',\'\',\''.$this->name.'\');"></button>
            <button type="button" title="Liste numérotée" class="bt-editeur icon-list-numbered" onclick="insertTag(\'1. \',\'\',\''.$this->name.'\');"></button>
            		
            <div id="bdo-titre-conteneur"><button type="button" title="Titre" class="bt-editeur icon-section" id="bdo-titre" style="margin-left: 30px;"></button>
            	<div id="bdo-titre-menu" style="display:none">
            		<button type="button" class="ss-bt-titre" onclick="insertTag(\'# \',\'\',\''.$this->name.'\');"/>Titre 1</button><br/>
            		<button type="button" class="ss-bt-titre" onclick="insertTag(\'## \',\'\',\''.$this->name.'\');"/>Titre 2</button><br/>
            		<button type="button" class="ss-bt-titre" onclick="insertTag(\'### \',\'\',\''.$this->name.'\');"/>Titre 3</button><br/>
            		<button type="button" class="ss-bt-titre" onclick="insertTag(\'#### \',\'\',\''.$this->name.'\');"/>Titre 4</button><br/>
            		<button type="button" class="ss-bt-titre" onclick="insertTag(\'##### \',\'\',\''.$this->name.'\');"/>Titre 5</button><br/>
            		<button type="button" class="ss-bt-titre" onclick="insertTag(\'###### \',\'\',\''.$this->name.'\');"/>Titre 6</button>
            	</div></div>
            <button type="button" title="Touche" class="bt-editeur icon-keyboard" onclick="insertTag(\'||\',\'||\',\''.$this->name.'\');"></button>

            <div style="display: inline;"><label for="checkDialogueCitation" title="Citation" class="bt-editeur icon-quotes-right" style="margin-left: 30px;" onclick="recupererSelection(\''.$this->name.'\', \'citation-bdo\')">
            		</label><input type="checkbox" id="checkDialogueCitation" class="modalCheck"/>
            		' . \Library\Entities\Utilitaire::fenetreModale("Insertion d'une citation", $txtBoiteDialogue['citation'], "checkDialogueCitation", true, "bdoCitation('$this->name', false)", "bdoCitation('$this->name', true)") . '</div>
            <div style="display: inline;"><label id="checkDialogueImage-label" for="checkDialogueImage" title="Image" class="bt-editeur icon-image">
            	</label><input type="checkbox" id="checkDialogueImage" class="modalCheck"/></div>
            		
            <div style="display: inline;"><label for="checkDialogueLien" title="Lien" class="bt-editeur icon-link" onclick="recupererSelection(\''.$this->name.'\', \'lien-texte-bdo\')">
            		</label><input type="checkbox" id="checkDialogueLien" class="modalCheck"/>
            		' . \Library\Entities\Utilitaire::fenetreModale("Insertion d'un lien", $txtBoiteDialogue['lien'], "checkDialogueLien", true, "bdoLien('$this->name', false)", "bdoLien('$this->name', true)") . '</div>
            <div id="bdo-code-conteneur"><button type="button" title="Code source" class="bt-editeur icon-embed2" id="bdo-code"></button>
            	<div id="bdo-code-menu" style="display:none">
            		<div>
            			<strong>Web</strong>
            			<ul>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.html\n\',\'\n````\n\',\''.$this->name.'\');"/>HTML</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.css\n\',\'\n````\n\',\''.$this->name.'\');"/>CSS</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.javascript\n\',\'\n````\n\',\''.$this->name.'\');"/>Javascript</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.php\n\',\'\n````\n\',\''.$this->name.'\');"/>PHP</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.sql\n\',\'\n````\n\',\''.$this->name.'\');"/>SQL</button></li>
            			</ul>
            		</div>
            		<div>
            			<strong>Prog</strong>
            			<ul>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.c\n\',\'\n````\n\',\''.$this->name.'\');"/>C</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.cpp\n\',\'\n````\n\',\''.$this->name.'\');"/>C++</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.python\n\',\'\n````\n\',\''.$this->name.'\');"/>Python</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.java\n\',\'\n````\n\',\''.$this->name.'\');"/>Java</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.matlab\n\',\'\n````\n\',\''.$this->name.'\');"/>Matlab</button></li>
            			</ul>
            		</div>
            		<div>
            			<strong>Autres</strong>
            			<ul>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.latex\n\',\'\n````\n\',\''.$this->name.'\');"/>LaTeX</button></li>
            				<li><button type="button" class="ss-bt-code" onclick="insertTag(\'````.markdown\n\',\'\n````\n\',\''.$this->name.'\');"/>Markdown</button></li>
            			</ul>
            		</div>
            	</div>
            </div>
            <button type="button" title="Pièce CAO" class="bt-editeur icon-codepen" onclick="insertTag(\'![_CAO_](\',\')\',\''.$this->name.'\');"></button>
        </div>
        <!--<p>
            <input name="previsualisation" type="checkbox" id="previsualisation" value="previsualisation" /> <label for="previsualisation">Prévisualisation automatique</label>
        </p>-->
		';
		//onclick="insertTag(\'# \',\'\',\''.$this->name.'\');"
		return $buttons;
	}
	
	/**
	 * Retourne la seconde partie des boutons de mise en forme du message (la partie sous le textarea)
	 * @return string
	 */
	private function buttonsEnd()
	{
		$buttons = '
		<!--<div id="previewDiv"></div>

		<p>
			<input type="button" value="Visualiser" onclick="view(\'textarea\',\'viewDiv\');" />
		</p>

		<div id="viewDiv"></div>--></div>';
	
		return $buttons;
	}
	
	/**
	 * Genère le code source HTML des boîtes de dialogue de la barre d'outils
	 * @return array code Le code source
	 */
	private function txtBtDialogue()
	{
		$code['citation'] = '<label for="citation-auteur-bdo" style="width: 200px;">Auteur de la citation :</label><input type="text" id="citation-auteur-bdo" name="citation-auteur-bdo"/><br/>
							 <label for="citation-bdo">Citation :</label><br/>
							 <textarea id="citation-bdo" name="citation-bdo" style="width: 100%; height: 100px;"></textarea>';
		
		$code['lien']	  = '<label for="lien-lien-bdo" style="width: 250px;">URL du lien :</label><input type="text" id="lien-lien-bdo" name="lien-lien-bdo" style="width: 350px;"/><br/>
							 <label for="lien-texte-bdo" style="width: 250px;">Texte de remplacement :</label><input type="text" id="lien-texte-bdo" name="lien-texte-bdo" style="width: 350px;"/><br/>';
		
		return $code;
	}
	
	static public function imageDialogImage($idTextEdit, $partieMembre = '../')
	{
		$domaines = \Applications\Backend\Modules\ImportImage\ImportImageController::$DOMAINES;
		$code['image']	  = '
							 	<div>
									<label for="image"><span class="tooltip">Image :<span>Uniquement les formats JPEG, PNG, GIF et BMP.</span></span> </label>
									   <input type="hidden" name="MAX_FILE_SIZE" value="3145728" />
									   <input type="file" name="image" id="image" required/><br/>
		
									<label for="alt"><span class="tooltip">Légende :<span>Est affiché sous l\'image pour une figure (faire commencer par \'_\' pour ne pas l\'afficher).<br/>Est également affiché pour les personnes non-voyantes, c\'est pour ça qu\'une légende est <strong>obligatoire</strong>.</span></span> </label>
									   <input type="text" name="alt" id="alt" /><br/>
		
								    <label for="hauteur"><span class="tooltip">Taille image : <span>Ne rien mettre pour garder la taille par défaut. <br/><strong>Redimensionnement uniquement pour les images JPEG et PNG.</strong> <br/>Mettre qu\'une seule dimension pour garder l\'échelle.</span></span> <strong>(hauteur)</strong></label>
									   <input type="number" name="hauteur" id="hauteur" min="0" /><br/>
								    <label for="largeur"><span class="invisible">Taille image : </span><strong>(largeur)</strong></label>
									   <input type="number" name="largeur" id="largeur" min="0" /><br/>
		
									<label for="domaine">Domaine :</label>
									<select name="domaine" id="domaine">';
		
		foreach($domaines as $domaineDossier => $domaineTexte)
		{
			$code['image'] .= '<option value="' . $domaineDossier . '">' . $domaineTexte . '</option>';
		}
		
		$code['image'] .=			'</select>
							<input type="hidden" name="idTextEdit" id="idTextEdit" value="' . (string) $idTextEdit . '"/>
							<input type="hidden" name="mdp" id="mdp" value="akIU$2as85B"/>
								  </div>
							 
		
							<div id="info-upload-image">
							    <div id="status-upload-image">En attente d\'un fichier.</div>
							    <iframe id="frame-upload-image" name="frame-upload-image-name" style="display:none;"></iframe>
							</div>
									<script type="test/javascript">
							document.getElementById(\'form-upload-image\').addEventListener(\'submit\', function() {
						    document.getElementById(\'status-upload-image\').innerHTML = \'Envoi en cours...\';
							}, true);</script>';
		
		return \Library\Entities\Utilitaire::fenetreModale("Importation d'une image", $code['image'], "checkDialogueImage", 'image', "document.getElementById('form-upload-image').submit()", '', $partieMembre);
	}
	
	static public function imageDialogImageLambda($idTextEdit)
	{
		
		$code['image']	  = '<label for="image-image-bdo" style="width: 250px;">URL de l\'image :</label><input type="text" id="image-image-bdo" name="image-image-bdo" style="width: 350px;"/><br/>
							 <label for="image-alt-bdo" style="width: 250px;">Texte de remplacement :</label><input type="text" id="image-alt-bdo" name="image-alt-bdo" style="width: 350px;"/><br/>';
		
		return \Library\Entities\Utilitaire::fenetreModale("Insertion d'une image", $code['image'], "checkDialogueImage", 'imageLambda', "bdoImageLambda('$idTextEdit', false)");
	}
}