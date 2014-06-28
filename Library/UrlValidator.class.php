<?php
namespace Library;

class UrlValidator extends Validator
{
	public function isValid($value)
	{
		return preg_match("#^[-a-zA-Z0-9_/]+$#", $value);
	}
}