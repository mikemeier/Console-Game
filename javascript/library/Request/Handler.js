function ConsoleRequestHandler(Manager, TransportProtocol, RequestParser){

	this.Manager			= Manager;
	this.TransportProtocol	= TransportProtocol;
	this.RequestParser		= RequestParser;

	this.requestStringArchiv			= [];
	this.requestStringArchivPosition	= -1;

	this.Request	= null;
	this.Response	= null;

	this.handle = function(requestString){
		var Request		= new ConsoleRequest(this.RequestParser, requestString);
		var command		= Request.getCommand();
		var Response	= new ConsoleResponse(Request.getRequestString(), ['command']);

		var handleCommand		= 'handle' + command.ucfirst();
		var handleCommandReturn = false;
		if(this[handleCommand])
			var handleCommandReturn = this[handleCommand](Request, Response);
		if(handleCommandReturn == false)
			this.sendWithTransportProtocol(Request, Response);

		this.Request	= Request;
		this.Response	= Response;
		
		this.requestStringArchiv.push(requestString);
		this.requestStringArchivPosition = -1;

		return Response;
	}
	
	this.getLastRequestString = function(){
		var position = this.requestStringArchiv.length + this.requestStringArchivPosition;
		if(this.requestStringArchiv[position]){
			this.requestStringArchivPosition--;
			return this.requestStringArchiv[position];
		}
		return false;
	}
	
	this.getNextRequestString = function(){
		var position = this.requestStringArchiv.length + this.requestStringArchivPosition + 1;
		if(this.requestStringArchiv[position]){
			this.requestStringArchivPosition++;
			return this.requestStringArchiv[position];
		}
		return false;
	}

	this.sendWithTransportProtocol = function(Request, Response){
		if(!this.TransportProtocol.isEstablished()){
			Response.writeLine('Not connected', ['error']);
			return false;
		}
		this.TransportProtocol.send(Request, Response);
	}

	this.getRequest = function(){
		return this.Request;
	}

	this.getResponse = function(){
		return this.Response;
	}
	
	this.handleBreak = function(Request, Response){
		if(this.TransportProtocol.isEstablished())
			return false;
		return true;
	}

	this.handleConnect = function(Request, Response){
		if(this.TransportProtocol.isEstablished()){
			Response.writeLine('Already connected', ['info']);
			return true;
		}
		if(TransportProtocol.establish(Request, Response))
			return true;
		return true;
	}

	this.handleDisconnect = function(Request, Response){
		if(this.TransportProtocol.isEstablished())
			return this.TransportProtocol.close(Request, Response);
		Response.writeLine('Not connected', ['info']);
	}

	this.handleCls = function(Request, Response){
		this.Manager.clearConsoleOutput();
		return true;
	}

}