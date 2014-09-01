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
		
		$texte = preg_replace('#&lt;liste&gt;(.+)&lt;/liste&gt;#isU', '<ul>$1</ul>', $texte); // liste à puce désordonnée
		$texte = preg_replace('#&lt;liste type="1"&gt;(.+)&lt;/liste&gt;#isU', '<ol>$1</ol>', $texte); // liste à puce ordonnée
		$texte = preg_replace('#&lt;puce&gt;(.+)&lt;/puce&gt;#isU', '<li>$1</li>', $texte); // puce
		
		$texte = preg_replace('#&lt;titre2&gt;(.+)&lt;/titre2&gt;#isU', '<h2>$1</h2>', $texte); // Titre 2
	
		return $texte;
	}
}