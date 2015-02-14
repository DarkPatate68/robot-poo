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
	 * Accesseur en écriteur de la longeur (width) du textarea (paramètre CSS). Ce nombre est un pourcentage
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
	 * Accesseur en écriteur de la hauteur (height) du textarea (paramètre CSS). Ce nombre est en pixel
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
		$buttons = '
		<div>
        <p>
			<a title="Gras" class="bt-editeur icon-bold" onclick="insertTag(\'**\',\'**\',\''.$this->name.'\');"></a>
            <a title="Italique" class="bt-editeur icon-italic" onclick="insertTag(\'*\',\'*\',\''.$this->name.'\');"></a>
            <a title="Barré" class="bt-editeur icon-strikethrough" onclick="insertTag(\'~~\',\'~~\',\''.$this->name.'\');"></a>
            <a title="Exposant" class="bt-editeur icon-superscript" onclick="insertTag(\'^\',\'^\',\''.$this->name.'\');"></a>
            <a title="Indice" class="bt-editeur icon-subscript" onclick="insertTag(\'~\',\'~\',\''.$this->name.'\');"></a>

            <a title="Aligner à gauche" class="bt-editeur icon-paragraph-left" style="margin-left: 30px;" onclick="insertTag(\'<-\',\'<-\',\''.$this->name.'\');"></a>
            <a title="Centrer" class="bt-editeur icon-paragraph-center" onclick="insertTag(\'->\',\'<-\',\''.$this->name.'\');"></a>
            <a title="Aligner à droite" class="bt-editeur icon-paragraph-right" onclick="insertTag(\'->\',\'->\',\''.$this->name.'\');"></a>
            		
            <a title="Liste" class="bt-editeur icon-list" style="margin-left: 30px;" onclick="insertTag(\'- \',\'\',\''.$this->name.'\');"></a>
            <a title="Liste numérotée" class="bt-editeur icon-list-numbered" onclick="insertTag(\'1. \',\'\',\''.$this->name.'\');"></a>
            		
            <a title="Titre" class="bt-editeur icon-section" style="margin-left: 30px;" onclick="insertTag(\'# \',\'\',\''.$this->name.'\');"></a>
            <a title="Touche" class="bt-editeur icon-keyboard" onclick="insertTag(\'||\',\'||\',\''.$this->name.'\');"></a>
            		
            <a title="Citation" class="bt-editeur icon-quotes-right" style="margin-left: 30px;" onclick="insertTag(\'> \',\'\',\''.$this->name.'\');"></a>
            <a title="Image" class="bt-editeur icon-image" onclick="insertTag(\'||\',\'||\',\''.$this->name.'\');"></a>
            <a title="Lien" class="bt-editeur icon-link" onclick="insertTag(\'||\',\'||\',\''.$this->name.'\');"></a>
            <a title="Code source" class="bt-editeur icon-embed2" onclick="insertTag(\'||\',\'||\',\''.$this->name.'\');"></a>
            <a title="Pièce CAO" class="bt-editeur icon-codepen" onclick="insertTag(\'![_CAO_](\',\')\',\''.$this->name.'\');"></a>
        </p>
        <!--<p>
            <input name="previsualisation" type="checkbox" id="previsualisation" value="previsualisation" /> <label for="previsualisation">Prévisualisation automatique</label>
        </p>-->
		';
		
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
}