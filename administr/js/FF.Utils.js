/*
* Written by Paolo Moretti - littlebrown@gmail.com
*/

var DateUtils = {
	
	weekDays : new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'),
	months   : new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
	monthDays: new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31),
	
	clone: function()  // Return a clone of the date
	{
		return new Date(this.getTime());
	},
	
	equal: function() // check if date is equal to "d", Argument 2 = true: equal in millisecond
	{
		return (arguments[1] ? this.getTime() == arguments[0].getTime() : (
			arguments[0].getDate() == this.getDate() && 
			arguments[0].getMonth() == this.getMonth() && 
			arguments[0].getFullYear() == this.getFullYear()
		));
	},
	
	greater: function()  // Check if the date is grater than argument[0], Argument 2 = true: in millisecond
	{
		return (arguments[1] ? this.getTime() > arguments[0].getTime() : (
			arguments[0].getFullYear() > this.getFullYear() || 
			(arguments[0].getFullYear() == this.getFullYear() && arguments[0].getMonth() > this.getMonth()) || 
			(arguments[0].getFullYear() == this.getFullYear() && arguments[0].getMonth() == this.getMonth() && arguments[0].getDate() > this.getDate())		
		));
	},
	
	smaller: function()  // Check if the date is smaller than argument[0], Argument 2 = true: in millisecond
	{
		return (arguments[1] ? !this.greater(arguments[0], arguments[1]) : !this.greater(arguments[0]));
	},
	
	next: function() // Return a future date based on month, weekday, days
	{
	  var args = arguments;
		if (typeof args[0] == 'string' && (this.months.indexOf(args[0].capitalize()) > -1 || args[0] == 'month')) 
        return new Date(this.getFullYear(), this.getMonth() + this.findMonth(args, 'next'), this.getDate());
    else if (typeof args[0] == 'string' && $this.weekDays.indexOf(args[0].capitalize()) > -1) 
				return new Date(this.getFullYear(), this.getMonth(), this.getDate() + this.findWeekDay(args, 'next'));
		else 
		    return args[0] || 1;
	},
	
	prev: function() // Return a past date based on month, weekday, days
	{
		var args = arguments;
		if (typeof args[0] == 'string' && (this.months.indexOf(args[0].capitalize()) > -1 || args[0] == 'month')) 
        return new Date(this.getFullYear(), this.getMonth() - this.findMonth(args, 'prev'), this.getDate());
    else if (typeof args[0] == 'string' && $this.weekDays.indexOf(args[0].capitalize()) > -1) 
				return new Date(this.getFullYear(), this.getMonth(), this.getDate() - this.findWeekDay(args, 'prev'));
		else 
		    return args[0] || 1;
	},
	
	findWeekDay: function(args, fn)  // Internal
	{
		var d = this.clone();
		for (var i=1; i <= 7; i++) {
			d = d[fn]();
			if(this.weekDays[d.getDay()] == args[0].capitalize())
				return (args[1] ? (args[1] * 7 + i) : i);
		}
		return;
	},
	
	findMonth: function(args, fn)  // Internal
	{
	 	var d = this.clone();
	 	var $this = this;
		return (args[0] == 'month' ? 1 : (function() {
			for (var i=1; i <= 12; i++) {
				d = d[fn]('month');
				if($this.months[d.getMonth()] == args[0].capitalize()) 
					return (args[1] ? (args[1] * 12 + i) : i);
			}
		})());
	},
	
	length: function() // return the month day count for the current date 
	{
	   if(this.isLead()) 
        this.monthDays[1] = 29;
     return (this.monthDays[this.getMonth()]);
  },
	
	first: function() // return the date of the first day of the month
	{
     return (new Date(this.clone().setDate(1)));
  },
	
	last: function()
	{
     return (new Date(this.clone().setDate(this.length())));
  },
	
	isLead: function() // Return true if lead year
	{
	   return (new Date(this.getFullYear(), 1, 29).getDate() == 29);
  },
	
	format: function()	// passing a Template with vars: day, month, year, sep, it returns the parsed date
	{
		return arguments[0].evaluate({day: this.getDate(), month: (this.getMonth()+1), year: this.getFullYear(), sep: (arguments[1] || '-')});
	}
	
}
Object.extend(Date.prototype, DateUtils);

FF.Utils = {
	
	checkDataFormat: function(format, f) {
		switch (format) {
			case "NUMERIC": 
				if(isNaN(f.value)) {
					alert('Questo campo deve essere numerico');
					f.focus();
					f.style.border = '2px solid red';
				} else {
					f.style.border = '1px solid #333';
				}
				break;
			case "DATE": 
				var dates = f.value.split("-");
				if(dates.length != 3 || dates[0].length != 4 || dates[1].length != 2 || dates[2].length != 2) {
					alert('Questo campo deve essere nel formato: AAAA-MM-GG');
					f.focus();
					f.style.border = '2px solid red';
				} else {
					f.style.border = '1px solid #333';
				}
		}
	},
	
	rebuildHostUrl: function(url)
	{
		var host = Dashboard.docroot;
		return url.replace(host, '') + '/';
	},
	
	getLocationAndAppend: function(append, param) {
		var loc = window.location.href;
		if(loc.endsWith('#')) loc = loc.truncate(loc.length-1, '');
		if (param == true) {
			if (loc.indexOf('?') > -1) 
				return loc + '&' + append;
			else
				return loc + '?' + append;
		}
		if(append != null) return loc+append;
	},
	
	getSizeByNumber: function(n) 
	{
		if(n < 1000) 
			return n + ' bytes';
		else if(n >= 1000 && n < 1000000)
			return n + ' Kb';
		else if(n >= 1000000 && n < 1000000000)
			return n + ' Mb';
	},
	
	saveTextareas: function()
	{
		//nicEditors
		for ( var i=0; i<$$("textarea").length; i++) {
			nicEditors.findEditor($$("textarea")[i].identify()).saveContent();
		}
	}
	
}