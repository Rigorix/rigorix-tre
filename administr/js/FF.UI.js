// JavaScript Document

FF.UI = {
	
	/*
	 * ZIndexes: 
	 * 		fadeLayer		=> 900
	 * 		popups			=> 1000
	 * 		Finder			=> 1500
	 * 		Shortcut label	=> 9000
	 */
	AnimationSpeed	: .6,
	PopupIndex		: 1,
	
	setInterface: function() 
	{
		if ($('loader')) 
			Element.remove($('loader'));
			
		this.setWindowProperties();
		this.setWindowTabs();
		this.setAppInteractions();
		
	},
	
	setRowsProperies: function() 
	{
		$('ContentDataTBody').select('tr').each(function(tr) {
			tr.observe('click', function(){ FF.Contents.checkRowByClick(this) });
			tr.observe('contextmenu', function(e){ FF.UI.ContextMenu.showMenu(this, e); Event.stop(e); });
			tr.observe('dblclick', function(e){ e.preventDefault(); FF.UI.ContextMenu.setConfig(this, e); FF.Contents.fastEdit(Event.element(e)); Event.stop(e); });
		});
		if ( jq("#ContentDataTBodyFinder").size() > 0 ) {
			$('ContentDataTBodyFinder').select('tr').each(function(tr) {
				tr.observe('click', function(){ FF.Contents.checkRowByClick(this) });
				tr.observe('contextmenu', function(e){ FF.UI.ContextMenu.showMenu(this, e); Event.stop(e); });
			});
		}
	},
	
	setAppInteractions: function() 
	{
		/*
		$$('select[multiple="multiple"]').each(function(sm) {
			$A(sm.options).each(function(smo) {
				smo.observe('dblclick', function(e) {
					FF.UI.multiSelectDblclickFn(sm, Event.element(e));
				})
			});
		});
		*/
		FF.UI.CustomFields.init();
	},
	
	setContentTableDesign: function()
	{
		var tflex = 0;
		var wrapw = jQuery ("#contentWrapper").width();
		jQuery ("#ContentDataTBody tr:first td div.datasBox").each (function() {
			tflex += new Number ( jQuery(this).attr ("flex") );
		});
		var fact = new Number(wrapw / tflex);
		jQuery ("#ContentDataTBody tr:first td").each (function(j) {
			/*jQuery(this)
				.attr("width", ( new Number (jQuery(this).find(".datasBox").attr ("flex") * fact) ))
				.css ("overflow", "hidden");*/
			var flex = jQuery(this).find(".datasBox").attr ("flex");
			jQuery(".datasBox").each ( function () {
				jQuery (this)
					.width ( new Number ( jQuery (this).attr ("flex") * fact) )
			});
				
			jQuery ("#tableHeaderComponent th:eq("+j+")")
				.attr("width", ( flex * fact ))
		});
		jQuery ("#contentsTable").width (wrapw);
	},
	
	multiSelectDblclickFn: function(sm, smo) 
	{
		var field = sm.id.split('_');
		var fieldType = field[field.length - 1];
		field = field.without(fieldType);
		var fieldName = field.join('_');
		if(fieldType == 'target') 
			var target = $(fieldName + '_multivalue');
		else 
			var target = $(fieldName + '_target');
		var newOpt = smo.cloneNode(true);
		newOpt.observe('dblclick', function(e) {
			FF.UI.multiSelectDblclickFn(target, Event.element(e));
		})
		
		target.options[target.options.length] = newOpt;
		Element.remove(smo);
		
		var newValue = '';
		$A($(fieldName + '_target').options).each(function(e) {
			if(newValue != '') 
				newValue += multifieldSeparator;
			newValue += e.value;
		});
		$(fieldName).value = newValue;
	},
	
	setWindowTabs: function()
	{
		/*
		var tabs = $$('div.tabStyle');
		if (tabs.length > 0) {
			for (var i = 0; i < tabs.length; i++) {
				tabs[i].appendChild(new Element('b').setStyle({
					width		: '1px',
					height		: '1px',
					overflow	: 'hidden',
					lineHeight	: '1px',
					background	: '#fff',
					position	: 'absolute',
					top			: 0,
					left		: 0,
					display		: 'block'
				}));
				tabs[i].appendChild(new Element('i').setStyle({
					width		: '1px',
					height		: '1px',
					overflow	: 'hidden',
					lineHeight	: '1px',
					background	: '#fff',
					position	: 'absolute',
					top			: 0,
					right		: 0,
					display		: 'block'
				}));
			}
		}
		*/
	},
	
	setSkin: function(t)
	{
		switch(t) {
			
			case 'Menu_minimizeBar':
				if ($('menuRightBar').down('a')) {
					$('menuRightBar').down('a').setStyle({
						visibility: 'visible',
						top: ((document.viewport.getDimensions()['height'] / 2) - ($('menuRightBar').down('a').getDimensions()['height'] / 2) + 'px')
					});
				}
				break;
			
		}
	},
	
	toggleMenuView: function() 
	{
		if(Dashboard.config.MENU_STATUS == 'CLOSE') {
			parent.document.getElementById('FF_UI_ContentWindow').setAttribute('cols', Dashboard.config.OPEN_MENU_WINDOW_WIDTH);
			Dashboard.config.MENU_STATUS = 'OPEN';
			parent.menu.document.getElementById('menuRightBar').down('a').update('< < < < < < < <');
		} else {
			Dashboard.config.OPEN_MENU_WINDOW_WIDTH = parent.document.getElementById('FF_UI_ContentWindow').getAttribute('cols');
			Dashboard.config.MENU_STATUS = 'CLOSE'
			parent.document.getElementById('FF_UI_ContentWindow').setAttribute('cols', '5,*');
			parent.menu.document.getElementById('menuRightBar').down('a').update('> > > > > > > >');
		}
	},
	
	toggleHeaderView: function() 
	{
		if(Dashboard.config.HEADER_STATUS == 'CLOSE') {
			parent.document.getElementById('FF_UI_AppWindow').setAttribute('rows', Dashboard.config.OPEN_HEADER_WINDOW_HEIGHT);
			Dashboard.config.HEADER_STATUS = 'OPEN';
		} else {
			Dashboard.config.OPEN_HEADER_WINDOW_HEIGHT = parent.document.getElementById('FF_UI_AppWindow').getAttribute('rows');
			Dashboard.config.HEADER_STATUS = 'CLOSE'
			parent.document.getElementById('FF_UI_AppWindow').setAttribute('rows', '0,*');
		}	
	},
	
	setWindowProperties: function() 
	{
		if($('contentWrapper') && $('contentHeader')) {
			this.checkContentSize();
			Event.observe(window, 'resize', function(){
				FF.UI.checkContentSize();
			})
		}
		if($('MenuContainer_')) {
			Event.observe(window, 'resize', function(){
				FF.UI.checkMenuSize();
			})
		}
		// Aggiungo i tooltip generali
		$$('tips').each(function(t) {
			t.observe('mouseover', function() {
				FF.Contents.showTooltip(this.readAttribute('msg'), this);
			});
			t.observe('mouseout', function() {
				if(FF.Contents.tt)
					Element.remove(FF.Contents.tt);
			});
			t.setStyle({
				borderBottom: '1px dashed green',
				cursor: 'help'
			});
		});
	},
	
	checkContentSize: function()
	{
		var Win = document.viewport.getDimensions();
		var CW_height = (Win.height - $('contentHeader').getDimensions().height - 4);
		if($('contentHeader_internal'))
			CW_height -= $('contentHeader_internal').getDimensions().height;
		
		$('contentWrapper').setStyle({
			width	: (Win.width - 10) + 'px',
			height	: CW_height - 5 + 'px'
		});
		this.setContentTableDesign ();
		/*
		$('contentWrapper').setStyle({
			width	: (Win.width - 10) + 'px',
			height	: CW_height - 5 + 'px',
			overflowY: 'hidden',
			padding	: 0,
			margin	: 0
		});
		if ($('contentsTable') && $('contentsTable').down('tbody')) {
			$('contentsTable').down('tbody').setStyle({
				height	: CW_height - 34 + 'px',
			padding	: 0,
			margin	: 0
			});
		}
		*/
	},
	
	checkMenuSize: function() 
	{
		
	},
	
	createFadeLayer: function(userStyle)
	{
		var style = {
			position	: 'absolute',
			width		: document.viewport.getDimensions()['width'],
			height		: document.viewport.getDimensions()['height'],
			background	: '#000',
			top			: 0,
			left		: 0,
			zIndex		: 900
		}
		Object.extend(style, userStyle);
		if(!$('fadeLayer')) {
			var fd = new Element('div', {id: 'fadeLayer'}).setStyle(style).setOpacity(.7);
			document.body.appendChild(fd);
			this.observeFadeLayer();
		} else 
			$('fadeLayer').show();
	},
	
	fireFadeLayer: function()
	{
		if($('fadeLayer')) 
			Element.remove($('fadeLayer'));
	},
	
	observeFadeLayer: function()
	{
		// Controllo la posizione
		Event.observe(window, 'resize', function() {
			if ($('fadeLayer')) {
				$('fadeLayer').setStyle({
					width	: document.viewport.getDimensions()['width'],
					height	: document.viewport.getDimensions()['height'],
					top		: $('contentWrapper').positionedOffset()
				});
			}
		});
	},
	
	observePopup: function(popupObj)
	{
		// Controllo la posizione
		Event.observe(window, 'resize', function(){
			FF.UI.CenterLayer(popupObj)
		});
	},
	
	CenterLayer: function(popupObj, animation)
	{
		if (popupObj) {
			var dims = this.getCenterLayerDims(popupObj);
			
			if(animation)
				new Effect.Move(popupObj, {x: dims.left, y: dims.top, duration: .3});
			else 
				popupObj.setStyle({
					top: dims.top + 'px',
					left: dims.left + 'px'
				});
		}
	},
	
	getCenterLayerDims: function(popupObj)
	{
		if (popupObj) {
			return {
				top	: Number((document.viewport.getDimensions()['height'] / 2) - (popupObj.getDimensions()['height'] / 2)),
				left: Number((document.viewport.getDimensions()['width'] / 2) - (popupObj.getDimensions()['width'] / 2))
			}
		}
	},
	
	showMessage: function(userOptions) 
	{
		var options = {
			text		: '',
			autohideTime: 3,
			css			: {}
		}
		if(typeof options == 'string')
			options.text = userOptions;
		else 
			Object.extend(options, userOptions);
		if(!this.messageBox) 
			this.createMessageBox();
		this.messageBox.setStyle(options.css);
		this.messageBox.update(options.text);
		if (this.messageBox.status == 'hidden') {
			new Effect.Move(FF.UI.messageBox, {
				x: (document.viewport.getDimensions()['width'] / 2 - FF.UI.messageBox.getDimensions()['width'] / 2),
				y: (document.viewport.getDimensions()['height'] / 2 - FF.UI.messageBox.getDimensions()['height'] / 2),
				duration: .3,
				mode: 'absolute'
			});
			this.messageBox.status = 'visible';
		}
		setTimeout(function() {
			new Effect.Move(FF.UI.messageBox, {
				x: (document.viewport.getDimensions()['width'] + 40),
				duration: .3,
				afterFinishInternal: function() {
					Element.remove(FF.UI.messageBox);
				}
			});
		}, options.autohideTime * 1000);
	},
	
	createMessageBox: function()
	{
		var mb = new Element('div', {id: 'UI_MessageBox'}).setStyle({
			padding		: '10px',
			background	: '#b9e8ae',
			color		: '#333',
			fontSize	: '12px',
			position	: 'absolute',
			width		: '200px',
			height		: '200px'
		});
		FF.UI.CenterLayer(mb, true);
		mb.style.left = '-400px';
		mb.status = 'hidden';
		
		mb.refresh = function(t){
			if(t)
				this.update(t);
			FF.UI.CenterLayer(this);
		}
		document.body.appendChild(mb);
		this.messageBox = mb;
	},
	
	createPopup: function(popupId, userOptions) 
	{
		var options = {
			width		: 300,
			height		: 300,
			background	: '#fff',
			padding		: 6,
			fadeLayer	: true,
			title		: 'Popup',
			onClose		: null,
			closeOnEsc	: true,
			iframe		: false,
			allowResize	: false
		}
		Object.extend(options, userOptions);
		this.popupOptions = options;
		if(options.fadeLayer == true)
			this.createFadeLayer();
		if(popupId == null)
			popupId = 'pop_' + FF.UI.PopupIndex;
		if(!$(popupId))  {
			var Pop = new Element('div', {id: popupId}).setStyle({
				position	: 'absolute',
				top			: 1,
				left		: 1, 
				width		: options['width'],
				height		: options['height'],
				background	: options['background'],
				zIndex		: (Dashboard.ui.zindex.lightbox + FF.UI.PopupIndex)
			}).setOpacity(1).addClassName('LayerPopup');
			
			var PopWin = new Element('div', {id: 'PopupContentWindow'}).setStyle({
				padding: options['padding']
			}).addClassName('LayerPopupWindow');
			if(options.title != null)
				PopWin.update('<h4>'+options.title+'</h4>');
			
			var CloseBtn = new Element('div').addClassName('popupCloseButton').update('<a href="#" onclick="FF.UI.ClosePopup(this)">X</a>');
			if(options.iframe != false) {
				// Inserisco l'iframe
				var iframe = new Element('iframe', {src: options.iframe}).setStyle({width: '99%', height: '90%'});
				PopWin.insert(iframe);
			}
			PopWin.appendChild(CloseBtn);
			Pop.appendChild(PopWin);
			document.body.appendChild(Pop);
			this.PopupIndex++;
			this.CenterLayer($(popupId))
			this.observePopup($(popupId));
		}
		$(popupId).setContent = function(t) {
			$('PopupContentWindow').innerHTML += t;
		}
		$(popupId).close = function(t) {
			FF.UI.ClosePopup(this);
		}
		$(popupId).resize = function(w, h) {
			if(options.allowResize == true) {
				this.setStyle({
					width: w + 'px',
					height: h + 'px'
				});
			}
		}
		_GLOBAL['active_popup'] = $(popupId);
		if(options['closeOnEsc'] == true) {
			if (FF.EventManager && _GLOBAL['active_popup']) {
				FF.EventManager.addEscAction(function(){
					_GLOBAL['active_popup'].close();
					_GLOBAL['active_popup'] = null;
				});
			}
		}
		return $(popupId);
	},
	
	ClosePopup: function(Obj)
	{
		if(Obj.tagName == 'A')
			Element.remove(Obj.up(2));
		else
			Element.remove(Obj);
		if($('fadeLayer')) 
			$('fadeLayer').hide();
		if(this.popupOptions.onClose != null)
			this.popupOptions.onClose();
		this.popupOptions = null;
	},
	
	runConfig: function(obj)
	{
		if(!obj.up().hasClassName('on')) {
			this.resetInternalConsole();
			this.activeInternalTab = obj;
			this.addInternalTabLoader('Loading...');
			obj.up().addClassName('on');
			this.createInternalConsole();
			$('IntConsole').update('<iframe name="configurator" border="0" frameborder="0" scrolling="no" id="ConfiguratorWindow" src="conf/configurator.php?table='+FF.Contents.table+'"></div>'); 
		} else {
			obj.up().removeClassName('on');
			this.removeInternalTabButtons(obj);
			this.CloseInternalConsole();
		}
	},
	
	addInternalTabLoader: function(lbl) {
		if($('tabLoader'))
			Element.remove('tabLoader');
		var loader = new Element('span', {id: 'tabLoader'}).update(lbl);
		this.activeInternalTab.up().appendChild(loader);
	},
	
	removeInternalTabLoader: function(lbl) {
		if($('tabLoader'))
			Element.remove('tabLoader');
	},
	
	removeInternalTabButtons: function()
	{
		this.activeInternalTab.up().descendants().each(function(e) {
			if(e.tagName == 'INPUT')
				Element.remove(e)
		});
	},
	
	addInternalTabButton: function(label, onclickFn) {
		var btn = new Element('input', {value: label, type: 'button'}).addClassName('internalTabButton').observe('click', function() {
			onclickFn();
		})
		this.activeInternalTab.up().appendChild(btn);
	},
	
	runFilter: function(obj) {
		if(!obj.up().hasClassName('on')) {
			this.activateTab(obj);
			this.activeInternalTab = obj;
			this.addInternalTabLoader('Loading...');
			this.createInternalConsole();
			new Ajax.Request('inc/ajaxRequests.php?action=getFilterConsole&table='+FF.Contents.table, {
				method: 'get',
				onComplete: FF.UI.runFilter_handler
			});
		} else {
			obj.up().removeClassName('on');
			this.CloseInternalConsole();
		}
	},
	
	runFilter_handler: function(ajax) 
	{
		$('IntConsole').update("<div id='filterBox'>" + ajax.responseText + "</div>"); 
		FF.UI.OpenInternalConsole();
		/*FF.UI.addInternalTabButton('Filtra!', function(){
			$('filterConsole').submit();
		});*/
	},
	
	resetInternalConsole: function()
	{
		$('tabContainer').childElements().each(function(e, i) {
			e.removeClassName('on');
			e.descendants().each(function(e) {
				if(e.tagName == 'INPUT')
					Element.remove(e)
			});
		});
	},
	
	createInternalConsole: function()
	{
		var Box = new Element('div', {id: 'IntConsole'}).update('<p>Loading...</p>');
		$('tabContent').appendChild(Box);
	},
	
	OpenInternalConsole: function(h)
	{
		if(!h)
			h = ($('IntConsole').getDimensions()['height'] + 10);
			
		new Effect.Morph('tabContent', {
			style: 'height: ' + h + 'px',
			duration: FF.UI.AnimationSpeed
		});
		this.removeInternalTabLoader();
	},
	
	CloseInternalConsole: function()
	{
		new Effect.Morph('tabContent', {
			style: 'height: 0px',
			duration: FF.UI.AnimationSpeed,
			afterFinishInternal: function() {
				Element.remove('IntConsole');
			}
		});
	},
	
	activateTab: function(Tab)
	{
		this.resetInternalConsole();
		Tab.up().addClassName('on');
	},
	
	addTab: function(userOptions)
	{
		var options = {
			active			: false,
			content			: 'Test',
			specialClass	: null,
			fn				: function(){},
			id				: null
		}
		Object.extend(options, userOptions);
		
		var Tab = new Element('div').addClassName('tabStyle').observe('click', options.fn);
		if(options.id != null) Tab.id = options.id;
		if(options.active == true)
			Tab.addClassName('on');
		if(options.specialClass != null)
			Tab.addClassName(options.specialClass);
		Tab.update('<a onclick="FF.UI.activateTab(this);">'+options.content+'</a>');
		if ($('tabContainer')) {
			$('tabContainer').insert(Tab);
			FF.UI.checkContentSize();
		}
	},
	
	addContentTab: function(userOptions) 
	{
		var opt = {
			active	: false,
			label	: 'New tab',
			fn		: function(){alert("Please define a function!")}
		}
		Object.extend(opt, userOptions);
		
		if($('contentWrapper')) {
			// Ok, il container c'è.
			if(!$('contentWrapperTabber')) {
				var cwt = new Element('div', {id: 'contentWrapperTabber'})
				cwt.insert(new Element('div').setStyle({position: 'relative'}));
				$('contentWrapper').insert(cwt);
				$('contentWrapper').down().setStyle({paddingBottom: '15px'});
			}
			$('contentWrapper').observe('scroll', function(){
				if($('contentWrapperTabber'))
					$('contentWrapperTabber').style.bottom = -$('contentWrapperTabber').cumulativeScrollOffset()['top'] + 'px';
			})
			var tab = new Element('div').addClassName('contentTab').update(opt.label).observe('click', opt.fn);
			/* Gestisco l'elemento attivo */
			if (opt.active == true) {
				tab.addClassName('selected');
				this.activeContentTab = tab;
			}
			/* Aggiungo gli observer generali per questi tab */
			tab.observe('click', function() {
				FF.UI.resetContentTabs();
				if(FF.UI.activeContentTab != this) {
					this.addClassName('selected');
					FF.UI.activeContentTab = this;
				}
			});
			$('contentWrapperTabber').insert(tab);
		}
	},
	
	resetContentTabs: function()
	{
		if ($('contentWrapperTabber')) {
			$('contentWrapperTabber').descendants().invoke('removeClassName', 'selected');
			FF.UI.activeContentTab = null;
		}
	},
	
	showShortcutLabel: function() 
	{
		var lbl = new Element('div', {id: 'ShortcutLabel'}).update('<h2>SHORTCUTS</h2><div>' + 
			'<strong>c</strong>: ' + Dashboard.dictionary.show_console + '<br />' + 
			'<strong>d</strong>: ' + Dashboard.dictionary.refresh_datas + '<br />' + 
			'<strong>g</strong>: ' + Dashboard.dictionary.search + '<br />' + 
			'<strong>m</strong>: ' + Dashboard.dictionary.toggle_view + '<br />' + 
			'<strong>r</strong>: ' + Dashboard.dictionary.refresh_page + '<br />'
		);
		
		if(FF.AppContext == 'ShowContent')
			lbl.insert('<strong>l</strong>: ' + Dashboard.dictionary.toggle_line_height + '<br />' + 
				'<strong>n</strong>: ' + Dashboard.dictionary.new_data + '<br />'
			);
		
		if(FF.AppContext == 'EditContent' || FF.AppContext == 'InsertContent')
			lbl.insert('<strong>a</strong>: ' + Dashboard.dictionary.apply_changes + '<br />' + 
				'<strong>s</strong>: ' + Dashboard.dictionary.save + '<br />'
			);
		lbl.insert ("</div>");
		document.body.appendChild(lbl);
		lbl.style.top = (document.viewport.getDimensions()['height']/2 - (lbl.getDimensions()['height'] / 2));
		lbl.style.left = (document.viewport.getDimensions()['width']/2 - (lbl.getDimensions()['width'] / 2));
		lbl.style.visibility = 'visible'
	},
	
	toggleAltFunctions: function()
	{
		if ($('toolBar')) {
			$('toolBar').down().childElements().each(function(item){
				if(item.down('u')) {
					item.down().innerHTML = item.down().innerHTML.replace('<u>', '');
					item.down().innerHTML = item.down().innerHTML.replace('</u>', '');
				} else {
					item.down().innerHTML = '<u>' + item.down().innerHTML[0] + '</u>' + item.down().innerHTML.substring(1);
				}
			});
		}
	}
	
}

