var script = document.createElement('script');
script.src = 'http://code.jquery.com/jquery.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

function loadit( element)
{
	$("#container").load(element.rel);
	
	var tabs=document.getElementById('tabs').getElementsByTagName("a");
	var tabsLi=document.getElementById('tabs').getElementsByTagName("li");
	for (var i=0; i < tabs.length; i++)
	{
		if(tabs[i].rel == element.rel) 
			tabsLi[i].className="mui-active";
		else
			tabsLi[i].className="";
	}
}

function startit()
{
	var tabs=document.getElementById('tabs').getElementsByTagName("a");
	$("#container").load(tabs[0].rel);

	var tabsLi=document.getElementById('tabs').getElementsByTagName("li");
	tabsLi[0].className="mui-active";
}


/*function loadit( element)
{
	var container = document.getElementById('container');
	container.src=element.rel;

	var tabs=document.getElementById('tabs').getElementsByTagName("a");
	var tabsLi=document.getElementById('tabs').getElementsByTagName("li");
	for (var i=0; i < tabs.length; i++)
	{
		if(tabs[i].rel == element.rel) 
			tabsLi[i].className="mui-active";
		else
			tabsLi[i].className="";
	}
	
	//setIframeHeight("container");
}*/

/*function startit()
{
	var tabs=document.getElementById('tabs').getElementsByTagName("a");
	var container = document.getElementById('container');
	container.src = tabs[0].rel;

	var tabsLi=document.getElementById('tabs').getElementsByTagName("li");
	tabsLi[0].className="mui-active";;
}*/

window.onload=startit;

var selection = (function() {
	var selectionne = new Array();
    return function(id) {
    	if(in_array(id, selectionne) !== false)
    		selectionne.splice(selectionne.indexOf(id),1);
    	else
    		selectionne.push(id);
    	
    	var valeur = "none";
    	if(selectionne.length > 0)
    	{
    		$('.disparait').fadeIn('def');
    		valeur = "inline-block";
    	}
    	else
    	{
    		$('.disparait').fadeOut('def');
    		valeur = "none";
    	}
    };
})();


/*function setIframeHeight(id) {
    var ifrm = document.getElementById(id);
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
    ifrm.style.visibility = 'hidden';
    ifrm.style.height = "10px"; // reset to minimal height ...
    // IE opt. for bing/msn needs a bit added or scrollbar appears
    ifrm.style.height = getDocHeight( doc ) + 4 + "px";
    alert(getDocHeight( doc ) + 4 + "px");
    ifrm.style.visibility = 'visible';
}

function getDocHeight(doc) {
    doc = doc || document;
    // stackoverflow.com/questions/1145850/
    var body = doc.body, html = doc.documentElement;
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
        html.clientHeight, html.scrollHeight, html.offsetHeight );
    return height;
}*/

//document.getElementById('container').onload = setIframeHeight('container');

/*function listener(event)
{
	if (event.origin !== "http://localhost" )
		return;
	
	var selectionne = new Array();

	document.getElementById("test").innerHTML = "received: "+event.data
}*/

var listener = (function() {
	var selectionne = new Array();

	return function(event) {
		if (event.origin !== ("http://"+window.location.hostname))
			return;
		
		var message = event.data.split("_");
		switch(message[0])
		{
			case "reception":
				if(in_array(event.data, selectionne) !== false)
					selectionne.splice(selectionne.indexOf(event.data),1);
				else
					selectionne.push(event.data);
				
				var valeur = "none";
				if(selectionne.length > 0)
				{
					$('.disparait').fadeIn('def');
					valeur = "inline-block";
				}
				else
				{
					$('.disparait').fadeOut('def');
					valeur = "none";
				}
				break;
				
		}
	};
})();



if (window.addEventListener){
addEventListener("message", listener, false)
} else {
attachEvent("onmessage", listener)
}

// -------- BIBLIOTHÈQUE --------------
function in_array(needle, haystack, argStrict) {
	  //  discuss at: http://phpjs.org/functions/in_array/
	  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // improved by: vlado houba
	  // improved by: Jonas Sciangula Street (Joni2Back)
	  //    input by: Billy
	  // bugfixed by: Brett Zamir (http://brett-zamir.me)
	  //   example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
	  //   returns 1: true
	  //   example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
	  //   returns 2: false
	  //   example 3: in_array(1, ['1', '2', '3']);
	  //   example 3: in_array(1, ['1', '2', '3'], false);
	  //   returns 3: true
	  //   returns 3: true
	  //   example 4: in_array(1, ['1', '2', '3'], true);
	  //   returns 4: false

	  var key = '',
	    strict = !! argStrict;

	  //we prevent the double check (strict && arr[key] === ndl) || (!strict && arr[key] == ndl)
	  //in just one for, in order to improve the performance 
	  //deciding wich type of comparation will do before walk array
	  if (strict) {
	    for (key in haystack) {
	      if (haystack[key] === needle) {
	        return true;
	      }
	    }
	  } else {
	    for (key in haystack) {
	      if (haystack[key] == needle) {
	        return true;
	      }
	    }
	  }

	  return false;
	}

function array_search(needle, haystack, argStrict) {
	  //  discuss at: http://phpjs.org/functions/array_search/
	  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  //    input by: Brett Zamir (http://brett-zamir.me)
	  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  //  depends on: array
	  //        test: skip
	  //   example 1: array_search('zonneveld', {firstname: 'kevin', middle: 'van', surname: 'zonneveld'});
	  //   returns 1: 'surname'
	  //   example 2: ini_set('phpjs.return_phpjs_arrays', 'on');
	  //   example 2: var ordered_arr = array({3:'value'}, {2:'value'}, {'a':'value'}, {'b':'value'});
	  //   example 2: var key = array_search(/val/g, ordered_arr); // or var key = ordered_arr.search(/val/g);
	  //   returns 2: '3'

	  var strict = !! argStrict,
	    key = '';

	  if (haystack && typeof haystack === 'object' && haystack.change_key_case) { // Duck-type check for our own array()-created PHPJS_Array
	    return haystack.search(needle, argStrict);
	  }
	  if (typeof needle === 'object' && needle.exec) { // Duck-type for RegExp
	    if (!strict) { // Let's consider case sensitive searches as strict
	      var flags = 'i' + (needle.global ? 'g' : '') +
	        (needle.multiline ? 'm' : '') +
	        (needle.sticky ? 'y' : ''); // sticky is FF only
	      needle = new RegExp(needle.source, flags);
	    }
	    for (key in haystack) {
	      if (needle.test(haystack[key])) {
	        return key;
	      }
	    }
	    return false;
	  }

	  for (key in haystack) {
	    if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
	      return key;
	    }
	  }

	  return false;
	}