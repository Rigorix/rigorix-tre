// JavaScript Document

FF.Configurator = {
	
	iframe: $('ConfiguratorWindow'),
	
	SAVE_CONFIG: function()
	{
		window.configurator.document.configurator.submit();
	},
	
	setIframeDimensions: function(d)
	{
		if($('ConfiguratorWindow')) {
			$('ConfiguratorWindow').style.width	 = d['width'] + 'px';
			$('ConfiguratorWindow').style.height = d['height'] + 'px';
		}
	},
	
	addNewConnection: function()
	{
		var pop = FF.UI.createPopup('NewConnectionPopup', {
			width	: 400,
			height	: 300,
			title	: 'Add new connection'
		});
		var newForm = '<br /><form action="adm_configurator.php?action=ADD_CONNECTION&tab=3" method="POST"><table width="100%" class="connectionTable">'+
			'<tr><td><strong>Connection host</strong></td><td><input type="text" name="connectionHost" /></td></tr>' +
			'<tr><td><strong>Database host</strong></td><td><input type="text" name="host" /></td></tr>' +
			'<tr><td><strong>Database name</strong></td><td><input type="text" name="name" /></td></tr>' +
			'<tr><td><strong>Database user</strong></td><td><input type="text" name="user" /></td></tr>' +
			'<tr><td><strong>Database pwd</strong></td><td><input type="text" name="pwd" /></td></tr>' +
		'</table><br /><br />' +
		'<input type="submit" value="ADD" /></form>';
		/*
		var newForm = $$('.connectionTable')[0].up().cloneNode(true);
		newForm.style.width = '100%';
		newForm.down('h2').replace('<input type="text" name="connectionName" />');
		*/
		pop.setContent(newForm);
	},
	
	removeDatabaseConnection: function(elem, index)
	{
		if (confirm('Are you sure to delete this connection?')) {
			new Ajax.Request('../inc/ajaxRequests.php?action=removeDatabaseConnection&index=' + index, {
				onSuccess: function(ajax){
					if (ajax.responseText.indexOf('OK') > -1) {
						elem.innerHTML = '<span class="red">Deleted!</span>';
						elem.onclick = null;
						setTimeout(function(){
							new Effect.DropOut(elem.up().previous());
							new Effect.DropOut(elem)
						}, 2000);
					}
					else 
						alert("Error deleting database connection!");
				}
			});
		}
	},	
	
	removePlugin: function(row, name) 
	{
		Element.remove(row.up(1));
		if(name != null) {
			// Era stato impostato, devo impostare la cancellazione
			name = name.split(" ").join("");
			$('configurator_form').appendChild(new Element('input', {type: 'hidden', name: 'plugin_' + name + '_remove', value: name}));
		}
	},
	
	addPluginRow: function() 
	{
		new Ajax.Request('../inc/ajaxRequests.php?action=getPlugins&index='+$('pluginTable').childNodes.length, {
			method: 'get',
			onComplete: FF.Configurator.addPluginRow_handler
		});
	},
	
	addPluginRow_handler: function(ajax) 
	{
		$('pluginTable').innerHTML += ajax.responseText;
	},
	
	setDataPerPage: function(table, num) {
		new Ajax.Request('inc/ajaxRequests.php?action=setDataPerPage&table='+table+'&num='+num, {
			method: 'get',
			onComplete: function(ajax) { FF.Contents.reload()	},
			onException: function() { FF.throwError('Error setting datas per page!'); }
		});
	},
	
	addNewUser: function() {
		var Pop = FF.UI.createPopup('newUserPop', {
			width	: 500,
			height	: 300
		});
		Pop.setContent('<iframe width="100%" height="250" border="0" frameborder="0" scrolling="no" src="new_user.php"></iframe>');
		
	},
	
	editUserForm: function(sel) {
		var Pop = FF.UI.createPopup('editUserPopup', {
			width	: 500,
			height	: 350
		});
		Pop.setContent('<iframe width="100%" height="280" border="0" frameborder="0" scrolling="no" src="edit_user.php?user='+sel.value+'"></iframe>');
		
	},
	
	removeUser: function(userName) {
		if (confirm('Sicuro di voler cancellare l\'utente ' + userName + '?')) {
			this.userDeleting = userName;
			new Ajax.Request('../inc/ajaxRequests.php?action=removeUser&userName=' + userName, {
				method: 'get',
				onComplete: FF.Configurator.removeUser_handler
			});
		}
	},
	
	removeUser_handler: function(ajax) {
		if (ajax.responseText.strip() != 'ERROR_DELETING') {
			FF.log('Cancellazione dell\'utente: ' + ajax.responseText.strip());
			window.location.href = 'edit_user.php?action=DELETED&user=' + ajax.responseText.strip();
		} else {
			FF.log('Impossibile cancellare l\'utente: ' + ajax.responseText.strip() + '. Non esiste');
			window.location.href = 'edit_user.php?action=NOT_DELETED&user=' + ajax.responseText.strip();
		}			
	},
	
	memSelectedTables: function(sel, fieldID, sep) {
		var values = "";
		for(var i=0; i<sel.options.length; i++) {
			if(sel.options[i].selected) {
				values += sel.options[i].value;
				if(i < sel.options.length) values += sep;
			}
		}
		$(fieldID).value = values;
	},
	
	openCrossfieldSetup: function(f)
	{
		var pop = FF.UI.createPopup('CrossfieldPopup', {
			width	: 600,
			height	: 365,
			title	: 'Crossfield settings'
		});
		_GLOBAL['cross_fields_popup'] = pop;
		FF.EventManager.addEscAction(function() {
			_GLOBAL['cross_fields_popup'].close();
			_GLOBAL['cross_fields_popup'] = null;
		});
		pop.setContent('<iframe width="100%" height="345" border="0" frameborder="0" scrolling="no" src="conf.cross_fields.php?field='+f+'"></iframe>');
	},
	
	removeCrossfield: function(field)
	{
		var pop = FF.UI.createPopup('CrossfieldPopup', {
			width	: 350,
			height	: 140,
			title	: 'Remove crossfield for ' + field
		});
		pop.setContent('<div style="text-align: center" id="RemoveResponder"><br />Sicuro di voler rimuovere il crossfield?<br /><br /><input type="button" value="Continua" onclick="FF.Configurator.doRemoveCrossfield(\''+field+'\')" /></div>');
	},
	
	doRemoveCrossfield: function(f)
	{
		new Ajax.Updater('RemoveResponder', '../inc/ajaxRequests.php?action=removeCrossField&table='+table+'&field='+f);
	},
	
	openVirtualfieldSetup: function(f)
	{
		var pop = FF.UI.createPopup('VirtualfieldPopup', {
			width	: 600,
			height	: 375,
			title	: 'Virtualfield settings for "' + f + '"'
		});
		_GLOBAL['virtual_fields_popup'] = pop;
		pop.setContent('<iframe width="100%" height="345" border="0" frameborder="0" scrolling="no" src="conf.virtual_fields.php?field='+f+'"></iframe>');
	},
	
	removeVirtualfield: function(field)
	{
		var pop = FF.UI.createPopup('VirtualfieldPopup', {
			width	: 350,
			height	: 140,
			title	: 'Remove virtualfield for ' + field
		});
		pop.setContent('<div style="text-align: center" id="RemoveResponder"><br />Sicuro di voler rimuovere il virtualfield?<br /><br /><input type="button" value="Continua" onclick="FF.Configurator.doRemoveVirtualfield(\''+field+'\')" /></div>');
	},
	
	doRemoveVirtualfield: function(f)
	{
		new Ajax.Updater('RemoveResponder', '../inc/ajaxRequests.php?action=removeVirtualField&table='+table+'&field='+f);
	},
	
	showHide: function(div, btn) {
		if($('conf_messager_debug').style.display != 'block') {
			$('conf_messager_debug').style.display = 'block';
		}
		else $('conf_messager_debug').style.display = 'none';
	},
	
	ShowTab: function(obj, tabFile)
	{
		this.TabObj = obj;
		this.TabFile = tabFile;
		
		if (!obj.up().hasClassName('on')) {
			$('contentWrapper').update("<div style=padding:10px>Loading...</div>");
			FF.UI.resetInternalConsole();
			obj.up().addClassName('on');
			new Ajax.Request(tabFile, {
				method: 'get',
				onComplete: function(res){
					if ($('contentWrapper')) {
						$('contentWrapper').update(res.responseText);
						eval(res.responseText.extractScripts());
					}
				}
			});
		}
	},
	
	refreshTab: function() {
		this.ShowTab(FF.Configurator.TabObj, FF.Configurator.TabFile);
	},
	
	showBackupFile: function(file) 
	{
		window.open(file)
	},
	
	deleteBackupFile: function(row, file) 
	{
		this.rowToRemove = row.up(1);
		new Ajax.Request('../inc/ajaxRequests.php?action=deleteBackupFile&file=' + file, {
			method: 'get',
			onComplete: function(res) {
				if(res.responseText.indexOf('Error') == -1)
					Element.remove(FF.Configurator.rowToRemove)
				FF.report(res.responseText);
			}
		});
	},
	
	restoreBackup: function(e, file)
	{
		this.restore_e = e;
		this.restoring = file;
		new Ajax.Request('../inc/ajaxRequests.php?action=restoreBackup&file=' + file, {
			method: 'get',
			onComplete: function(res) {
				if(res.responseText.indexOf('Error') == -1) {
					$A(FF.Configurator.restore_e.up(2).getElementsByTagName('tr')).each(function(r){
						r.removeClassName('restoredFromThis');
					});
					e.up(1).addClassName('restoredFromThis');
				}
				FF.report(res.responseText);
			}
		});
	},
	
	showDiff: function(e, file) 
	{
		/*
		var diff_win = FF.UI.createPopup('DiffWindow', {
			width		: document.viewport.getDimensions()['width'] * .9,
			height		: document.viewport.getDimensions()['height'] * .85
		});
		diff_win.setContent("<h4 id=diocan>Backup Diff</h4>");
		
		new Ajax.Request('../inc/ajaxRequests.php?action=viewDiff&file=' + file, {
			method: 'get',
			onComplete: function(res) {
				if(res.responseText.indexOf('Error') == -1) {
					diff_win.setContent('<pre style="background: #f3f3f3;float:left;width:49%;height:88%;overflow:auto"><code>'+res.responseText.escapeHTML()+'</code></pre>');
				} else
					diff_win.setContent("<p style=color:red>Error loading Diff</p>");
				
				// Chiamo il file di config
				new Ajax.Request('../inc/ajaxRequests.php?action=viewDiff&file=config', {
					method: 'get',
					onComplete: function(res) {
						if(res.responseText.indexOf('Error') == -1) {
							diff_win.setContent('<div style="width: 1%; overflow: hidden; float: left;">&nbsp;</div><pre style="background: #f3f3f3;float:left;width:49%;height:88%;overflow:auto"><code>'+res.responseText.escapeHTML()+'</code></pre>');
						} else
							diff_win.setContent("<p style=color:red>Error loading Diff</p>");
					}
				}); 
				
			}
		}); 
		*/
		var diff_win = FF.UI.createPopup('DiffWindow', {
			width		: document.viewport.getDimensions()['width'] * .9,
			height		: document.viewport.getDimensions()['height'] * .85
		});
		diff_win.setContent("<h4 id=diocan>Backup Diff</h4>");
		new Ajax.Request('../inc/ajaxRequests.php?action=viewConfigDiff&file=' + file, {
			method: 'get',
			onComplete: function(res) {
				if(res.responseText.indexOf('Error') == -1) {
					diff_win.setContent('<div style="background: #f3f3f3;width:100%;height:88%;overflow:auto">'+res.responseText+'</div>');
				} else
					diff_win.setContent("<p style=color:red>Error loading Diff</p>");
			}
		}); 
	},
	
	createModule: function(name, dir, a) 
	{
		new Ajax.Request('../inc/ajaxRequests.php?action=createModule&name='+name+'&dir='+dir, {
			onComplete: function() {
				//FF.Configurator.refreshTab();
				window.location.href = '?act=modules';
			}
		});
		a.wrap('marquee', {behavior: 'scroll', scrolldelay: 0, direction: 'right', style: 'width: 25px'});
		// <marquee behavior="scroll" scrolldelay="0" direction="right" style="width: 20px">
	},
	
	setModuleProps: function(ser)
	{
		new Ajax.Request('../inc/ajaxRequests.php?action=setModuleProps&'+ser, {
			onComplete: function() {
				//FF.Configurator.refreshTab();
				window.location.href = '?act=modules';
			}
		});
	},
	
	
	
	/*
	 * Componente per aggiungere una tabella al Database 
	 */
	tableFieldCount: 0, 
	
	addTableFieldRow: function(l)
	{
		this.tableFieldCount++;
		var tr = $('field_table').down('tbody').down('tr');
		var newTr = tr.cloneNode(true);
		newTr.down('td', 0).update('<a class="cursorPointer normal" onclick="FF.Configurator.removeTableFieldRow(this)">Remove</a>');
		var field_name = newTr.down('td', 1).down('input').name;
		var field_index = new Number(field_name.split('field')[1].split('_name')[0]);
		newTr.getElementsBySelector('input').each(function(e){
			e.value = '';
			e.name = e.name.replace('field' + field_index, 'field' + FF.Configurator.tableFieldCount);
		});
		newTr.getElementsBySelector('select').each(function(e){
			e.selectedIndex = 0; 
			e.name = e.name.replace('field' + field_index, 'field' + FF.Configurator.tableFieldCount);
		});	
		$('field_table').down('tbody').insert({bottom: newTr})
	},
	
	removeTableFieldRow: function(e)
	{
		Element.remove(e.up(1))
	},
	
	removeModule: function(path)
	{
		if(confirm('Are you sure to unlink this module from the list?')) {
			$('ModuleEditingCell').update('Working...');
			new Ajax.Request('../inc/ajaxRequests.php?action=removeModule&path=' + path, {
				onComplete: function(res) {
					if(res.responseText.strip() == 'OK') {
						$('ModuleEditingCell').update('<span style="color: green">Successfully removed!</span><br /><br />The module directory and configuration is still available in the filesystem but not usable with the FF admin.');
						$A($('ModuleEditingSelect').options).each(function(opt){
							if (opt.value == path) 
								Element.remove(opt);
						});
					} else {
						$('ModuleEditingCell').update('<span style="color: red">An error occurred while removing the module!</span>');
					}
				}
			}); 
		}
	}
	
}