FF.UI.ContextMenu = {
	
	setConfig: function (tr, e)
	{
		var elem = ( jQuery(Event.element(e)).hasClass ("datasBox") ? Event.element(e) : jQuery(Event.element(e)).parent(".datasBox:first").get(0))
		this.config = {
			father	: tr,
			rowId	: tr.readAttribute('title').split(',')[0],
			fieldId	: tr.readAttribute('title').split(',')[1],
			element	: elem,
			event	: e,
			table	: table
		}
		FF.Contents.highlightRow(tr);
	},
	
	showMenu: function(tr, e) 
	{
		var elem = ( jQuery(Event.element(e)).hasClass ("datasBox") ? Event.element(e) : jQuery(Event.element(e)).parent(".datasBox:first").get(0))
		if(this.config && jQuery ("#rowContextMenu").size() > 0){
			Element.remove(this.config.menu);
			FF.Contents.unhighlightRow(this.config.father);
			this.config.element.up().removeClassName('highlightedElement');
			//this.config.data.transport.abort();
			this.config = null;
		}
		var menu = new Element('div', {id: 'rowContextMenu'});
		menu.style.left = Event.pointerX(e) + 'px';
		menu.style.top = Event.pointerY(e) + 'px';
		document.body.insert(menu);
		
		FF.Contents.highlightRow(tr);
		this.config = {
			father	: tr,
			rowId	: tr.readAttribute('title').split(',')[0],
			fieldId	: tr.readAttribute('title').split(',')[1],
			element	: elem,
			event	: e,
			menu	: menu,
			table	: table
		}
		this.config.element.up().addClassName('highlightedElement');
		var menuContent = this.getMenu();
		menu.update(menuContent);
		
		if((Event.pointerX(e) + menu.getDimensions().width) > document.viewport.getDimensions().width)
			menu.style.left = (Event.pointerX(e) - menu.getDimensions().width) + 'px';
		if((Event.pointerY(e) + menu.getDimensions().height) > document.viewport.getDimensions().height)
			menu.style.top = (Event.pointerY(e) - menu.getDimensions().height) + 'px';
		
		document.body.observe('click', this.hideMenu.bind(e));
		document.body.observe('contextmenu', this.hideMenu.bind(e));
		document.body.observe('keypress', this.hideMenu.bind(e));
	},
	
	getMenu: function()
	{
		var ret = '<ul>' + 
			'<li><strong>Row</strong></li>' + 
			'<li><a onclick="FF.report(\'Loading edit console\');" href="edit.php?action=EDIT&table=' + this.config.table + '&editField=' + this.config.fieldId + '&editId=' + this.config.rowId + '">Edit</a></li>' + 
			'<li><a href="javascript:void();" onclick="FF.Contents.deleteRow(\'' + this.config.rowId + '\', \'' + this.config.fieldId + '\', \'' + this.config.table + '\');">Delete</a></li>' + 
			((FF.Contents.selectedRows.length > 1) ? '<li><a href="javascript:void();" onclick="FF.Contents.multipleDelete();">Multiple delete</a></li>' : '') + 
			'<li><a href="javascript:void();" onclick="FF.Contents.selectedRows.push(FF.UI.ContextMenu.config.father.identify()); FF.Contents.duplicateRow();">Duplica</a></li>' + 
			'<li><hr ><strong>Element</strong></li>' + 
			'<li><a href="javascript: void();" onclick="FF.Contents.fastEdit(FF.UI.ContextMenu.config.element)">Edit element</a></li>' + 
			(this.config.element.readAttribute('title') == 'File' ? '<li><a href="javascript: void();" onclick="window.open(\'imageViewer.php?table='+this.config.table+'&file='+this.config.element.innerHTML.strip()+'\', \'imageviewer\', \'width=400, height=300, toolbar=0, scrollbars=0, status=0, location=0, menubar=0\');" target="_blank">View file</a></li>' : '') + 
			'<li><hr ><strong>Table</strong></li>' + 
			'<li><a onclick="FF.Contents.checkAllRows();">' + ((Dashboard.content.rowSelection == 'None') ? 'Select all rows in view' : 'Deselect all rows') + '</a></li>' + 
		'</ul>';
		return ret;
	},
	
	hideMenu: function(e) 
	{
		if($('rowContextMenu')) 
			if (!e || (Event.element(e) != $('rowContextMenu') && $('rowContextMenu').ancestors().indexOf(Event.element(e)) == -1)) {
				FF.UI.ContextMenu.config.menu.remove();
				FF.UI.ContextMenu.config.element.up().removeClassName('highlightedElement');
				FF.Contents.unhighlightRow(FF.UI.ContextMenu.config.father);
				document.body.stopObserving('click', FF.UI.ContextMenu.hideMenu.bind(e));
//				FF.UI.ContextMenu.config.data.transport.abort();
				FF.UI.ContextMenu.config = null;
			}
	}
	
}

