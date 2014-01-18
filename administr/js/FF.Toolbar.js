// JavaScript Document

FF.Toolbar = {
	
	init: function(){
		if ($('toolBar').down().tagName == 'UL') {
			$('toolBar').down().childElements().each(function(e, i){
				e.onclick = function(){
					if (FF.Toolbar.activeMenu == this) 
						FF.Toolbar.CloseItem(this);
					else 
						FF.Toolbar.OpenItem(this);
				}
			});
		} else 
			alert("ERROR. No toolbar found.\nTry reloading the page!")
		FF.EventManager.addDocumentClickAction('if(Event.element(e) && Element.ancestors(Event.element(e)).indexOf($("toolBar")) == -1) FF.Toolbar.CloseItem(FF.Toolbar.activeMenu, true)');
	},
	
	ClosePanels: function() {
		$$('#toolBar li.on').each(function(e) {
			e.removeClassName('on');
			e.lastChild.style.display = 'none';
		});
	},
	
	CloseItem: function(e, resetToolbar) {
		this.activeMenu = false;
		if(e) {
			if(Element.cleanWhitespace(e).lastChild) 
				Element.cleanWhitespace(e).lastChild.style.display = 'none';
			Element.removeClassName(e, 'on');
			e.onclick = function() {
				if(FF.Toolbar.activeMenu == this) 
					FF.Toolbar.CloseItem(this, false); 
				else 
					FF.Toolbar.OpenItem(this); 
			}
			if (resetToolbar == true) {
				$('toolBar').down().childElements().each(function(e, i){
					e.onmouseover = function(){
					}
				});
			}
		}
	},
	
	OpenItem: function(e) {
		if($('toolBar').down().tagName == 'UL') {
			$('toolBar').down().childElements().each(function(e, i){
				FF.Toolbar.CloseItem(e, false);
				e.onmouseover = function() {
					FF.Toolbar.OpenItem(this);
				}
			});
		}
		e.onclick = function(){};
		FF.Toolbar.activeMenu = e;
		if(e) {
			var sl = Element.cleanWhitespace(e).lastChild;
			sl.style.display = 'block';
			Element.addClassName(e, 'on');
			
		}
		FF.EventManager.addEscAction(function() {
			if(FF.Toolbar.activeMenu != false) 
				FF.Toolbar.CloseItem(FF.Toolbar.activeMenu, true);
		});
		this.runKeyboardController = true;
	},
	
	runKeyboardController: function() 
	{
		// Imposto la navigazione da tastiera
		var active = $('toolBar').getElementsByTagName('on');
		
	}
	
}


