// JavaScript Document

var _GLOBAL = new Array();
var Dashboard = (opener ? opener.Dashboard : (top.window.dashboard || {
  reports     : [],
  dictionary  : [] 
}));
if (Dashboard.dictionary) 
	 Dashboard.dictionary = (dictionary != null ? dictionary : []);

var FF = {
	// On load
	ClickActions: new Array(),
	
	initPage: function(){
		
		// Azzero i report mostrati. Ad ogni refresh devo ripartire da zero
		Dashboard.reports = $A(Dashboard.reports);
		Dashboard.reports.clear();
		
		// Applico la configurazione dell'interfaccia
		if(FF.UI)
			FF.UI.setInterface();
			
		// Controllo se ci sono report da far vedere. Se ci sono li mostro
		if (Dashboard.reportsToShow.length > 0) {
			Dashboard.reportsToShow.each(function(r){
				FF.report(r.text, r.type);
			});
			Dashboard.reportsToShow.clear();
		}
	},
	
	log: function(t){
		new Ajax.Request('inc/ajaxRequests.php?action=_log&logText=' + t, {
			method: 'get'
		});
	},
	
	report: function(txt, type) {
		var report = new Element('div').addClassName('report').addClassName(type).update(txt).observe('click', function() {FF.unreport(this);});
		document.body.insert(report);
		report.setStyle({
			left	: FF.UI.getCenterLayerDims(report).left + 'px',
			zIndex	: 90000 + Dashboard.reports.length
		});
		new Effect.Move(report, {y: (2 + (22 * Dashboard.reports.length)), x: FF.UI.getCenterLayerDims(report).left, mode: 'absolute'});
		Dashboard.reports.push(report);
		if(type != 'Error')
			setTimeout(function() {FF.unreport(report)}, Dashboard.config.reportDelayBeforeClose);
	},
	
	unreport: function(r)
	{
		new Effect.DropOut(r);
		Dashboard.reports = $A(Dashboard.reports).without(r);
		
	},
	
	popup: function(url, name, w, h){
		var newWindow = window.open(url, name, 'height=' + h + ',width=' + w);
		if (window.focus) 
			newWindow.focus();
	},
	
	closePageLogger: function(logElement){
		Element.hide(logElement);
		new Ajax.Request('inc/ajaxRequests.php?action=deletePageLogger', {
			method: 'get'
		});
	},
	
	saveSessionVar: function(name, value)
	{
		new Ajax.Request('inc/ajaxRequests.php?action=saveSessionVar&name=' + name + '&value=' + value);
	},
	
	getSessionVar: function (name, vari)
	{
		new Ajax.Request('inc/ajaxRequests.php?action=getSessionVar&name=' + name, {
			onComplete: function(res) {
				vari = res.responseText.strip();
			}
		});
	},
	
	throwError: function(e)
	{
		var error = FF.UI.showMessage({
			css	: {background: 'red', color: '#fff'},
			text: e
		});
	},
	
	debug: function(t)
	{
		if($('debug'))
			$('debug').innerHTML += t;
	}
	
}

function loadScript(url, callback){
	var script = document.createElement("script")
	script.type = "text/javascript";
	if (script.readyState) { //IE
		script.onreadystatechange = function(){
			if (script.readyState == "loaded" || script.readyState == "complete") {
				script.onreadystatechange = null;
				callback();
			}
		};
	}
	else { //Others
		script.onload = function(){
			callback();
		};
		script.src = url;
		document.getElementsByTagName("head")[0].appendChild(script);
	}
}



function externalCall(f) {
	eval(f);
}

document.observe("dom:loaded", function() {
	FF.initPage();
});
