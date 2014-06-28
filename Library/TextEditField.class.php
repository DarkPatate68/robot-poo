<?php
namespace Library;

class TextEditField extends Field
{
	protected $cols;
	protected $rows;
	protected $boutonsEdition = true;

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
		
		
		$widget .= '<textarea name="'.$this->name.'" id="' . $this->name . '"';

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

	public function setCols($cols)
	{
		$this->cols = (int) $cols;
	}

	public function setRows($rows)
	{
		$this->rows = (int) $rows;
	}
	
	public function setBoutonsEdition($ed)
	{
		$this->boutonsEdition = (bool) $ed;
	}
	
	private function buttons()
	{
		$buttons = '
		<div>
        <p>
            <input class="btMiseEnForme" type="button" value="G" onclick="insertTag(\'<gras>\',\'</gras>\',\''.$this->name.'\');" />
            <input class="btMiseEnForme" type="button" value="I" onclick="insertTag(\'<italique>\',\'</italique>\',\''.$this->name.'\');" />
            <input class="btMiseEnForme" type="button" value="Lien" />
            <input class="btMiseEnForme" type="button" value="Image" />
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