FF.UI.CustomFields = {
	
	init: function() 
	{
		/* Creo le combobox */
		$$('.FF_combobox').each(function(f) { FF.UI.CustomFields.Combobox.create(f); });
		
		/* Creo gli input con controlli */
		$$('.FF_textfield').each(function(f) { FF.UI.CustomFields.Textfield.create(f); });
		
		/* Creo caselle di testo avanzate */
		//$$('.FF_textarea').each(function(f) { FF.HtmlEditor.replaceTextarea(f, {height: 100}); });
		jQuery( ".datepicker" ).datepicker( { dateFormat: 'yy-mm-dd' } );
	}
	
}

FF.UI.CustomFields.Textfield = {
	
	sep: ',',
	zindex: Dashboard.ui.zindex.textfield,
	collector: new Array(),
	errortext: new Template('This field is #{type}. Please correct!'),
	create: function(f)
	{
		var textfield = new Element('div').addClassName('FF_textfield_ui').setStyle({width: f.getWidth()});
		f.wrap(textfield);
		
		if(f.readAttribute('restriction')) {
			// Apply restrictions
			f.readAttribute('restriction').split(FF.UI.CustomFields.Textfield.sep).uniq().each(function(r) {
				textfield.down('input').observe('keyup', function() {
					switch(r) {
						case 'numbers':
							if(isNaN(textfield.down('input').value))
								textfield.addClassName('error').writeAttribute('errortype', 'numbers');
							else 
								textfield.removeClassName('error');
							break;
						case 'string':
							break;
						case 'email':
							break;
						case 'notnull':
							break;
					}
					if(!textfield.hasClassName('error') && textfield.down('.errorEvidence'))
						textfield.down('.errorEvidence').remove();
				});
				textfield.down('input').observe('blur', function() {
					if(textfield.hasClassName('error')) 
						FF.UI.CustomFields.Textfield.correctError(textfield);
				});
			});
		}
		
		if(f.readAttribute('datasource')) {
			// Campo con autocomplete, carico i dati
			textfield.down('input').observe('keyup', function() {
				if (this.value.length > 1 && this.value != this.lastSearch) {
					this.lastSearch = this.value;
					new Ajax.Request(f.readAttribute('datasource') + '?action=autocomplete&field_name=' + this.name + '&w=' + this.value, {
						onComplete: function(data){
							eval(data.responseText);
							if (results.length > 0) 
								FF.UI.CustomFields.Textfield.createAutocomplete(textfield, results);
							else 
								if (textfield.down('.autocompleteList')) 
									textfield.down('.autocompleteList').remove();
							
						}
					});
				}
			});
		}
	},
	
	createAutocomplete: function(textfield, items) 
	{
		textfield.absolutize().addClassName('open').setStyle({zIndex: FF.UI.CustomFields.Textfield.zindex++});
		if(textfield.nav != true) this.initKeyboardNavigation(textfield);
		
		if (textfield.down('.autocompleteList')) {
			textfield.down('.autocompleteList').update();
			var list = textfield.down('.autocompleteList');
		} else 
			var list = new Element('ul', {className: 'autocompleteList'});
		items.each(function(item) {
			list.insert(new Element('li').update(item));
		});
		textfield.insert(list.relativize());
	},
	
	initKeyboardNavigation: function(textfield)
	{
		var $this = this;
		textfield.nav = true;
		Event.observe(textfield, 'keyup', function(ev) {
			switch(ev.keyCode) {
				case Event.KEY_TAB:
				case Event.KEY_RETURN:
					$this.selectEntry(textfield);
					Event.stop(ev);
				case Event.KEY_ESC:
					$this.fire(textfield);
					Event.stop(event);
					return;
				case Event.KEY_LEFT:
				case Event.KEY_RIGHT:
					return;
				case Event.KEY_UP:
					$this.markPrevious(textfield);
					Event.stop(ev);
					return;
				case Event.KEY_DOWN:
					$this.markNext(textfield);
					Event.stop(ev);
					return;
				}
		});
	},
	
	markNext: function(textfield)
	{
		if(textfield.select('.selected').length > 0) {
			textfield.down('.selected').removeClassName('selected').next().addClassName('selected');
		} else 
			textfield.down('li').addClassName('selected');
	},
	
	markPrevious: function(textfield)
	{
		if(textfield.select('.selected').length > 0) {
			textfield.down('.selected').removeClassName('selected').previous().addClassName('selected');
		} else 
			textfield.select('li').last().addClassName('selected');
	},
	
	selectEntry: function(textfield) 
	{
		textfield.down('input').value = textfield.down('.selected').innerHTML;
		FF.UI.CustomFields.Textfield.fire(textfield);
	},
	
	fire: function(textfield) 
	{
		textfield.down('ul').remove();
		textfield.down('input').nav = false;
		textfield.relativize().removeClassName('open');
	},
	
	correctError: function(f) 
	{
		f.relativize().focus();
		switch(f.readAttribute('errortype')) {
			case 'numbers':
				var errortext = FF.UI.CustomFields.Textfield.errortext.evaluate({type: 'numeric only'});
		}
		var t = new Element('div').addClassName('errorEvidence').update(errortext);
		f.insert(t);
		t.setStyle({left: (f.getWidth()-5) + 'px'})
	}
	
}

