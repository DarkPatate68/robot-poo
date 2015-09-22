function selection(id)
{
	//var win = document.getElementById("container").contentWindow;
	window.top.postMessage(id,"http://"+window.location.hostname);
}

/*function getDocHeight(doc) {
    doc = doc || document;
    // stackoverflow.com/questions/1145850/
    var body = doc.body, html = doc.documentElement;
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
        html.clientHeight, html.scrollHeight, html.offsetHeight );
    return height;
}*/