function ConsoleRequest(RequestParser, requestString){
	this.requestParser = RequestParser;
	this.requestString = requestString;

	var parsed		= this.requestParser.parse(requestString);
	this.command	= parsed.command;
	this.parameters = parsed.parameters;

	this.getRequestString = function(){
		return this.requestString;
	}

	this.getCommand = function(){
		return this.command;
	}

	this.getParameters = function(){
		return this.parameters;
	}

	this.getParameter = function(number){
		if(this.parameters[number])
			return this.parameters[number];
		return null;
	}

}