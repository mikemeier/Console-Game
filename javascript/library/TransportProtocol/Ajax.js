
function ConsoleTransportProtocolAjax(Manager){

	ConsoleTransportProtocolBase.call(this, Manager);

	this.Ajax = null;

	/**
	 * @todo Timer starten
	 */
	this.concreteEstablish = function(Request, Response){
		var address = Request.getParameter(1);
		if(!address){
			Response.writeLine('Need valid address', ['error']);
			return true;
		}

		var Ajax = this.getXmlHttp();
		if(!Ajax){
			Response.writeLine('Error on establish connection', ['error']);
			return false;
		}

		Response.writeLine('Trying '+ address +'...', ['info']);
		this.Manager.disableInput(true);
		return this.concreteSend(Request, Response);
	}

	/**
	 * @todo Timer beenden
	 */
	this.concreteClose = function(Request, Response){
		var Ajax = this.getXmlHttp();
		Ajax.post(Request);
		return this.onClose(Response);
	}

	this.concreteSend = function(Request, Response){
		var Ajax = this.getXmlHttp();
		if(!Ajax){
			Response.writeLine('Error on establish connection', ['error']);
			return false;
		}
		Ajax.post(Request);
	}

	this.getXmlHttp = function(){
		if(this.Ajax != null && !this.Ajax.isUsed())
			return this.Ajax;
		var XmlHttp = false;
		if(window.ActiveXObject){
			try {
				XmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
			}catch(e){
				try {
					XmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
				}catch(e){}
			}
		}else if(window.XMLHttpRequest){
			try {
				XmlHttp = new XMLHttpRequest();
			}catch(e){}
		}
		if(XmlHttp){
			XmlHttp.onreadystatechange = this.bind(this.onReadyStateChange);
			return this.Ajax = new ConsoleTransportProtocolAjaxRequest(XmlHttp);
		}
		return false;
	}

	this.onReadyStateChange = function(Ajax){
		var Target = Ajax.currentTarget;
		if(Target.readyState == 4){
			this.onMessage(Target.responseText);
		}
	}

}