<?php
namespace Library\Entities;

abstract class Section extends \Library\Entity
{
	public static $SECTIONS_INSA = array('STH 1', 
							'A2', 'A3', 'A4', 'A5', 
							'FIP GCE3', 'FIP GCE4', 'FIP GCE5', 
							'FIP MECA3', 'FIP MECA4', 'FIP MECA4', 
							'G (TOPO) 2', 'G (TOPO) 3', 'G (TOPO) 4', 'G (TOPO) 5', 
							'GC2', 'GC3', 'GC4', 'GC5', 
							'GCE2', 'GCE3', 'GCE4', 'GCE5', 
							'GE2', 'GE3', 'GE4', 'GE5', 
							'GM2', 'GM3', 'GM4', 'GM5', 
							'MIQ2', 'MIQ3', 'MIQ4', 'MIQ5', 
							'PL2', 'PL3', 'PL4', 'PL5', 
							'DIPLÔMÉ', 'AUTRE');
	
	static function valide($section)
	{
		return in_array((string) $section, self::$SECTIONS_INSA);
	}
}