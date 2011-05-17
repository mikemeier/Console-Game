function ConsoleTransportProtocolAjaxRequest(XmlHttp) {

	this.Ajax				= XmlHttp;
	this.used				= false;

	this.post = function(Request){
		this.Ajax.open('POST', 'request.php?format=json', true);
		this.Ajax.setRequestHeader('Content-Type',		'application/x-www-form-urlencoded');
		this.Ajax.setRequestHeader('X-Requested-With',	'ConsoleTransportProtocolAjaxRequest');
		this.Ajax.send('q='+escape(Request.getRequestString()));
		this.used = true;
	}

	this.isUsed = function(){
		return this.used;
	}

}