function ConsoleTransportProtocolWebSocket(Manager){

	ConsoleTransportProtocolBase.call(this, Manager);

	this.socket = null;

	this.concreteEstablish = function(Request, Response){
		var address = Request.getParameter(1);
		if(!address){
			Response.writeLine('Need valid address', ['error']);
			return true;
		}
		
		try {
			Response.writeLine('Trying '+ address +'...', ['info']);
			var socket = new WebSocket('ws://' + address);
			this.Manager.disableInput(true);
		}catch(exception){
			this.Manager.switchToAjax();
			Response.writeLine('Error on establish connection', ['error']);
			return false;
		}
		
		socket.onopen		= this.bind(this.onOpen);
		socket.onmessage	= this.bind(this.onSocketMessage);
		socket.onclose		= this.bind(this.onClose);

		this.socket = socket;
		return true;
	}

	this.onSocketMessage = function(event){
		this.onMessage(event.data);
	}

	this.concreteSend = function(Request, Response){
		
	}

	this.concreteClose = function(Request, Response){
		this.socket.close();
		this.onClose(Response);
	}

}