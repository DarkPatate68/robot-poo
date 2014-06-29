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
}