FF.UI.CustomFields.Combobox = {
	
	sep: ',',
	zindex: Dashboard.ui.zindex.combobox,
	collector: new Array(),
	create: function(f)	
	{
		var combobox = new Element('div').addClassName('FF_combobox_ui').update('<label>Seleziona</label>');
		combobox.writeAttribute('multiple', (f.multiple) ? true : false);
		if(f.multiple && f.readAttribute('separator'))
			this.sep = f.readAttribute('separator');
		if(f.select('option').length > 0)
			combobox.insert(new Element('ul').writeAttribute('comboselect', f));
		var comboValue = '';
		f.select('option').each(function(opt) {
			var item = new Element('li').writeAttribute('value', opt.value).update(opt.innerHTML);
			if (opt.selected) {
				item.addClassName('selected');
				comboValue += opt.innerHTML + ',';
			}
			item.observe('click', FF.UI.CustomFields.Combobox.onItemClick.bind());
			combobox.down('ul').insert(item);
		});
		if(comboValue != '')
			combobox.down('label').update('<span>' + comboValue + '</span>');
		f.wrap(combobox);
		if (f.multiple) {
			combobox.insert(new Element('input', {type: 'hidden', name: f.name, value: ''}));
			Element.remove(f);
		}
		combobox.down('label').observe('click', FF.UI.CustomFields.Combobox.onOpenClose.bind());
		combobox.checkLabel = function() {
			var lbl = '';
			this.select('li.selected').each(function(li) {
				lbl += li.innerHTML + ',';
			});
			if(lbl != '')
				this.down('label').innerHTML = lbl.truncate(-1, '');
			else 
				this.down('label').innerHTML = "Seleziona";
		}
		combobox.assignValues = function() {
			var $this = this;
			if($this.readAttribute('multiple')) 
				$this.down('input').value = '';
			this.select('li').each(function(li) {
				if ($this.readAttribute('multiple')) {
					if (li.hasClassName('selected')) 
						$this.down('input').value += li.readAttribute('value') + FF.UI.CustomFields.Combobox.sep;
				}
				else 
					$this.down('.FF_combobox').down('option[value=' + li.readAttribute('value') + ']').selected = (li.hasClassName('selected')) ? 'selected' : null;
			});
			if ($this.readAttribute('multiple')) 
				$this.down('input').value = $this.down('input').value.truncate(-(FF.UI.CustomFields.Combobox.sep.length), '')
		}
		combobox.checkLabel();
		combobox.assignValues();
		this.collector.push(combobox);
	},
	
	onItemClick: function()
	{
		var $this = this;
		if(!this.up('.FF_combobox_ui').readAttribute('multiple')) {
			FF.UI.CustomFields.Combobox.onUncheckItems(this.up(2));
			FF.UI.CustomFields.Combobox.onCloseAll ();
		}
		this.toggleClassName('selected');
		this.up('.FF_combobox_ui').assignValues();
		this.up('.FF_combobox_ui').checkLabel();
	},
	
	onUncheckItems: function(combobox) 
	{
		combobox.select('li.selected').invoke('removeClassName', 'selected');
	},
	
	onOpenClose: function()
	{
		var $combobox = this.up();
		$combobox.checkLabel();
		FF.UI.CustomFields.Combobox.onCloseAll($combobox);
		$combobox.toggleClassName('open');
		if ($combobox.hasClassName('open')) {
			$combobox.wrap('div').addClassName('FF_combobox_ui').addClassName('placeHolder').setStyle({width: $combobox.getWidth() + 'px'});
			$combobox.absolutize().setStyle({zIndex: FF.UI.CustomFields.Combobox.zindex++});
		} else {
			$combobox.relativize().up().wrap($combobox).down('.placeHolder').remove();
		}
	},
	
	onCloseAll: function(except)
	{
		this.collector.each(function(cb) {
			if(cb != except) 
				cb.removeClassName('open').relativize().checkLabel();
		});
	}
	
}

