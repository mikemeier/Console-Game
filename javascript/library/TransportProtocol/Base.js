function ConsoleTransportProtocolBase(Manager){

	this.Manager		= Manager;
	this.established	= false;

	this.establish = function(Request, Response){
		if(this.isEstablished())
			return true;
		return this.concreteEstablish(Request, Response);
	}

	this.isEstablished = function(){
		return this.established;
	}

	this.send = function(Request, Response){
		if(!this.isEstablished())
			return false;
		return this.concreteSend(Request, Response);
	}

	this.close = function(Request, Response){
		this.Manager.disableInput(true);
		Response.writeLine('Disconnecting...', ['info']);
		this.established = false;
		this.concreteClose(Request, Response);
	}

	this.onMessage = function(message){
		this.Manager.disableInput(false);
		this.Manager.onMessage(message);
	}

	this.onOpen = function(Response){
		Response.writeLine('Connected', ['info']);
		this.Manager.disableInput(false);
		this.established = true;
		this.Manager.onOpen(Response);
	}

	this.onClose = function(Response){
		Response.writeLine('Disconnected', ['info']);
		this.Manager.disableInput(false);
		this.established = false;
		this.Manager.onClose(Response);
	}

}