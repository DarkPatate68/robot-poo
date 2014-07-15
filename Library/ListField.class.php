<?php
namespace Library;

/**
 * Classe représentat un select. Il peut être paramétré
 * @author Siméon
 * @date 29/06/2014
 */
class ListField extends Field
{
	protected $options; /**< Représente la liste des paramètres à afficher */
	protected $selected; /**< Représente l'option sélectionnée par défaut */
	protected $disabled; /**< Si oui ou non le champ pourra être modifié. */
	
	/**
	 * Construit le select en remplissant utilisant les différents paramètres rentré.
	 * @see \Library\Field::buildWidget()
	 */
	public function buildWidget()
	{
		$widget = '';

		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}

		$widget .= '<label for="' . $this->name . '">'.$this->label.'</label>';
		
				
		
		$widget .= '<select name="'.$this->name.'" id="' . $this->name . '" ';
		
		if($this->disabled)
			$widget .= 'disabled="disabled"';
		
		$widget .= '>';
		foreach ($this->options as $option)
		{
			$widget .= '<option value="' . $option . '" ';
			if($this->selected == $option && $this->selected !== false)
				$widget .= 'selected="selected"';
			$widget .= '>' . $option . '</option>';
		}
		
		return $widget .= '</select>';
	}

	/**
	 * Accesseur en écriture de la liste présente dans le select
	 * @param array $options
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}

	/**
	 * Accesseur en écriture du paramètre disabled (paramètre HTML)
	 * @param bool $dis
	 */
	public function setDisabled($dis)
	{
		$this->disabled = (bool) $dis;
	}
	
	/**
	 * Accesseur en écriture du paramètre selected (paramètre HTML). Si la chaîne est égale a une des options alors elle sera sélectionnée par défaut.
	 * @param string $selected Si le paramètre vaut @b false, il ne sera pas pris en compte.
	 * @return void
	 */
	public function setSelected($selected)
	{
		if($selected !== false)
			$this->selected = (string) $selected;
		else
			$this->selected = $selected;
	}
}