FF.UI.CustomFields.Datafield = {
	
	create: function(f)
	{
		
	},
	
}



/**
 * @author morettip
 * paolo.moretti@valueteam.com
 */
var AgendaClosing = false;
var Agenda = {
	
	defaultOptions: {
		months			: {
			it	: new Array('GENNAIO', 'FEBBRAIO', 'MARZO', 'APRILE', 'MAGGIO', 'GIUGNO', 'LUGLIO', 'AGOSTO', 'SETTEMBRE', 'OTTOBRE', 'NOVEMBRE', 'DICEMBRE'),
			en	: new Array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER')
		},
		weekDaysName		: {
			it	: new Array('Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'),
			en	: new Array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')
		},
		monthsDaycount		: new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31),
		xmlFile			: false,
		id			: 'AppCalendar',
		returnDayOnClick	: true, 
		initDateReference	: true,
		returnField		: false,
		opener			: null,
		dateFormat		: new Template('#{day}#{sep}#{month}#{sep}#{year}'),
		dateFormatIndexer	: new Array('day', 'month', 'year'),
		dateSeparator		: '/',
		vposition		: 'bottom',
		hposition		: 'left',
		closeOnReturn		: true,
		skin			: 'invisible',
		view			: 'normal',
		locale			: 'it'
	},
	settings: {		
		dayContents		: new Array(),
		today			: new Date(),
		year			: new Date().getFullYear(),
		filters			: new Array(),
		eventsList		: new Array(),
		xmlParamsObj		: {
			year	: new Date().getFullYear(),
			month	: new Date().getMonth()+1
		}
	},
	UI: {
		app					: function() {return new Element('div', {id: 'AppCalendar'}); },
		monthLabel			: function() {return new Element('div', {id: 'monthLabel'}); },
		monthDaysContainer	: function() {return new Element('div', {id: 'monthDays'}); },						// Contenitore dei giorni
		nextMonthBtn		: function() {return new Element('div', {id: 'nextMonthBtn'}); },					// Pulsante per il mese successivo
		prevMonthBtn		: function() {return new Element('div', {id: 'prevMonthBtn'}); },					// Pulsante per il mese precedente
		getMonthDayHolder	: function() {return new Element('li').insert(new Element('a').insert(new Element('span')))},	
		eventsDetail		: function() {return new Element('div', {id: 'eventDetail'}); },
		getFocusTitle		: function() {return new Element('h1') },
		focusLink			: function() {return new Element('a').addClassName('listLink') },
		focusContainer		: function() {return new Element('div', {id: 'singleEvent'} )},
		focusEventBox		: function() {return new Element('div', {id: 'singleEvent'}).insert(new Element('h1').addClassName('listLink')) },
		agendaRows			: function() {return new Element('div', {id: 'agenda_table_rows'}); }
	},
	LBL: {
		Today			: 'Oggi, ',
		AddFileTip		: 'Seleziona i formati che ti interessano cliccando il tipo di file'
	},
	
	init: function(userOptions) {
		// Anni bisestili
		this.checkLead();
		// Integro le opzioni di default con quelle definite dall'utente
		this.options = Object.clone(this.defaultOptions);
		Object.extend(this.options, userOptions);
		this.UI.app.style.display = 'block';
		this.checkDateFormat();
		if(this.options.opener != null) {
			this.options.opener.style.cursor = 'pointer';
		}
		if(this.options.xmlFile)
			this.loadXML(this.options.xmlFile);
		else 
			this.buildNewAgenda();
	},
	
	checkDateFormat: function() 
	{
		if(this.options.locale == 'en') {	// Risetto il formato a mese/giorno/anno
			this.options.dateFormat = new Template('#{month}#{sep}#{day}#{sep}#{year}');
			this.options.dateFormatIndexer = new Array('month', 'day', 'year');
		} else {
			this.options.dateFormat = new Template('#{day}#{sep}#{month}#{sep}#{year}');
			this.options.dateFormatIndexer = new Array('day', 'month', 'year');
		}
	},
	
	unload: function()
	{
		this.UI.app.hide();
	},
	
	getXmlParams: function()
	{
		return "?" + Object.toQueryString(this.settings.xmlParamsObj);
	},
	
	checkLead: function()
	{
		var isLeap = new Date(this.settings.today.getFullYear(),1,29).getDate() == 29;
		if(isLeap) 
			this.defaultOptions.monthsDaycount[1] = 29;
	},
	
	loadXML: function(xmlFile) {
		AgendaBaloon.init();
		if(document.all) {
			var http = new ActiveXObject("Microsoft.XMLHTTP");
			http.open("GET", xmlFile + this.getXmlParams(), false);
			http.send(null);
			this.xmlDoc = http.responseXML;
			this.eventsList = this.xmlDoc.getElementsByTagName('item');
			// Inizializzo il primo
			this.buildNewAgenda();
		} else {
			new Ajax.Request(xmlFile + this.getXmlParams(), {method: 'get', onFailure: Agenda.handlerERROR, onSuccess: function(ajax){ Agenda.handlerXML(ajax); }});
		}
		
	},
	
	handlerXML: function(ajax) {
		if(document.all) 
			this.xmlDoc = ajax.responseText;
		else 
			this.xmlDoc = ajax.responseXML

		this.eventsList = this.xmlDoc.getElementsByTagName('item');
		// Inizializzo il primo
		this.buildNewAgenda();
	},
	
	buildNewAgenda: function(m) {
		if(this.options.view == 'mini') 
			this.UI.app.addClassName('mini');
		if(m) {
			this.settings.today = new Date(this.settings.today.getFullYear(),(this.settings.today.getMonth()+m), this.settings.today.getDate());
			
			if(this.settings.today.getFullYear() != this.settings.year) {
				// E' cambiato l'anno, devo richiedere un nuovo XML
				this.settings.year = this.settings.today.getFullYear();
				this.settings.xmlParamsObj.year = this.settings.today.getFullYear();
				this.settings.xmlParamsObj.month = this.doubbleCharDateObj(this.settings.today.getMonth() + 1);
				
			} else 
				this.settings.xmlParamsObj.month = this.doubbleCharDateObj(this.settings.today.getMonth() + 1);
			// Check id leap year
			if(this.options.xmlFile)
				this.loadXML(this.options.xmlFile);
			else 
				this.buildAgenda();
			
		} else {
			// Check id leap year
			var isLeap = new Date(this.settings.today.getFullYear(),1,29).getDate() == 29;
			if(isLeap) 
				this.options.monthsDaycount[1] = 29;
			
			this.buildAgenda(true);
		}
	},
	
	handlerERROR: function(ajax) {
		 alert('Error ' + ajax.status + ' -- ' + ajax.statusText);
	},
		
	buildAgenda: function(first) {
		if(this.options.initDateReference && first) {
			// Guardo la data del campo di riferimento e costruisco il calendario in base a quella
			var refDate = this.getDateObjFromString($(this.options.returnField).value);
			if (refDate) {
				this.settings.today.setDate(refDate.day);
				this.settings.today.setMonth(refDate.month - 1);
				this.settings.today.setFullYear(refDate.year);
			} else 
				this.settings.today = new Date();
		}

		// Setto mese e giorno
		this.UI.monthLabel.update(this.options.months[this.options.locale][this.settings.today.getMonth()] + ' ' + this.settings.today.getFullYear());
		this.options.currentMonth = this.settings.today.getMonth();
		
		// Costruisco il calendario
		var firstDay = new Date(this.settings.today.getTime() - ((this.settings.today.getDate()-1) * 24 * 60 * 60 * 1000));
		var dayOfWeek = firstDay.getDay();
		if(dayOfWeek == 0) 
			dayOfWeek = 7;

		this.UI.monthDaysContainer.update();
		var dayBuildingStarted = false;
		var actDay = 1;
		
		// Creazione calendario....
		// Creazione nomi dei giorni della settimana
		$('weekDaysHeader').update('');
		$('weekDaysHeader').insert(new Element('ul'));
		this.options.weekDaysName[this.options.locale].each(function(e, ind) {
			var wdn = new Element('li').insert(new Element('strong').update(e));
			if(ind == 5 || ind == 6)
				wdn.addClassName('fest');
			$('weekDaysHeader').down('ul').insert(wdn);
		});
		$('weekDaysHeader').insert(new Element('br').setStyle({clear: 'both'}));
		var skipBuild = false;
		
		// Inserimento giorni del mese
		for(var i=0; i<42; i++) {
			var dayHolder = this.UI.getMonthDayHolder();
			if(dayOfWeek == (i+1) || dayBuildingStarted == true && actDay <= this.options.monthsDaycount[this.options.currentMonth]) {
				dayBuildingStarted = true;
				dayHolder.id = 'agenda_day_'+actDay;
				dayHolder.down('span').update(actDay);
				dayHolder.writeAttribute('returnDate', actDay);
				var t = new Date();
				t.setMonth(this.options.currentMonth);
				t.setFullYear(this.settings.year);
				t.setDate(actDay);
				dayHolder.writeAttribute('weekday', (t.getDay() == 0 ? 6 : (t.getDay() - 1)));
				if(t.getDay() == 0 || t.getDay() == 6)
					dayHolder.addClassName('festDay');
				actDay++;
			} else {
				if (i%7==0 && dayBuildingStarted ==true) {
					skipBuild = true;
					dayHolder.down('span').update('&nbsp;');
				}
			}
			if (this.options.returnDayOnClick) {
				dayHolder.down('span').setStyle({cursor: 'pointer'});
				dayHolder.down('a').onclick = function(){
					var date = {
						day	: this.up().readAttribute('returnDate'),
						month	: ++Agenda.options.currentMonth,
						year	: Agenda.settings.year,
						sep	: Agenda.options.dateSeparator
					}
					date = Agenda.doubbleCharDateObj(date);
					$(Agenda.options.returnField).value = Agenda.options.dateFormat.evaluate(date);
					if(Agenda.options.closeOnReturn)
						Agenda.unload();
					if($(Agenda.options.returnField).updateDatagrid) 
						$(Agenda.options.returnField).updateDatagrid();
				}
			}
			if(!skipBuild) {
				this.UI.monthDaysContainer.appendChild(dayHolder);
				if(this.options.xmlFile)
					this.setDayProperties(actDay-1);
			}
		}
		// Posiziono il calendario
		if (this.options.opener) {
			var voffset = Element.getHeight(this.options.opener);
			var hoffset = Element.getWidth(this.options.opener);
			var fix = this.options.opener.viewportOffset().top;
			if (this.options.vposition == 'top') 
				voffset = -this.UI.app.getHeight();
			if (this.options.hposition == 'left') 
				hoffset = -this.UI.app.getWidth();
			this.UI.app.setStyle({
				position: 'absolute',
				zIndex: 99999999,
				background: '#fff',
				left: this.options.opener.viewportOffset().left + this.options.opener.cumulativeScrollOffset().left + hoffset + 'px',
				top: this.options.opener.viewportOffset().top + this.options.opener.cumulativeScrollOffset().top + voffset + 'px'
			}).setStyle({
				top: this.options.opener.viewportOffset().top + this.options.opener.cumulativeScrollOffset().top + voffset + 'px'
			});
		}
		// Valorizzo i bottoni
		this.UI.prevMonthBtn.onclick = function(){Agenda.buildNewAgenda(-1);}
		this.UI.nextMonthBtn.onclick = function(){Agenda.buildNewAgenda(1);}
		
		// Evento close
		if (this.options.skin == 'invisible') {
			// Se sono in configurazione invisibile, faccio chiudere il calendario al blur
			Event.observe(document.body, 'click', function(e){
				if (Event.element(e) != Agenda.UI.app &&
				Event.element(e).ancestors().indexOf(Agenda.UI.app) == -1 &&
				Event.element(e).ancestors().indexOf(Agenda.options.opener) == -1) 
					Agenda.unload();
			});
		} else 
			this.UI.app.setStyle({display: 'block'});
		if ($$('.Legenda').length > 0) 
			$$('.Legenda')[0].style.marginTop = (this.UI.monthDaysContainer.offsetHeight - 204) + 'px';
	},
	
	emailChecker: function(mail) {
		
		return mail.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.tv)|(\..{2,2}))$)\b/gi);
		
	},
	
	setDayProperties: function(d) {
		var dayEvents = Agenda.searchEventsByDay(d);
		if(dayEvents.length > 0 && d > 0) {
			var types = new Array();
			dayEvents.each(function(e) {
				if(e.getAttribute('type') == 4)
					var eventTypeSrc = 'event_type_4.gif';
				else if(e.getAttribute('type') == 1 || e.getAttribute('type') == 3)
					var eventTypeSrc = 'event_type_13.gif';
				else
					var eventTypeSrc = 'event_type_025.gif';
				if (Agenda.options.view == 'mini')
					eventTypeSrc = 'm_' + eventTypeSrc;
				if(types.indexOf(eventTypeSrc) == -1) {
					types.push(eventTypeSrc);
					var c = new Element('img', {src: '/shared/i/' + eventTypeSrc});
					c.setStyle({
						position	: 'absolute',
						right		: '3px',
						bottom		: (11 * (types.length-1) + 4) + 'px'
					});
					$('agenda_day_'+d).appendChild(c);
				}
			});
			$('agenda_day_'+d).observe('mousemove', function(){
				AgendaBaloon.showBaloon(this);
			});
			$('agenda_day_'+d).observe('mouseout', function(){
				AgendaBaloon.hideBaloon(this);
			});
		}
	},
	
	searchEventsByDay: function(d) {
		var eventsOnDay = new Array();
		for(var i=0; i<this.eventsList.length; i++) {
			var date = this.eventsList[i].getAttribute('date').split("-");
			if(Number(date[2]) == d) { // && parseInt(date[1]) == (this.options.currentMonth+1)
				// Evento nel giorno "d"
				eventsOnDay.push(this.eventsList[i]);
			}
		}
		return eventsOnDay;
	},
	
	searchEventsByDate: function(date) {
		var searchDate = (parseInt(date.getMonth())+1) + "-" + parseInt(date.getDate());
		var eventsOnDay = new Array();
		
		for(var i=0; i<this.eventsList.length; i++) {
			var eventDate = this.eventsList[i].getAttribute('date').split("-");
			var eventDate = parseInt(eventDate[this.options.dateFormatIndexer.indexOf('month')]) + "-" + parseInt(eventDate[this.options.dateFormatIndexer.indexOf('year')]);
			if(eventDate == searchDate) {
				// Evento nel giorno "d"
				eventsOnDay.push(this.eventsList[i]);
			}
		}
		return eventsOnDay;
	},
	
	getDateObjFromString: function(str)
	{
		if(str != '' && str != null && str != undefined) {
			var ret = str.split(this.options.dateSeparator);
			var date = {
				day	: ret[this.options.dateFormatIndexer.indexOf('day')],
				month	: ret[this.options.dateFormatIndexer.indexOf('month')],
				year	: ret[this.options.dateFormatIndexer.indexOf('year')],
				sep	: this.options.dateSeparator
			}
			return date;
		} else 
			return false;
	},
	
	doubbleCharDateObj: function(Obj)
	{
		Object.keys(Obj).each(function(k) {
			if(Number(Obj[k]) < 10) 
				Obj[k] = String('0' + Obj[k]);
		});
		return Obj;
	},
	
	doubbleCharNumber: function(n)
	{
		if(Number(n) < 10) 
			n = String('0' + n);
		return n;
	}
	
}

