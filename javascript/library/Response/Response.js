function ConsoleResponse(string, classes){

	this.Lines = [];

	this.addLine = function(Line){
		this.Lines.push(Line);
	}

	this.writeLine = function(string, classes){
		if(!classes)
			var classes = [];
		this.addLine(
			new ConsoleResponseOutputLine(string, classes)
		);
	}

	if(string)
		this.writeLine(string, classes);

	this.getLines = function(){
		return this.Lines;
	}

}