FF.Toolbar.Finder = {
	
	call: false,
	finding: false,
	init: function(Obj, table)
	{
		if(Obj) {
			var $this 	= this;
			this.table	= table;
			this.Obj 	= $(Obj);
			if (this.Obj != null) {
				var $Finder = this.Obj;
				this.defaultText = this.Obj.value;
				this.Obj.observe('click', function(e){
					if ($Finder.value == $this.defaultText) 
						$Finder.value = '';
				});
				this.Obj.observe('keyup', this.onKeyUp);
			}
		}
		FF.EventManager.addDocumentClickAction('if(Event.element(e) && Element.ancestors(Event.element(e)).indexOf($("FinderPanel")) == -1 && Event.element(e) != $(\'FinderInput\')) FF.Toolbar.Finder.onBlur();');
	},
	/*
	onBlur: function()
	{
		clearInterval(this.intID);
		$this = FF.Toolbar.Finder;
		if($this.Obj) 
			$this.Obj.value = $this.defaultText;
		if ($this.panel) {
			Element.remove($this.panel);
			$this.panel = null;
			FF.UI.fireFadeLayer();
		}
		if ($('FinderContainer')) {
			$('FinderContainer').removeClassName('finding');
			$('FinderContainer').relativize();
		}
	},
	
	onKeyUp: function() {
		$this = FF.Toolbar.Finder;
		if($this.Obj.value.length >= 2 && $this.Obj.value != $this.defaultText) {
			if(!$this.panel) 
				$this.panel = $this.createPanel();
			$('PanelMsg').update('Finding: <strong>'+$this.Obj.value+'</strong>');
			
			// Chiamo la response
			var $req = 'inc/ajaxRequests.php?action=find&table='+$this.table+'&w=' + $this.Obj.value;
			if($('FinderTruncate') && $F('FinderTruncate'))
				$req += '&truncate=' + $F('FinderTruncate');
			if($this.call)
				$this.call.transport.abort();
			$this.call = new Ajax.Updater('PanelResults', $req, {onComplete: function(){$this.call = false;}});
			$('PanelResults').update('searching...');
		}
	},
	*/
	
	onBlur: function()
	{
		$this = FF.Toolbar.Finder;
		if($this.Obj && $this.Obj.value == '') 
			$this.Obj.value = $this.defaultText;
		if($('loadIcon'))
			$('loadIcon').hide();
		if($this.finding) 
			FF.UI.fireFadeLayer();
		if ($('FinderContainer')) {
			$('FinderContainer').removeClassName('finding');
			$('FinderContainer').relativize();
		}
		$this.finding = false;
	},
	
	onKeyUp: function() {
		$this = FF.Toolbar.Finder;
		if($this.Obj.value.length >= 2 && $this.Obj.value != $this.defaultText) {
			if($('ContentDataTBodyFinder')) 
				FF.Toolbar.Finder.removeResults();
			$this.finding = true;
			/* Chiamo la response */
			var $req = 'inc/ajaxRequests.php?action=find&table='+$this.table+'&w=' + $this.Obj.value;
			if($('FinderTruncate') && $F('FinderTruncate'))
				$req += '&truncate=' + $F('FinderTruncate');
			if($this.call)
				$this.call.transport.abort();
			$this.call = new Ajax.Request($req, {
				onComplete: function(res) {
					$this.call = false;
					if (res.responseText.strip() == '<no_result>') 
						FF.report('No result for this keyword!!', 'Error');
					else 
						if (!res.responseText.strip().empty()) {
							var rows = $this.highlightCode(res.responseText, $this.Obj.value);
							$('ContentDataTBody').up().insert(new Element('tbody', {id: 'ContentDataTBodyFinder'}).insert(rows));
							$('ContentDataTBody').hide();
							$this.onBlur();
							FF.UI.setRowsProperies();
							if(!$('searchRemover')) 
								FF.UI.addTab({
									id: 'searchRemover',
									content: 'Cancella i risultati ricerca',
									specialClass: 'Error',
									fn: function() {FF.Toolbar.Finder.removeResults();}
								});
							if($$('.pager').size() > 0)
								$$('.pager')[0].hide();
						}
				}
			});
			FF.UI.createFadeLayer();
			if (!$('loadIcon')) {
				var l = new Element('img', { id: 'loadIcon', src: 'i/loader.gif'}).setStyle({position: 'absolute', zIndex: 1001});
				document.body.insert(l);
				FF.UI.CenterLayer(l);
			} else 
				$('loadIcon').show();
			
			$('FinderContainer').addClassName('finding').absolutize().setStyle({zIndex: 1000});
			FF.EventManager.addEscAction(function() {
				FF.Toolbar.Finder.onBlur();
			});
		}
	},
	
	removeResults: function()
	{
		$('ContentDataTBody').show(); 
		$('ContentDataTBodyFinder').remove(); 
		$('searchRemover').remove();
		if($$('.pager').size() > 0)
			$$('.pager')[0].show();
	},
	
	highlightCode: function(code, w)
	{
		return code.gsub(w, '<span class=highlight><strong>'+w+'</strong></span>');
	},
	
	/*
	createPanel: function() 
	{
		var $this = this;
		FF.UI.createFadeLayer();
		var Panel = new Element('div', {id: 'FinderPanel'}).setStyle({minWidth: 200});
		var PanelMsg = new Element('div', {id: 'PanelMsg'}).update('Finding...');
		var PanelButtons = new Element('div', {id: 'PanelButtons'}).setStyle({
			position	: 'absolute',
			top			: '6px',
			right		: '7px',
			textAlign	: 'right'
		}).update('Truncate: <input type="text" size="2" id="FinderTruncate" value="200" />');
		var PanelResults = new Element('div', {id: 'PanelResults'}).update('<h4>Results:<br /><br /></h4>');
		Panel.appendChild(PanelMsg);
		Panel.appendChild(PanelButtons);
		Panel.appendChild(PanelResults);
		$('toolBar').appendChild(Panel);
		
		this.intID = setInterval(function() {
			
			if($this.panel.getDimensions()['width'] > (document.viewport.getDimensions()['width'] * .8))
				$this.panel.style.width = (document.viewport.getDimensions()['width']  * .8) + 'px';
			
			if($this.panel.getDimensions()['height'] > (document.viewport.getDimensions()['height']  * .8))
				$('PanelResults').style.height = (document.viewport.getDimensions()['height']  * .8) + 'px';
			
		}, 500);
		$('FinderContainer').addClassName('finding').absolutize().setStyle({zIndex: 1000});
		FF.EventManager.addEscAction(function() {
			FF.Toolbar.Finder.onBlur();
		});
		return Panel;
	}
	*/
	
}
