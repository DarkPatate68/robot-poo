<?php
namespace Library\Entities;

abstract class Tshirt extends \Library\Entity
{
	public static $TSHIRT = array('NON DÉFINI', 'XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL');
	
	static function valide($tshirt)
	{
		return in_array((string) $tshirt, self::$TSHIRT);
	}
}