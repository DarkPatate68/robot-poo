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
		    $txtWidth = '';
		else
		    $txtWidth = 'style="width:' . (int) $this->width . '%"';
		
		
		$widget .= '<textarea name="'.$this->name.'" id="' . $this->name . '" ' . $txtWidth;

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
	 * Retourne la première partie des boutons de mise en forme du message (la partie au-dessus du textarea)
	 * @return string
	 */
	private function buttons()
	{
		$buttons = '
		<div>
        <p>
            <input class="btMiseEnForme" type="button" value="G" onclick="insertTag(\'<gras>\',\'</gras>\',\''.$this->name.'\');" />
            <input class="btMiseEnForme" type="button" value="I" onclick="insertTag(\'<italique>\',\'</italique>\',\''.$this->name.'\');" />
            <input class="btMiseEnForme" type="button" value="Lien"  onclick="insertTag(\'<lien=\',\'></lien>\',\''.$this->name.'\');"/>
            <input class="btMiseEnForme" type="button" value="Image" onclick="insertTag(\'<img=\',\'>\',\''.$this->name.'\');"/>
            <input class="btMiseEnForme" type="button" value="Citation" />
            <select onchange="insertTag(\'<\' + this.options[this.selectedIndex].value + \'>\', \'</\' + this.options[this.selectedIndex].value + \'>\');">
                <option value="none" class="selected" selected="selected"><em>Taille</em></option>
                <option value="ttpetit">Très très petit</option>
                <option value="tpetit">Très petit</option>
                <option value="petit">Petit</option>
                <option value="gros">Gros</option>
                <option value="tgros">Très gros</option>
                <option value="ttgros">Très très gros</option>
            </select>
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