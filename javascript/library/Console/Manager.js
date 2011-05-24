function ConsoleManager(input, output, body, allowSocket){
	this.input				= input;
	this.output				= output;
	this.body				= body;
	this.allowSocket		= allowSocket;

	this.TransportProtocol;
	this.RequestHandler;
	this.ResponseHandler;
	
	this.lastKeyCode		= null;
	this.lastKeyCodeTime	= null;

	this.init = function(){
		this.setRequestHandler();
		this.ResponseHandler	= new ConsoleResponseHandler(this.output);
		this.input.onkeydown	= this.bind(this.inputOnKeyDown);
		this.body.onclick		= this.bind(this.bodyOnClick);
		this.focusInput();
	}

	this.switchToAjax = function(){
		this.allowSocket = false;
		this.init();
	}

	this.clearConsoleOutput = function(){
		this.output.innerHTML = '';
	}

	this.isSocketAllowed = function(){
		return this.allowSocket;
	}

	this.focusInput = function(){
		this.input.focus();
	}

	this.disableInput = function(bool){
		this.input.disabled = bool;
		this.focusInput();
	}

	this.setRequestHandler = function(){
		var TransportProtocolFactory	= new ConsoleTransportProtocolFactory(this);
		this.TransportProtocol			= TransportProtocolFactory.getTransportProtocol();
		var RequestParser				= new ConsoleRequestParser();
		this.RequestHandler				= new ConsoleRequestHandler(this, this.TransportProtocol, RequestParser);
	}

	this.sendCommand = function(command){
		var Response = this.RequestHandler.handle(command);
		return this.ResponseHandler.handle(Response);
	}

	this.onOpen = function(Response){
		console.log('onOpen');
		this.disableInput(false);
	}

	this.onClose = function(Response){
		console.log('onClose');
		this.disableInput(false);
	}

	this.onMessage = function(message){
		console.log('onMessage');
		this.disableInput(false);
		this.handleMessage(message);
	}

	this.clearInput = function(){
		this.input.value = '';
	}

	this.bodyOnClick = function(event){
		this.focusInput();
	}

	this.inputOnKeyDown = function(e){
		var keyCode = (window.event) ? e.which : e.keyCode;
		if(keyCode == 13 && !this.input.disabled){
			var command = this.input.value.trim();
			if(command != ''){
				this.clearInput();
				this.sendCommand(command);
			}
		//Key-Up --> last Command
		}else if(keyCode == 38){
			var lastCommand = this.RequestHandler.getLastRequestString();
			if(lastCommand)
				this.input.value = lastCommand;
		//Key-Down
		}else if(keyCode == 40){
			var nextCommand = this.RequestHandler.getNextRequestString();
			if(nextCommand)
				this.input.value = nextCommand;
		//Ctrl+C -> break
		}else if(this.isDoubleKeyCode(17, 67, keyCode)){
			this.sendCommand('break');
		}
		this.setLastKeyCode(keyCode);
		
	}

	this.handleMessage = function(message){
		try {
			var json = JSON.parse(message);
		}catch(e){
			throw "couldnt parse message";
		}
		console.debug(json);
		var Response	= new ConsoleResponse();
		var tmpLines	= json.data.lines;
		for(var i=0;i<tmpLines.length;i++){
			var tmpLine		= tmpLines[i];
			var tmpParts	= tmpLine.parts;
			var Line		= new ConsoleResponseOutputLine();
			for(var j=0;j<tmpParts.length;j++){
				var tmpPart = tmpParts[j];
				Line.addPart(tmpPart.string, tmpPart.classes);
			}
			Response.addLine(Line);
		}
		this.handleContainer(json.container, Response);
		this.ResponseHandler.handle(Response);
	}

	this.handleContainer = function(container, Response){
		if(container.isConnected)
			this.TransportProtocol.established = container.isConnected;
		if(container.closeConnection == true)
			this.TransportProtocol.onClose(Response);
		if(container.openConnection == true)
			this.TransportProtocol.onOpen(Response);
	}
	
	this.setLastKeyCode = function(keyCode){
		this.lastKeyCode		= keyCode;
		this.lastKeyCodeTime	= new Date();
	}
	
	this.isDoubleKeyCode = function(code1, code2, keyCode){
		return (this.lastKeyCode == code1 && keyCode == code2 && (new Date() - this.lastKeyCodeTime <= 500));
	}
	
}