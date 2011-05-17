String.prototype.trim = function(){
	var whitespace	= " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
	var l			= 0;
	var i			= 0;
	var str			= this + '';
	l = str.length;
	for(i = 0; i < l; i++){
		if(whitespace.indexOf(str.charAt(i)) === -1){
			str = str.substring(i);
			break;
		}
	}
	l = str.length;
	for(i = l - 1; i >= 0; i--){
		if (whitespace.indexOf(str.charAt(i)) === -1) {
			str = str.substring(0, i + 1);
			break;
		}
	}
	return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}
String.prototype.ucfirst = function(){
	var str = this + '';
    return str.charAt(0).toUpperCase() + str.substr(1);
}
String.prototype.toHTML = function(){
	var quote_style		= 'ENT_QUOTES';
	var double_encode	= false;
    var optTemp = 0,
        i = 0,
        noquotes = false;
    if (typeof quote_style === 'undefined' || quote_style === null) {
        quote_style = 2;
    }
    var string = this + '';
    if (double_encode !== false) { // Put this first to avoid double-encoding
        string = string.replace(/&/g, '&amp;');
    }
    string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');

    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE': 1,
        'ENT_HTML_QUOTE_DOUBLE': 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE': 4
    };
    if (quote_style === 0) {
        noquotes = true;
    }
    if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
        quote_style = [].concat(quote_style);
        for (i = 0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;
            }
            else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }
        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/'/g, '&#039;');
    }
    if (!noquotes) {
        string = string.replace(/"/g, '&quot;');
    }

    return string;
}
Object.prototype.bind = function(method){
	var object	= this;
	return function(){
        method.apply(object, arguments);
    };
}
Object.prototype.extend = function(){
   var Obj = function(){};
   Obj.prototype = this;
   return new Obj();
}