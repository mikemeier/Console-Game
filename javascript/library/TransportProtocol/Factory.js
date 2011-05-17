function ConsoleTransportProtocolFactory(Manager){

	this.Manager = Manager;

	this.getTransportProtocol = function(){
		if(this.hasWebSocket())
			return new ConsoleTransportProtocolWebSocket(this.Manager);
		return new ConsoleTransportProtocolAjax(this.Manager);
	}

	this.hasWebSocket = function(){
		if(!this.Manager.isSocketAllowed())
			return false;
		return ("WebSocket" in window);
	}

}