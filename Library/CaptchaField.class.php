<?php
namespace Library;

class CaptchaField extends Field
{
	public function buildWidget()
	{
		$widget = '';

		if (!empty($this->errorMessage))
		{
			$widget .= $this->errorMessage.'<br />';
		}

		$widget .= '<label for="' . $this->name . '">Recopiez le chiffre ci-contre : <img src="captcha.php" alt="captcha" /></label><input type="text" name="'.$this->name.'" id="' . $this->name . '"';

		return $widget .= ' />';
	}
}