// JavaScript Document

FF.Contents = {
	
	table: table,
	selectedRows: new Array(),
	NE_Objs: new Array(),
	
	loading: function(status) {
		if(status) {
			var fog = Builder.node('div', {id: 'loader'});
			document.body.appendChild(fog);
			Element.setOpacity('loader', .85);
		}
	},
	
	onStartLoading: function()
	{
		FF.status = 'loadingData';
		FF.UI.createFadeLayer({background: '#fff'});
	},
	
	onLoadingComplete: function()
	{
		FF.status = 'idle';
		FF.UI.fireFadeLayer();
	},
	
	refreshDatas: function(userOptions)
	{
		// Gestisco le opzioni
		var opt = {
			pager : 0
		}
		Object.extend(opt, userOptions);
		
		if ($('ContentDataTBody')) {
			$('ContentDataTBody').update('');
			new Ajax.Request('inc/ajaxRequests.php?action=refreshDatas', {
				onCreate: function() {FF.Contents.onStartLoading();},
				onSuccess: function(t) {
					$('ContentDataTBody').update(t.responseText);
					FF.UI.setRowsProperies();
				},
				onComplete: function() {FF.Contents.onLoadingComplete();},
				onException: function() { FF.throwError('Data reloading failure!'); }
			});
		}
	},
	
	reload: function() 
	{
		window.location.reload();
	},
	
	getDirectoryUrl: function(f, path) 
	{
		FF.Console.show((path || ''), {returnField: $(f), initDir: 'moduleroot', customMessage: 'Naviga alla directory scelta e digita: "return dir"'});
	},
	
	checkSelection: function() {
		if(this.selectedRows.length > 0) {
			$('tabDuplicate').style.display = 'block';
			if(this.selectedRows.length > 1) $('tabMultipleDelete').style.display = 'block';
			else $('tabMultipleDelete').style.display = 'none';
		} else {
			$('tabDuplicate').style.display = 'none';
			$('tabMultipleDelete').style.display = 'none';
		}
	},
	
	checkAllRows: function(Obj) {
		var inputs = $$('input[type="checkbox"].rowSelector');
		if (Dashboard.content.rowSelection == 'None') {
			inputs.each(function(e){
				FF.Contents.checkRow(e, true);
			});
			Dashboard.content.rowSelection = 'All';
		} else {
			inputs.each(function(e){
				FF.Contents.checkRow(e, false);
			});
			Dashboard.content.rowSelection = 'None';
		}
	},
	
	checkRow: function (obj, status) {
		var row = obj.parentNode.parentNode;
		if(status) {
			obj.checked = true;
			Element.addClassName(row, 'selected');
			this.selectedRows.push(row.identify());
		} else {
			obj.checked = false;
			Element.removeClassName(row, 'selected');
			this.selectedRows = this.selectedRows.without(row.identify());
		}
		this.checkSelection();
	},
	
	checkRowByClick: function (row) {
		var status = row.select('.rowSelector')[0];
		if(!status.checked) {
			status.checked = true;
			row.addClassName('selected');
			this.selectedRows.push(row.identify());
		} else {
			status.checked = false;
			row.removeClassName('selected');
			this.selectedRows = this.selectedRows.without(row.identify());
		}
		this.checkSelection();
	},
	
	highlightRow: function(row) 
	{
		row.addClassName('selected');
	},
	
	unhighlightRow: function(row) 
	{
		var status = row.select('.rowSelector')[0];
		if(!status.checked) 
			row.removeClassName('selected');
	},
	
	showImagesThumb: function(field, path, w, btnIcon) {
		jQuery ("td.imageField_" + field).each ( function() {
			if ( jQuery(this).attr ("showing") != "true") {
				var imgSrc = jQuery(this).find (".datasBox").html();
				console.log ("mostro " + imgSrc)
				jQuery(this).attr ("showing", "true")
				jQuery(this).attr ("src", imgSrc);
				jQuery(this).find (".datasBox").html('<a class="cont-img-wrapper" href="'+path+imgSrc+'" target="new"><img src="'+path+imgSrc+'" width="'+w+'" /></a>');
			} else {
				jQuery(this).attr ("showing", "false");
				jQuery(this).find (".datasBox").html ( jQuery(this).attr ("src") );
			}
		});
		jQuery (btnIcon).find("img").attr ("src", ( jQuery (btnIcon).find("img").attr ("src").indexOf ("view") > -1 ) ?  
			jQuery (btnIcon).find("img").attr ("src").split ("view.gif").join ("hide.gif") : 
			jQuery (btnIcon).find("img").attr ("src").split ("hide.gif").join ("view.gif") 
		)
	},
	
	deleteRow: function(elemId, elemIdField, table) {
		if (confirm("Sei sicuro di voler cancellare questo dato?")) {
			window.location.href = 'dataProcessor.php?processACTION=DEL&idField='+elemIdField+'&id='+elemId+'&table='+table;
		} else {
			return false;
		}
	},
	
	fastEdit: function(elem) {
		
		// Cambio colore all'elemento in fast edit
		if (elem.nodeName == 'TD') {
			var td = elem;
			elem = elem.down('div');
		} else 
			var td = elem.up('td');
		td.addClassName('fastEditing');
		this.fastEditing = elem;
		this.updateFastediting = elem.identify();
		
		var popup = FF.UI.createPopup('fastEdit', {
			height		: 355,
			width		: 600,
			title		: 'Fast editor',
			allowResize	: true,
			onClose		: function() {
				FF.Contents.fastEditing.up().removeClassName('fastEditing');
			}
		});
		//  ** corretto per il dblclick **  popup.setContent('<iframe width="100%" height="345" border="0" frameborder="0" scrolling="no" src="fastEditor.php?field='+elem.getAttribute('name')+'&id='+elem.parentNode.parentNode.getAttribute('id').split("row_")[1]+'&idField='+$F('idField')+'&table='+$F('tableName')+'"></iframe>');
		popup.setContent('<iframe width="100%" height="325" border="0" frameborder="0" scrolling="no" src="fastEditor.php?field='+elem.getAttribute('name')+'&id='+FF.UI.ContextMenu.config.rowId+'&idField='+FF.UI.ContextMenu.config.fieldId+'&table='+FF.UI.ContextMenu.config.table+'"></iframe>');
		_GLOBAL['fast_edit_popup'] = popup;
	},
	
	closeFastEdit: function() 
	{
		var obj = this.fastEditing.up('td');
		obj.removeClassName('fastEditing');
		obj.removeClassName('highlightedElement');
		$('fastEditWrapper').up().remove();
		$('popupWrapper').remove();
		
	},
	
	refreshFastEdited: function() {
		var params = this.updateFastediting.split(">");
		var refresh = new Ajax.Request('inc/ajaxRequests.php?action=refreshFastEdited&table='+params[0]+'&id='+params[1]+'&campoGet='+params[2], {
			method: 'get',
			onComplete: FF.Contents.handle_refreshFastEdited
		});
	},
	
	handle_refreshFastEdited: function(ajax) {
		var updateHTML = ajax.responseText.split("<htmlcode>")[1];
		if(updateHTML != 'KO' && ajax.responseText != "") {
			$(FF.Contents.updateFastediting).innerHTML = updateHTML;
		}
	},
	
	saveFastEdit: function(href, form, closeAfterFinish) {
		
		if($('fastEditResultMessage'))
			$('fastEditResultMessage').remove();
		this.closeAfterFinish = closeAfterFinish;
		var wait = new Element('img', {src: 'i/loading.gif', id: 'loadingIcon'});
		href.insert({ after: wait });
		href.disabled = true;

		var save = new Ajax.Request('inc/ajaxRequests.php?action=saveFastEdit&'+form, {
			method: 'get',
			onComplete: FF.Contents.handle_saveFastEdit
		});
	},
	
	handle_saveFastEdit: function(req) {
		if(req.responseText.indexOf('OK') != -1) {
			$('loadingIcon').insert({after: new Element('span', {id: 'fastEditResultMessage'}).setStyle({color: 'green'}).update('Saved!')});
			$('loadingIcon').previous().disabled = false;
			$('loadingIcon').remove();
			parent.externalCall('FF.Contents.refreshFastEdited();');
			
			if(FF.contents.closeAfterFinish) {
				parent.externalCall('FF.Contents.closeFastEdit()');
			} else {
				var ok = new Element('div', {id: 'resultOk'}).addClassName('resultOK').update('Salvato correttamente');
				$('popupButtonRow').insertBefore(ok, Element.cleanWhitespace($('popupButtonRow')).firstChild);
				new Effect.Pulsate('resultOk', { afterFinishInternal: function() {Element.remove('resultOk')}});
			}
			
		} else {
			alert("ERRORE: " + req.responseText);
		}
	},
	
	moveOptions: function(sel1, sel2, selReal, val, Sep) 
	{
		//MUOVE I VALORI SELEZIONATI DALLA SEL1 ALLA SEL2
		//Ciclo i valori da spostare
		for(var i=0; i<sel1.length; i++) {
			if(sel1.options[i].selected) {
				var exists = false;
				for(var j=0; j<sel2.length; j++) {
					if(sel2.options[j].value == sel1.options[i].value) exists = true;
				}
				if(!exists) {
					//inserisco il valore nella select bersaglio e lo levo dall'altra
					var newOpt = new Option();
					newOpt.value = sel1.options[i].value;
					newOpt.text = sel1.options[i].text;
					sel2.options[sel2.options.length] = newOpt;
					
					sel1.options[i] = null;
				}
			}
		}
		// Valorizzo il campo nascosto
		var multiVal = "";
		for(var i=0; i<selReal.length; i++) {
			if(i==0) multiVal = selReal.options[i].value;
			else multiVal += Sep + selReal.options[i].value;
		}
		val.value = multiVal;
	},
	
	appendNE: function(a, NE_obj) 
	{
		a.innerHTML += ': <strong>OK</strong>';
		a.up().setStyle({
			background	: '#000'
		});
		this.NE_Objs.push(NE_obj);
	},
	
	checkEditForm: function(f)
	{
		for(var i=0; i<this.NE_Objs.length; i++) {
			for(var a=0; a<this.NE_Objs[i].nicInstances.length; a++) {
				this.NE_Objs[i].nicInstances[a].saveContent();
			}
		}
		f.submit();
	},
	
	SAVE: function() {
		this.checkEditForm(document.editForm)
		//document.editForm.submit();
	},
	
	SAVE_AND_REDO: function() {
		document.editForm.action = 'dataProcessor.php?action=doItAgain';
		this.SAVE();
	},
	
	SHOW_ALL: function() {
		window.location = FF.Utils.getLocationAndAppend('show=all', true);
	},
	
	SHOW_NORMAL: function() {
		window.location = FF.Utils.getLocationAndAppend('show=normal', true);
	},
	
	duplicateRow: function(btn) {
		if (btn) {
			btn.innerHTML += '<img src="i/loading_bgBlue.gif" hspace="5">';
			btn.onclick = null;
		}
		var ids = '';
		$A(this.selectedRows).each(function(e){
			ids += e.split("row_")[1]+',';
		});
		new Ajax.Request('inc/ajaxRequests.php?action=duplicateRows&ids='+ids+'&table='+FF.Contents.table, {
			method: 'get',
			onComplete: FF.Contents.duplicateRow_handler
		});
	},
	
	duplicateRow_handler: function(ajax) {
		if($$('#tabDuplicate img').length > 0)
			Element.remove($$('#tabDuplicate img')[0]);
		if(ajax.responseText.indexOf('QUERY_ERROR') != -1) {
			alert("Problemi con la dupplicazione: " + ajax.responseText)
		} else {
			window.location.reload();
		}
	},
	
	multipleDelete: function(btn) {
		if (btn) {
			btn.innerHTML += '<img src="i/loading_bgBlue.gif" hspace="5">';
			btn.onclick = null;
		}
		var ids = '';
		$A(this.selectedRows).each(function(e){
			ids += e.split("row_")[1]+',';
		});
		new Ajax.Request('inc/ajaxRequests.php?action=deleteMultipleRows&ids='+ids+'&table='+FF.Contents.table, {
			method: 'get',
			onComplete: FF.Contents.multipleDelete_handler
		});
	},
	
	multipleDelete_handler: function(ajax) {
		if($('tabMultipleDelete')[0])
			Element.remove($$('#tabMultipleDelete img')[0]);
		$A(FF.Contents.selectedRows).each(function(e){
			$(e).style.display = 'none';
		});
		if(ajax.responseText.indexOf('QUERY_ERROR') != -1) {
			alert("Problemi con la cancellazione: " + ajax.responseText)
		} else {
			$('tabMultipleDelete').onclick = function(){FF.Contents.multipleDelete(this);}
			$('tabMultipleDelete').style.display = 'none';
			FF.Contents.selectedRows = new Array();
		}
		this.checkSelection();
	},
	
	showCalendar: function(calID) {
		$(calID).style.display = 'block';
	},
	
	getExcelDump: function(table) {
		this.excelDumping = table;
		new Ajax.Request('inc/ajaxRequests.php?action=makeExcelDump&table='+table, {
			method: 'get',
			onComplete: FF.Contents.getExcelDump_handler
		});
	},
	
	getCSVDump: function(table) {
		this.excelDumping = table;
		new Ajax.Request('inc/ajaxRequests.php?action=makeCSVDump&table='+table, {
			method: 'get',
			onComplete: FF.Contents.getCSVDump_handler
		});
	},
	
	getCSVDump_handler: function(ajax) {
		if(ajax.responseText.indexOf('OK') != -1) {
			var fileName = ajax.responseText.split('<filename>')[1];
			var content = new Element('div').setStyle({
				padding	: '15px'
			});
			content.update('Il file CSV e\' scaricabile da qui: <a href="inc/'+fileName+'" target="_blank">SCARICA</a><br><br>Sara\' disponibile per una settimana.');
			FF.UI.create(200, 150, content, "DOM");
			
		} else {
			alert("ERROR creating CSV dump");
			alert(ajax.responseText);
		}
	},
	
	getSQLDump: function(table) {
		this.sqlDumping = table;
		new Ajax.Request('inc/ajaxRequests.php?action=makeSQLDump&table='+table, {
			method: 'get',
			onComplete: FF.Contents.getSQLDump_handler
		});
	},
	
	getSQLDump_handler: function(ajax) {
		if(ajax.responseText.strip().indexOf('OK') != -1) {
			var fileName = ajax.responseText.split('<filename>')[1];
			var content = new Element('div').setStyle({padding: 15}).update('Il file SQL e\' scaricabile da qui: <a href="inc/'+fileName+'" target="_blank">SCARICA</a><br><br>Sara\' disponibile per una settimana.');
			var p = FF.UI.createPopup('SQLDumper', {height: 120});
			p.setContent('<div style="padding: 15px">Il file SQL e\' scaricabile da qui: <a href="dumps/'+fileName+'" target="_blank">SCARICA</a><br><br>Sara\' disponibile per una settimana.</div>');
		} else {
			alert("ERROR creating SQL dump");
			alert(ajax.responseText);
		}
	},
	
	getExcelDump_handler: function(ajax) {
		alert("BETA - not active")
		if(ajax.responseText.indexOf('OK') != -1) {
			var fileName = ajax.responseText.split('<filename>')[1];
			var content = Builder.node('DIV', {style: 'padding: 15px'});
			content.innerHTML = 'Il file EXCEL e\' scaricabile da qui: <a href="inc/'+fileName+'" target="_blank">SCARICA</a><br><br>Sara\' disponibile per una settimana.';
			Animations.Popup.create(200, 150, content, "DOM");
			
		} else {
			alert("ERROR creating Excel dump");
			alert(ajax.responseText);
		}
	},
	
	removeUploadedFile: function(file, table, element) {
		this.removingField = element;
		this.removingFile = file;
		new Ajax.Request('inc/ajaxRequests.php?action=removeUploadedFile&fileName='+file+'&table=' + table, {
			method: 'get',
			onComplete: FF.Contents.removeUploadedFile_handler
		});
	},
	
	removeUploadedFile_handler: function(AJAX) {
		if(AJAX.responseText.indexOf('OK') != -1) {
			$('previewer_' + FF.Contents.removingField).src = 'i/imageNotFound.gif';
			$('filename_' + FF.Contents.removingField).innerHTML = '-- rimosso --';
			$('remove_' + FF.Contents.removingField).value = 'TRUE';
			Element.remove($('btnRemove_' + FF.Contents.removingField));
			var sel = $('fileSelect_' + FF.Contents.removingField);
			if(sel) 
				Element.cleanWhitespace(sel);
			for(var i=0; i<sel.childNodes.length; i++) {
				if(sel.childNodes[i].value == FF.Contents.removingFile) 
					Element.remove(sel.childNodes[i]);
			}
		}
	},
	
	selectFastImage: function(name, src, type, form) {
		this.changeImageSrc(name, src);
		if(!form) var form = document.editForm;
		form['loadingType_' + name].value = type;
		$('remove_' + name).value = '';
	},
	
	selectImage: function(name, src, type, form) {
		this.changeImageSrc(name, src);
		if(!form) var form = document.editForm;
		form['loadingType_' + name].value = type;
		$('remove_' + name).value = '';
	},
	
	changeImageSrc: function(id, src) {
		if(src != '') {
			$('previewer_' + id).src = src;
			$('filename_' + id).innerHTML = src;
			$('remove_' + id).value = '';
		}
	},
	
	CompressRows: function(ToolbarItem)
	{
		if(ToolbarItem) {
			ToolbarItem.update("Espandi righe");
			ToolbarItem.onclick = function() {FF.Contents.DecompressRows(ToolbarItem)}
		}		
		Dashboard.content.rowCompression = true;
		$$('.datasBox').invoke('addClassName', 'Compressed');
		FF.Toolbar.ClosePanels();
	},
	
	DecompressRows: function(ToolbarItem)
	{
		if(ToolbarItem) {
			ToolbarItem.update("Comprimi righe");
			ToolbarItem.onclick = function() {FF.Contents.CompressRows(ToolbarItem)}
		}
		Dashboard.content.rowCompression = false;
		$$('.datasBox').invoke('removeClassName', 'Compressed');
		FF.Toolbar.ClosePanels();
		new Ajax.Request('inc/ajaxRequests.php?action=setRowCompressedView');
	},
	
	addTooltips: function() 
	{
		$$('.datasBox').each(function(Obj) {
			Obj.observe('mouseover', function() {
				if(Dashboard.content.rowCompression) {
					FF.Contents.ttElement = this;
					setTimeout(function() {
						if(FF.Contents.ttElement == Obj) {
							/* Mostro il tooltip */
							FF.Contents.showTooltip(Obj.innerHTML);
						}
					}, 1000);
				}
			});
			Obj.observe('mouseout', function() {
				if (Dashboard.content.rowCompression) {
					if (FF.Contents.ttElement == this) 
						FF.Contents.ttElement = null;
					if (FF.Contents.tt) 
						Element.remove(FF.Contents.tt);
				}
			});
		});
	},
	
	showTooltip: function(code, elem)
	{
		if($('tt_panel'))
			Element.remove('tt_panel');
		var tt = new Element('div', {id: 'tt_panel'}).setStyle({
			position	: 'absolute',
			padding		: '10px',
			maxWidth	: '250px'
		}).update(code).observe('click', function(){Element.remove(this)});
		$('contentWrapper').insert(tt);
		var ttElem = elem || FF.Contents.ttElement || false;
		if(ttElem) {
			tt.setStyle({
				top	: (ttElem.positionedOffset()['top'] - tt.getDimensions()['height'] - 5) + 'px',
				left: (ttElem.positionedOffset()['left'] - ((tt.getDimensions()['width'] - ttElem.getDimensions()['width']) / 2)) + 'px'
			});
			FF.Contents.tt = tt;
		}
	}
	
}