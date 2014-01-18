FF.Calendar = {
	
	Elem		: String(),
	Sep			: String('-'),
	Now			: new Date(),
	MonthName	: new Array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'),	// mn
	MonthDays	: new Array('31','28','31','30','31','30','31','31','30','31','30','31'),				// mnn
	MonthBDays	: new Array('31','29','31','30','31','30','31','31','30','31','30','31'),				// mnl
	Calvalarr	: new Array(42),
	
	/*
	var sccm=now.getMonth();
	var sccy=now.getFullYear();
	var ccm=now.getMonth();
	var ccy=now.getFullYear();
	var ccd=now.getDate();
	var updobj;
	*/
	
	runCalendar: function(ielem, sep, v) 		// EX lcs
	{
		this.Elem = ielem		// EX updobj

		if(!$('fcContainer')) 
			this.Elem.up().appendChild(new Element('div', {id: 'fcContainer'}).update(FF.Calendar.getCalendar()));
		
		this.setCalendar(this.Elem.value);
	},
	
	setCalendar: function(d) {		// EX prepcalendar(hd, cm, cy)
		
		var InputDate = d.explode(this.Sep);
		var CalDate = new Date();							// td=new Date();
		CalDate.setDate(1)									//td.setDate(1);
		CalDate.setFullYear(this.Now.getFullYear());		//td.setFullYear(cy);
		CalDate.setMonth(this.Now.getDate());				//td.setMonth(cm);
		
		// sd = this.Now.getDate();		
		//cd=td.getDay();
		
		$('mns').update(mn[InputDate[1]] + ' ' + InputDate[0]);
		var MonthArr = ((InputDate[0]%4) == 0) ? this.MonthDays : this.MonthBDays;
		for(var d = Number(1); d <= 42; d++) {
			// Setto lo stile
			$('v' + d).addClassName('CalDay');	
			if ((d >= (CalDate.getDate() -(-1))) && (d <= CalDate.getDate() - (-MonthArr[InputDate[1]]))) {
				var dip = ((d - CalDate.getDate() < this.Now.getDate()) && (InputDate[1] == this.Now.getMonth()) && (InputDate[0] == this.Now.getFullYear()));
				//var htd = ((hd!='')&&(d-cd==hd));
				/*
				if (dip)
					f_cpps($('v'+parseInt(d)));
				else 
				*/
				/*
				if (htd)
					f_hds($('v'+parseInt(d)));
				else
					f_cps($('v'+parseInt(d)));
				*/
				
				$('v'+parseInt(d)).onmouseover = function() {this.style.background = '#FFCC66'}
				$('v'+parseInt(d)).onmouseout = function() {this.style.background = '#C4D3EA'}
				$('v'+parseInt(d)).onclick = function() {
					var res_date = FF.Calendar.Calvalarr[this.id.substring(1, this.id.length)];
					res_date = res_date.split(FF.Calendar.Sep)[2] + '-' + res_date.split(FF.Calendar.Sep)[1] + '-' + res_date.split(FF.Calendar.Sep)[0];
					FF.Calendar.Elem.value = res_date;
					Element.remove('fcContainer')
				}
				
				$('v' + d).update(d-cd);	
				FF.Calendar.Calvalarr[d] = '' + (d.getDate() - cd) + FF.Calendar.Sep + (d.getMonth() -(-1)) + FF.Calendar.Sep + d.getFullYear();
			}
			else {
				$('v' + d).update('&nbsp;');
				$('v' + d).onmouseover=null;
				$('v' + d).onmouseout=null;
				$('v' + d).style.cursor='default';
			}
		}
	},
	
	getCalendar: function() {
		var Cal = '<table id="fc" cellpadding="2" style="border-collapse:collapse;background:#FFFFFF;border:1px solid #ABABAB;"><tbody>' + 
		'<tr><td style="cursor:pointer !important" onclick="csubm()"><img src="js/libs/arrowleftmonth.gif"></td><td colspan=5 id="mns" align="center" style="font:bold 13px Arial"></td><td align="right" style="cursor:pointer" onclick="caddm()"><img src="js/libs/arrowrightmonth.gif"></td></tr>' + 
		'<tr><td align=center style="background:#ABABAB;font:12px Arial">S</td><td align=center style="background:#ABABAB;font:12px Arial">M</td><td align=center style="background:#ABABAB;font:12px Arial">T</td><td align=center style="background:#ABABAB;font:12px Arial">W</td><td align=center style="background:#ABABAB;font:12px Arial">T</td><td align=center style="background:#ABABAB;font:12px Arial">F</td><td align=center style="background:#ABABAB;font:12px Arial">S</td></tr>';
		for (var kk = 1; kk <= 6; kk++) {
			Cal += '<tr>';
			for (var tt = 1; tt <= 7; tt++) {
				num = 7 * (kk - 1) - (-tt);
				Cal += '<td id="v' + num + '" style="width:18px;height:18px">&nbsp;</td>';
			}
			Cal += '</tr>';
		}
		Cal += '</tbody></table>';
		return Cal;
	}
	
}





