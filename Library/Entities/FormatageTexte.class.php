<?php

namespace Library\Entities;
use Library\Markdown\MarkdownExtra;


abstract class FormatageTexte
{
	static public function monoLigne($texte)
	{
		$texte = (string) $texte;
		return str_replace(array('<p>', '</p>', '<br/>', '<br />'), '', self::zCode(htmlspecialchars($texte)));
	}
	
	static public function monoLigneSansZCode($texte)
	{
		$texte = (string) $texte;
		return htmlspecialchars($texte);
	}
	
	static public function multiLigne($texte)
	{
		$texte = (string) $texte;
		return self::zCode(/*nl2br(htmlspecialchars(*/$texte/*))*/);
	}
	
	static public function zCode($texte)
	{
		/*$texte = preg_replace('#&lt;g(?:ras)?&gt;(.+)&lt;/g(?:ras)?&gt;#isU', '<strong>$1</strong>', $texte); // Gras
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
		$texte = preg_replace('#&lt;img lien=&quot;(.+)&quot; alt=&quot;(.+)&quot;&gt;#isU', '<img src="$1" alt="$2"/>', $texte); // Image
		$texte = preg_replace('#&lt;img lien=&quot;(.+)&quot; alt=&quot;(.+)&quot; flottant=gauche&gt;#isU', '<img src="$1" alt="$2" class="flottantGauche"/>', $texte); // Image
		$texte = preg_replace('#&lt;img lien=&quot;(.+)&quot; alt=&quot;(.+)&quot; flottant=droit&gt;#isU', '<img src="$1" alt="$2" class="flottantDroit"/>', $texte); // Image
		
		$texte = preg_replace('#&lt;!flottant&gt;#isU', '<span class="flottantArret"/></span>', $texte); // Arrêt flottant
		
		//couleurs
		$texte = preg_replace('#&lt;couleur=rouge&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:red;">$1</span>', $texte); // rouge
		$texte = preg_replace('#&lt;couleur=orange&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:orange;">$1</span>', $texte); // orange
		$texte = preg_replace('#&lt;couleur=vert&gt;(.+)&lt;/couleur&gt;#isU', '<span style="color:green;">$1</span>', $texte); // vert
		
		
		$texte = preg_replace('#&lt;ligne/&gt;#isU', '<hr/>', $texte); // ligne
		*/
		
	    $texte = MarkdownExtra::defaultTransform($texte);
		//$texte = $Extra->text($texte);
	    $texte = preg_replace('#<p><img(.+) alt="(.+)" /></p>#isU', '<figure class="centre"><img$1 alt="$2" /><figcaption>$2</figcaption></figure>', $texte);
	    $texte = preg_replace('#<figure class="centre"><img(.+) alt="_(.+)" /><figcaption>(.+)</figcaption></figure>#isU', '<figure class="centre"><img$1 alt="$2" /></figure>', $texte);
	    
	    $texte = preg_replace('#<figure class="centre"><img src="(.+)" alt="CAO_" /></figure>#isU', '<div class="bloc-CAO"><div class="icone-CAO icon-codepen"></div><span class="txt-CAO"><strong>Une
	     pièce CAO a été attachée, cliquez sur le lien ci-dessous pour la visualiser</strong><br/>
	    		<span class="icon-link"> </span><a href="' . $GLOBALS['PREFIXE'] . '/app/creo/index.php?fichier=$1" target="_blank">$1</a></span></div>', $texte);
	    
	    $texte = preg_replace('#<blockquote>(.+)(?:Source(: .+))?(</p>)?</blockquote>#isU', '<blockquote><div class="auteur_citation"><span>Citation <strong>$2</strong></span></div>$1$3</blockquote>', $texte);
	    
	    $texte = preg_replace('#<code class="(.+)">#isU', '<code class="language-$1">', $texte);
	    $texte = preg_replace('#<code class="language-html">#isU', '<code class="language-markup">', $texte);
	    $texte = preg_replace('#<pre><code class="language-(.+)">#isU', '<pre class="line-numbers"><code class="language-$1">', $texte);
	    
	    $texte = preg_replace('#~~(.+)~~#isU', '<del>$1</del>', $texte);
	    $texte = preg_replace('#\|\|(.+)\|\|#isU', '<kbd>$1</kbd>', $texte);
	    $texte = preg_replace('#\^(.+)\^#isU', '<sup>$1</sup>', $texte);
	    $texte = preg_replace('#~(.+)~#isU', '<sub>$1</sub>', $texte);
	    
	    $texte = preg_replace('#<p>->(.+)&lt;-</p>#isU', '<p class="centre">$1</p>', $texte);
	    $texte = preg_replace('#<p>->(.+)-></p>#isU', '<p class="adroite">$1</p>', $texte);
	    $texte = preg_replace('#<p>&lt;-(.+)&lt;-</p>#isU', '<p class="agauche">$1</p>', $texte);
	    
	    
	    $texte = preg_replace('#&lt;efi/>#isU', '&#8239;', $texte); // espace fine insécable
		$texte = preg_replace('#« #isU', '«&#8239;', $texte); // guillemet
		$texte = preg_replace('# »#isU', '&#8239;»', $texte); // guillemet
		$texte = preg_replace('# :#isU', '&#8239;:', $texte); // deux-pts
		$texte = preg_replace('# !#isU', '&#8239;!', $texte); // point d'exclamation
		$texte = preg_replace('# \?#isU', '&#8239;?', $texte); // point d'interrogation
		
		/*$texte = preg_replace('#&lt;position=gauche&gt;(.+)&lt;/position&gt;#isU', '<div class="agauche">$1</div>', $texte); // Texte aligné à gauche
		$texte = preg_replace('#&lt;position=centre&gt;(.+)&lt;/position&gt;#isU', '<div class="centre">$1</div>', $texte); // Texte centré
		$texte = preg_replace('#&lt;position=droite&gt;(.+)&lt;/position&gt;#isU', '<div class="adroite">$1</div>', $texte); // Texte aligné à gauche*/
		
		
		
		return $texte;
	}
}