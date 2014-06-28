<?php

namespace Library\Entities;

abstract class FormatageTexte
{
	static public function monoLigne($texte)
	{
		$texte = (string) $texte;
		return self::zCode(htmlspecialchars($texte));
	}
	
	static public function monoLigneSansZCode($texte)
	{
		$texte = (string) $texte;
		return htmlspecialchars($texte);
	}
	
	static public function multiLigne($texte)
	{
		$texte = (string) $texte;
		return self::zCode(nl2br(htmlspecialchars($texte)));
	}
	
	static public function zCode($texte)
	{
		$texte = preg_replace('#&lt;g(?:ras)?&gt;(.+)&lt;/g(?:ras)?&gt;#isU', '<strong>$1</strong>', $texte); // Gras
		$texte = preg_replace('#&lt;i(?:talique)?&gt;(.+)&lt;/i(?:talique)?&gt;#isU', '<em>$1</em>', $texte); // Italique
	
		return $texte;
	}
}