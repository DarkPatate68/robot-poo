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
		
		$texte = preg_replace('#&lt;liste&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ul class="puce_normale">$1</ul>', $texte); // liste à puce désordonnée
		$texte = preg_replace('#&lt;liste type="1"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ol>$1</ol>', $texte); // liste à puce ordonnée
		$texte = preg_replace('#&lt;puce&gt;(.+)&lt;/puce&gt;#isU', '<li>$1</li>', $texte); // puce
			$texte = preg_replace('#<ul class="puce_normale">(?:<br />)+#is', '<ul class="puce_normale">', $texte); // On enlève le retour chariot
			$texte = preg_replace('#</ul>(?:<br />)+#is', '</ul>', $texte); // On enlève le retour chariot
			$texte = preg_replace('#<ol>(?:<br />)+#is', '<ol>', $texte); // On enlève le retour chariot
			$texte = preg_replace('#</ol>(?:<br />)+#is', '</ol>', $texte); // On enlève le retour chariot
			$texte = preg_replace('#</li>(?:<br />)+#is', '</li>', $texte); // On enlève le retour chariot
		
		$texte = preg_replace('#&lt;titre2&gt;(.+)&lt;/titre2&gt;#isU', '<h2>$1</h2>', $texte); // Titre 2
			$texte = preg_replace('#</h2>(?:<br />)#isU', '</h2>', $texte); // On enlève le retour chariot
		
		$texte = preg_replace('#&lt;lien=(.+)&gt;(.+)&lt;/lien&gt;#isU', '<a href="$1">$2</a>', $texte); // Lien
		$texte = preg_replace('#&lt;img=(.+)&gt;#isU', '<img src="$1"/>', $texte); // Image
		
		//couleurs
		$texte = preg_replace('#&lt;couleur=rouge&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:red;">$1</span>', $texte); // rouge
		$texte = preg_replace('#&lt;couleur=orange&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:orange;">$1</span>', $texte); // orange
		$texte = preg_replace('#&lt;couleur=vert&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:green;">$1</span>', $texte); // vert
		
		
		$texte = preg_replace('#&lt;ligne/&gt;#isU', '<hr/>', $texte); // ligne
	
		return $texte;
	}
}