function getCalendar () {
	var Cal = '<table id="fc" cellpadding=2><tbody>' + 
	'<tr><td onclick="csubm()"><img src="js/libs/arrowleftmonth.gif"></td><td colspan=5 id="mns" align="center" style="font:bold 13px Arial"></td><td align="right" style="cursor:pointer" onclick="caddm()"><img src="js/libs/arrowrightmonth.gif"></td></tr>' + 
	'<tr><td align=center style="background:#ABABAB;font:12px Arial">S</td><td align=center style="background:#ABABAB;font:12px Arial">M</td><td align=center style="background:#ABABAB;font:12px Arial">T</td><td align=center style="background:#ABABAB;font:12px Arial">W</td><td align=center style="background:#ABABAB;font:12px Arial">T</td><td align=center style="background:#ABABAB;font:12px Arial">F</td><td align=center style="background:#ABABAB;font:12px Arial">S</td></tr>';
	for (var kk = 1; kk <= 6; kk++) {
		Cal += '<tr>';
		for (var tt = 1; tt <= 7; tt++) {
			num = 7 * (kk - 1) - (-tt);
			Cal += '<td id="v' + num + '" style="width:18px;height:18px">&nbsp;</td>';
		}
		Cal += '</tr>';
	}
	Cal += '</tbody></table>';
	return Cal;
}
FF.EventManager.addDocumentClickAction('if(Event.element(e) && Element.ancestors(Event.element(e)).indexOf($("fc")) == -1) Element.remove("fcContainer")');

/*
Event.observe(document.body, 'click', function() {
	checkClick();
});
*/
//document.all?document.attachEvent('onclick',checkClick):document.addEventListener('click',checkClick,false);


// Calendar script
var now = new Date();
var sccm=now.getMonth();
var sccy=now.getFullYear();
var ccm=now.getMonth();
var ccy=now.getFullYear();
var ccd=now.getDate();
var updobj;

function lcs(ielem, sep, v) {
	updobj=ielem;

	if(!$('fcContainer')) 
		ielem.up().appendChild(new Element('div', {id: 'fcContainer'}).update(getCalendar()))
	
	// First check date is valid
	curdt=ielem.value.split(sep)[2] + sep + ielem.value.split(sep)[1] + sep + ielem.value.split(sep)[0];
	curdtarr=curdt.split(sep);
	isdt=true;
	for(var k=0;k<curdtarr.length;k++) {
		if (isNaN(curdtarr[k]))
			isdt=false;
	}
	if (isdt&(curdtarr.length==3)) {
		ccm=curdtarr[1]-1;
		ccy=curdtarr[2];
		prepcalendar(curdtarr[0],curdtarr[1]-1,curdtarr[2]);
	}
	
}

