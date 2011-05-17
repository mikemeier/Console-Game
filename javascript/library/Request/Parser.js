function ConsoleRequestParser(){

	this.parse = function(string){
		var parsed		= {};
		var splitted	= string.split(" ");

		parsed.command	= splitted[0];
		delete splitted[0];
		parsed.parameters	= splitted;
		return parsed;
	}

}