/*
var AgendaBaloon = {
	
	offset_x: 66,
	offset_y: 12,
	
	init: function() {
		this.setBaloonPosition();
		$('popup_agenda').onmousemove = function() {
			AgendaBaloon.show();
			this.itemOver = false;
		}
		$('popup_agenda').onmouseout = function() {
			AgendaClosing = true;
			AgendaBaloon.hide();
		}
	},
	
	createBaloon: function()
	{
		var b = new Element('div', {id: 'popup_agenda'});
		var bc = new Element('div', {id: 'popup_agenda_content'});
		bc.insert(new Element('div', {id: 'popup_agenda_day'}));
		bc.insert(new Element('div', {id: 'popup_agenda_weekday'}));
		bc.insert(new Element('ul', {id: 'popup_agenda_list'}));
		b.insert(bc);
		b.insert(new Element('img', {width: '257', height: '29', id: 'popup_agenda_closer', src: '/shared/i/agenda_baloon_b.png'}));
		document.body.appendChild(b);
	},
	
	setBaloonPosition: function() 
	{
		//document.body.appendChild($('popup_agenda'));
	},
	
	show: function() {
		if(AgendaClosing) 
			AgendaClosing = false
		if (this.prevItemOver != this.itemOver) {
			this.populateBaloon();
			$('popup_agenda').showing = true;
			this.showing = true;
			if(Agenda.options.view != 'mini') {
				$('popup_agenda').setStyle({
					position	: 'absolute',
					left		: this.itemOver.viewportOffset().left + this.offset_x - $('popup_agenda').getDimensions().width + 'px',
					top		: this.itemOver.viewportOffset().top + this.offset_y + this.itemOver.cumulativeScrollOffset().top - $('popup_agenda').getDimensions().height + 'px',
					zIndex		: 5000,
					display		: 'block'
				});
			} else {
				$('popup_agenda').setStyle({
					position	: 'absolute',
					left		: this.itemOver.offsetLeft + this.offset_x - $('popup_agenda').offsetWidth + 'px',
					top		: this.itemOver.offsetTop + this.offset_y - $('popup_agenda').offsetHeight + 'px',
					zIndex		: 5000,
					display		: 'block'
				});
			}
		}
	},
	
	hide: function() {
		setTimeout(function(){
			if(AgendaClosing === true) {
				AgendaBaloon.prevItemOver = '##';
				this.prevItemOver = '##';
				AgendaBaloon.showing = false;
				$('popup_agenda').setStyle({
					position	: 'absolute',
					zIndex		: 5,
					top		: '-2000px'
				});
			}
		}, 100);
	},
	
	populateBaloon: function() {
		$('popup_agenda_list').update();
		$('popup_agenda_day').update(Agenda.doubbleCharNumber(this.itemOver.down('span').innerHTML));
		$('popup_agenda_weekday').update(Agenda.options.weekDaysName[Agenda.options.locale][this.itemOver.getAttribute('weekday')]);
		var events = Agenda.searchEventsByDay(this.itemOver.readAttribute('returnDate'));
		events.each(function(ev) {
			var link = new Element('li').addClassName("type" + ev.getAttribute('type'));
			link.appendChild(new Element('a', {href: ev.getElementsByTagName('link')[0].getAttribute('href')}).update(ev.getElementsByTagName('link')[0].firstChild.nodeValue))
			if(ev.getAttribute('reminder') == 'true') {
				link.setAttribute('reminderUrl', '/' + Agenda.options.locale + '/tools/alert/sendalertsingle_form.jsp?url=' + ev.getElementsByTagName('link')[0].getAttribute('href'));
				link.insert(new Element('a').addClassName('set_reminder').update('> SET REMINDER').setStyle({position: 'relative'})).observe('click', function() {
					window.location = this.getAttribute('reminderUrl');
				});
			}
			$('popup_agenda_list').appendChild(link);
		});
		$('popup_agenda_list').relativize();
		$('popup_agenda_content').setStyle({paddingBottom: '20px'});		
	},

	showBaloon: function(elem) {
		AgendaClosing = false;
		this.prevItemOver = this.itemOver;
		this.showing = true;
		this.itemOver = elem;
		this.show();
	},
	
	hideBaloon: function(elem) {
		AgendaClosing = true;
		if(this.showing === true) 
			this.hide();
	}
}
*/