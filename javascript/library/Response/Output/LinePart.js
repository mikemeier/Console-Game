function ConsoleResponseOutputLinePart(string, classes){
	
	this.string		= string;
	this.classes	= classes;
	
	this.getString = function(){
		return this.string;
	}
	
	this.getClasses = function(){
		return this.classes;
	}

}