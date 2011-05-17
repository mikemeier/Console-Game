function ConsoleResponseOutputLine(string, classes){

	this.parts = [];

	this.addPart = function(string, classes){
		this.parts.push(new ConsoleResponseOutputLinePart(string, classes));
	}

	this.addPart(
		'>>', ['path']
	);

	var time	= new Date();
	var hours	= time.getHours();
	var minutes	= time.getMinutes();
	var seconds	= time.getSeconds();
	if(hours < 10)
		hours = "0" +hours;
	if(minutes < 10)
		minutes = "0" + minutes;
	if(seconds < 10)
		seconds = "0" + seconds;
	
	this.addPart(
		hours + ':' + minutes + ':' + seconds, ['time']
	);

	if(string){
		if(!classes)
			var classes = [];
		this.addPart(string, classes);
	}

	this.getParts = function(){
		return this.parts;
	}

}