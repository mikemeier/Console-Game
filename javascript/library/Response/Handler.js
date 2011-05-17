function ConsoleResponseHandler(output){

	this.output = output;

	this.handle = function(Response){
		var output = '';
		var Lines	= Response.getLines();
		for(var i = 0; i < Lines.length; i++){
			output		+= '<div class="line">';
			var Line	= Lines[i];
			var Parts	= Line.getParts();
			for(var j = 0; j < Parts.length; j++){
				var Part = Parts[j];
				output += '<span class="part '+ Part.getClasses().join(' ') +'">'+ Part.getString().toHTML() +'</span>';
			}
			output += '</div>';
		}
		this.output.innerHTML += output;
		if(output)
			this.output.scrollTop = 100000000000;
		return output;
	}

}