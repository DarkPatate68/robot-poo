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
		$texte = preg_replace('#&lt;s(?:ouligne)?&gt;(.+)&lt;/s(?:ouligne)?&gt;#isU', '<span style="text-decoration: underline;">$1</span>', $texte); // Italique
		
		$texte = preg_replace('#&lt;liste&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ul class="puce_normale">$1</ul>', $texte); // liste à puce désordonnée
		$texte = preg_replace('#&lt;liste type="1"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ol>$1</ol>', $texte); // liste à puce ordonnée
		$texte = preg_replace('#&lt;liste type="cercle"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ul style="list-style-type:circle">$1</ul>', $texte); // liste à cercle
		$texte = preg_replace('#&lt;liste type="carre"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ul style="list-style-type:square">$1</ul>', $texte); // liste à carré
		$texte = preg_replace('#&lt;liste type="rien"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ul style="list-style-type:none">$1</ul>', $texte); // liste vide
		$texte = preg_replace('#&lt;liste type="a"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ol style="list-style-type:lower-alpha">$1</ol>', $texte); // liste lettre minuscule
		$texte = preg_replace('#&lt;liste type="A"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ol style="list-style-type:upper-alpha">$1</ol>', $texte); // liste lettre majuscule
		$texte = preg_replace('#&lt;liste type="i"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ol style="list-style-type:lower-roman">$1</ol>', $texte); // liste chiffre romain minuscule
		$texte = preg_replace('#&lt;liste type="I"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ol style="list-style-type:upper-roman">$1</ol>', $texte); // liste chiffre romain majuscule
		$texte = preg_replace('#&lt;liste type="alpha"&gt;(.+)&lt;/liste&gt;(?:<br ?/>)*#isU', '<ol style="list-style-type:lower-greek">$1</ol>', $texte); // liste grecque
		
		$texte = preg_replace('#&lt;puce&gt;(.+)&lt;/puce&gt;#isU', '<li>$1</li>', $texte); // puce
			$texte = preg_replace('#<ul class="puce_normale">(?:<br />)+#is', '<ul class="puce_normale">', $texte); // On enlève le retour chariot
			$texte = preg_replace('#</ul>(?:<br />)+#is', '</ul>', $texte); // On enlève le retour chariot
			$texte = preg_replace('#<ol>(?:<br />)+#is', '<ol>', $texte); // On enlève le retour chariot
			$texte = preg_replace('#</ol>(?:<br />)+#is', '</ol>', $texte); // On enlève le retour chariot
			$texte = preg_replace('#</li>(?:<br />)+#is', '</li>', $texte); // On enlève le retour chariot
		
		$texte = preg_replace('#&lt;titre1&gt;(.+)&lt;/titre1&gt;#isU', '<h2>$1</h2>', $texte); // Titre 1
			$texte = preg_replace('#</h2>(?:<br />)#isU', '</h2>', $texte); // On enlève le retour chariot
		$texte = preg_replace('#&lt;titre2&gt;(.+)&lt;/titre2&gt;#isU', '<h3>$1</h3>', $texte); // Titre 2
			$texte = preg_replace('#</h3>(?:<br />)#isU', '</h3>', $texte); // On enlève le retour chariot
		
		$texte = preg_replace('#&lt;lien=(.+)&gt;(.+)&lt;/lien&gt;#isU', '<a href="$1">$2</a>', $texte); // Lien
		$texte = preg_replace('#&lt;img=(.+)&gt;#isU', '<img src="$1"/>', $texte); // Image
		$texte = preg_replace('#&lt;img lien=&quot;(.+)&quot; alt=&quot;(.+)&quot ?/?;&gt;#isU', '<img src="$1" alt="$2"/>', $texte); // Image
		$texte = preg_replace('#&lt;img lien=&quot;(.+)&quot; alt=&quot;(.+)&quot; flottant=gauche ?/?&gt;#isU', '<img src="$1" alt="$2" class="flottantGauche"/>', $texte); // Image
		$texte = preg_replace('#&lt;img lien=&quot;(.+)&quot; alt=&quot;(.+)&quot; flottant=droit ?/?&gt;#isU', '<img src="$1" alt="$2" class="flottantDroit"/>', $texte); // Image
		
		$texte = preg_replace('#&lt;!flottant&gt;#isU', '<span class="flottantArret"/></span>', $texte); // Arrêt flottant
		
		//couleurs
		$texte = preg_replace('#&lt;couleur=rouge&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:red;">$1</span>', $texte); // rouge
		$texte = preg_replace('#&lt;couleur=orange&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:orange;">$1</span>', $texte); // orange
		$texte = preg_replace('#&lt;couleur=vert&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:green;">$1</span>', $texte); // vert
		$texte = preg_replace('#&lt;couleur=noir&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:black;">$1</span>', $texte); // noir
		$texte = preg_replace('#&lt;couleur=white&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:white;">$1</span>', $texte); // blanc
		$texte = preg_replace('#&lt;couleur=bleu&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:blue;">$1</span>', $texte); // bleu
		$texte = preg_replace('#&lt;couleur=jaune&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:yellow;">$1</span>', $texte); // jaune
		$texte = preg_replace('#&lt;couleur=gris&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:gray;">$1</span>', $texte); // gris
		$texte = preg_replace('#&lt;couleur=argent&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:silver;">$1</span>', $texte); // argent
		$texte = preg_replace('#&lt;couleur=marron&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:maroon;">$1</span>', $texte); // marron
		$texte = preg_replace('#&lt;couleur=fuchsiae&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:fuchsia;">$1</span>', $texte); // fuchsia
		$texte = preg_replace('#&lt;couleur=pourpre&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:purple;">$1</span>', $texte); // pourpre
		$texte = preg_replace('#&lt;couleur=violet&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:violet;">$1</span>', $texte); // violet
		$texte = preg_replace('#&lt;couleur=aqua&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:aqua;">$1</span>', $texte); // aqua
		$texte = preg_replace('#&lt;couleur=bleu_marine&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:navy;">$1</span>', $texte); // bleu marine
		$texte = preg_replace('#&lt;couleur=vert_fluo&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:lime;">$1</span>', $texte); // vert fluo
		$texte = preg_replace('#&lt;couleur=olive&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:olive;">$1</span>', $texte); // olive
		$texte = preg_replace('#&lt;couleur=bleu_vert&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:teal;">$1</span>', $texte); // bleu vert
		
		
		$texte = preg_replace('#&lt;ligne/&gt;#isU', '<hr/>', $texte); // ligne
		$texte = preg_replace('#&lt;efi/&gt;#isU', '&#8239;', $texte); // espace fine insécable
		$texte = preg_replace('#&lt;ei/&gt;#isU', '&nbsp;', $texte); // espace insécable
		
			
		$texte = preg_replace('#&lt;position=gauche&gt;(.+)&lt;/position&gt;#isU', '<div class="agauche">$1</div>', $texte); // Texte aligné à gauche
		$texte = preg_replace('#&lt;position=centree?&gt;(.+)&lt;/position&gt;#isU', '<div class="centre">$1</div>', $texte); // Texte centré
		$texte = preg_replace('#&lt;position=droite&gt;(.+)&lt;/position&gt;#isU', '<div class="adroite">$1</div>', $texte); // Texte aligné à gauche
		$texte = preg_replace('#&lt;position=justifiee&gt;(.+)&lt;/position&gt;#isU', '<div class="justifiee">$1</div>', $texte); // Texte justifié
		
		
		// Orthotypographie
		$texte = preg_replace('#« #isU', '«&#8239;', $texte); // guillemet
		$texte = preg_replace('# »#isU', '&#8239;»', $texte); // guillemet
		$texte = preg_replace('# :#isU', '&#8239;:', $texte); // deux-pts
		$texte = preg_replace('# ;#isU', '&#8239;;', $texte); // point-virgule
		$texte = preg_replace('# !#isU', '&#8239;!', $texte); // point d'exclamation
		$texte = preg_replace('# \?#isU', '&#8239;?', $texte); // point d'interrogation
		$texte = preg_replace('# %#isU', '&#8239;%', $texte); // pourcentage
		
		return $texte;
	}
}