function evtTgt(e)
{
	var el;
	if(e.target)el=e.target;
	else if(e.srcElement)el=e.srcElement;
	if(el.nodeType==3)el=el.parentNode; // defeat Safari bug
	return el;
}
function EvtObj(e){if(!e)e=window.event;return e;}
function cs_over(e) {
	evtTgt(EvtObj(e)).style.background='#FFCC66';
}
function cs_out(e) {
	evtTgt(EvtObj(e)).style.background='#C4D3EA';
}
function cs_click(e) {
	var res_date = calvalarr[evtTgt(EvtObj(e)).id.substring(1,evtTgt(EvtObj(e)).id.length)];
	res_date = res_date.split("/")[2] + '-' + res_date.split("/")[1] + '-' + res_date.split("/")[0];
	updobj.value = res_date;
	Element.remove('fcContainer')
	
}

var mn=new Array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');
var mnn=new Array('31','28','31','30','31','30','31','31','30','31','30','31');
var mnl=new Array('31','29','31','30','31','30','31','31','30','31','30','31');
var calvalarr=new Array(42);

function f_cps(obj) {
	obj.style.background='#C4D3EA';
	obj.style.font='10px Arial';
	obj.style.color='#333333';
	obj.style.textAlign='center';
	obj.style.textDecoration='none';
	obj.style.border='1px solid #6487AE';
	obj.style.cursor='pointer';
}

function f_cpps(obj) {
	obj.style.background='#C4D3EA';
	obj.style.font='10px Arial';
	obj.style.color='#ABABAB';
	obj.style.textAlign='center';
	obj.style.textDecoration='line-through';
	obj.style.border='1px solid #6487AE';
	obj.style.cursor='default';
}

function f_hds(obj) {
	obj.style.background='#FFF799';
	obj.style.font='bold 10px Arial';
	obj.style.color='#333333';
	obj.style.textAlign='center';
	obj.style.border='1px solid #6487AE';
	obj.style.cursor='pointer';
}

// day selected

function prepcalendar(hd,cm,cy) {

	now=new Date();
	sd=now.getDate();
	td=new Date();
	td.setDate(1);
	td.setFullYear(cy);
	td.setMonth(cm);
	cd=td.getDay();
	$('mns').innerHTML=mn[cm]+ ' ' + cy;
	marr=((cy%4)==0)?mnl:mnn;
	for(var d=1;d<=42;d++) {
		f_cps($('v'+parseInt(d)));
		if ((d >= (cd -(-1))) && (d<=cd-(-marr[cm]))) {
			dip=((d-cd < sd)&&(cm==sccm)&&(cy==sccy));
			htd=((hd!='')&&(d-cd==hd));
			/*
			if (dip)
				f_cpps($('v'+parseInt(d)));
			else 
			*/
			if (htd)
				f_hds($('v'+parseInt(d)));
			else
				f_cps($('v'+parseInt(d)));

			$('v'+parseInt(d)).onmouseover=cs_over;
			$('v'+parseInt(d)).onmouseout=cs_out;
			$('v'+parseInt(d)).onclick=cs_click;
			
			$('v'+parseInt(d)).innerHTML=d-cd;	
			calvalarr[d]=''+(d-cd)+'/'+(cm-(-1))+'/'+cy;
		}
		else {
			$('v'+d).innerHTML='&nbsp;';
			$('v'+parseInt(d)).onmouseover=null;
			$('v'+parseInt(d)).onmouseout=null;
			$('v'+parseInt(d)).style.cursor='default';
			}
	}
}

//prepcalendar(ccd,ccm,ccy);
//$('fc'+cc).style.visibility='hidden';

function caddm() {
	marr=((ccy%4)==0)?mnl:mnn;
	
	ccm+=1;
	if (ccm>=12) {
		ccm=0;
		ccy++;
	}
	cdayf();
	prepcalendar('',ccm,ccy);
}

function csubm() {
	marr = ((ccy%4)==0) ? mnl:mnn;
	ccm -= 1;
	if (ccm < 0) {
		ccm = 11;
		ccy--;
	}
	cdayf();
	prepcalendar('',ccm,ccy);
}

function cdayf() {
	if ((ccy>sccy)|((ccy==sccy)&&(ccm>=sccm))) {}
//		return;
	else {
		ccy=sccy;
		ccm=sccm;
		cfd=scfd;
	}
}