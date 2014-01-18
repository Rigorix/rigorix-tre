// JavaScript Document
var EM_intID;		// Id intervallo doppio click
var EM_short_intID;	// Id intervallo shortcut

FF.EventManager = {
	
	dblclick_speed	: 200,
	shortcut_time	: 200,
	EscActions		: new Array(),		// Mappa di elementi => funzioni da controllare alla pressione del tasto esc
	ObserveRelease	: new Array(),
	ClickActions	: new Array(),
	pressed			: false,
	dblObserver		: false,
	runShortcut		: false,
	runAltFunctions: false,
	shortcut_key	: 'f',
	
	addEscAction: function(f) 
	{
		FF.EventManager.EscActions.push(f);
	},
	
	addReleaseObserver: function(k, fn)
	{
		FF.EventManager.ObserveRelease[k] = fn;
	},
	
	addDocumentClickAction: function(f) {
		FF.EventManager.ClickActions.push(f);
	},	
	
	runDocumentClickAction: function(e) {
		FF.EventManager.ClickActions.each(function(f, i){
			if(Object.isString(f))
				eval(f);
			if(Object.isFunction(f))
				f();
		});
	},
	
	runKeyDownMapper: function(e) 
	{
		var $this = FF.EventManager;
		var KeyID = (window.event) ? event.keyCode : e.keyCode;
		// Controllo se ci sono tasti da osservare alla pressione
		
		switch(KeyID) {
			
			case 18:	// ALT
				// Attivo il menu
				if ($this.runAltFunctions == false) {
					$this.runAltFunctions = true;
					FF.UI.toggleAltFunctions();
				}
				break;

		}
	},
	
	runKeyMapper: function(e) {
		var $this = FF.EventManager;
		
		if($this.runShortcut == true && $this.getRealChar(e) != $this.shortcut_key) {
			// Combinazione di tasti, la verifico nella mappatura
			$this.callShortcut($this.pressed, e);
		}
		if($this.pressed == false) {
			FF.EventManager.resetShortcut();
			// Controllo il doppioclick
			if($this.getRealChar(e) == $this.getRealChar($this.dblObserver)) {
				// E' un doppioclick
				FF.debug(" - <strong>Doppio click di " + $this.getRealChar(e) + "</strong>");
				$this.doubleKeyPress(e);
			}
			// Imposto il controllo per lo shortcut
			EM_short_intID = setInterval(function() {
				FF.EventManager.resetShortcut();
				if ($this.getRealChar($this.pressed) == $this.shortcut_key) {
					FF.debug(" - <strong>runShortcut = true</strong>");
					$this.runShortcut = true;
					FF.UI.showShortcutLabel();
				}
			}, $this.shortcut_time);
			clearInterval(EM_intID);
			$this.pressed = e;
			$this.dblObserver = e;
			// Imposto un timer per rilevare i doppi click
			EM_intID = setInterval(function() {
				clearInterval(EM_intID);
				$this.dblObserver = false;
			}, $this.dblclick_speed);
		}
		
	},
	
	runKeyReleaseMapper: function(e) 
	{
		FF.EventManager.resetAltFunctions(e);
		FF.EventManager.resetShortcut();
		FF.EventManager.runShortcut = false;
		FF.EventManager.singleKeyPress(e);
		FF.EventManager.pressed = false;
	},
	
	resetShortcut: function()
	{
		if($('ShortcutLabel'))
			Element.remove('ShortcutLabel');
		clearInterval(EM_short_intID);
		FF.EventManager.runShortcut = false;
	},
	
	resetAltFunctions: function(e)
	{
		if (FF.EventManager.runAltFunctions == true) {
			Event.stop(e);
			// Resetto anche le funzioni alla pressione del tasto ALT
			FF.UI.toggleAltFunctions();
			FF.EventManager.runAltFunctions = false;
		}
	},
	
	singleKeyPress: function(e) {
		// Check special keys
		var KeyID = (window.event) ? event.keyCode : e.keyCode;
		switch(KeyID) {
			
			case 27:	// ESC
				this.EscActions.each(function(e){
					e();
				});
				break;
				
			case 40:
				if(FF.Toolbar && FF.Toolbar.runKeyboardController == true) {
					alert("codice 40")
				}
				break;
			
			default:
				var _char = FF.EventManager.getRealChar(e);
				switch(_char) {
					
					// Alt + F => apri il menù da tastiera
					case 'F':
						if(FF.EventManager.runAltFunctions == true) {
							alert("ff")
							Event.stop(e);
							var menuItem = $('toolBar').down().childElements()[0];
							if (FF.Toolbar.activeMenu == menuItem) 
								FF.Toolbar.CloseItem(menuItem);
							else 
								FF.Toolbar.OpenItem(menuItem);
						}
						break;
					
				}
		}
	},
	
	doubleKeyPress: function(e) {
		if(e.keyCode == Event.KEY_TAB) {
			// TAB PRESSURE
		} else {
			
			/*
			 * Spostate le funzione tramite gli shortcut "f + *"
			 * 
			var _char = FF.EventManager.getRealChar(e);
				switch(_char) {
	
					case 'm': 
						//Massimizzo o normalizzo la visualizzazione
						FF.UI.toggleMenuView();
						FF.UI.toggleHeaderView();
						break;
						
					case 'c':
						// Apro la console
						window.open(RootDir + 'Console.php', 'newconsole', 'width=500,height=550');
						break;
					
				}
			*/
		}
	},
	
	callShortcut: function(a, b)
	{
		var $this = FF.EventManager;
		$this.resetShortcut();
		var shortcut = $this.getRealChar(a) + '=>' + $this.getRealChar(b);
		$this.pressed = false;
		FF.debug("<br />SHORTCUT " + shortcut);
		switch (shortcut) {
			
			case 'f=>a':
				// Applica le modifiche o i nuovi dati (solo su modifica o inserimento)
				new Ajax.Request(RootDir + 'dataProcessor.php?action=applyChanges', {
					method: 'post',
					parameters: $('editForm').serialize(),
					onSuccess: function() {
						FF.UI.showMessage({
							text: 'Modifica applicata', 
							autohideTime: 1.5
						});
					},
					onFailure: function() {
						alert("onFailure")
					},
					onException: function() {
						alert("onException")
					}
				});
				break;
			
			case 'f=>b':
				// Torno alla lissta
				window.location.href = 'content.php?table=' + FF.CurrentTable;
				break;
			
			case 'f=>m':
				// Massimizzo o normalizzo la visualizzazione
				FF.UI.toggleMenuView();
				FF.UI.toggleHeaderView();
				break;
			
			case 'f=>n':
				// Aggiungo un nuovo dato
				window.location.href = 'edit.php?table='+FF.CurrentTable+'&action=INSERT';
				break;
			
			case 'f=>c':
				// Apro la console
				window.open(RootDir + 'Console.php', 'newconsole', 'width=500,height=550');
				break;
			
			case 'f=>r':
				// Refresh del frame
				window.location.reload();
				break;
			
			case 'f=>s':
				// Salvo il contenuto
				FF.Contents.SAVE();
				break;
				
			case 'f=>g':
				// Finder
				if($('FinderInput')) {
					if ($('FinderInput').value == FF.Toolbar.Finder.defaultText) 
						$('FinderInput').value = '';
				}
				$('FinderInput').focus();
				break;
				
			case 'f=>l':
				// Comprimo le righe
				if (Dashboard.content.rowCompression) 
					FF.Contents.DecompressRows($('ToolbarRowCompressor'));
				else 
					FF.Contents.CompressRows($('ToolbarRowCompressor'));
				break;	
			
			case 'f=>d':
				// Ricarico i dati in pagina
				FF.Contents.refreshDatas();
				break;	
			
			default: 
				$this.pressed = b;
		}
	},
	
	getRealChar: function(e)
	{
		if (e != false && e != null) {
			var code;
			if (!e) 
				var e = window.event;
			if (e.keyCode) 
				code = e.keyCode;
			else 
				if (e.which) 
					code = e.which;
			return String.fromCharCode(code);
		} else 
			return false;
	}
	
}
document.observe("dom:loaded", function() {
	Event.observe(document.body, 'click', FF.EventManager.runDocumentClickAction);
	Event.observe(window, 'keydown', FF.EventManager.runKeyDownMapper);
	Event.observe(window, 'keypress', FF.EventManager.runKeyMapper);
	Event.observe(window, 'keyup', FF.EventManager.runKeyReleaseMapper);
	FF.EventManager.addEscAction(function(e){
		if($('rowContextMenu')) FF.UI.ContextMenu.hideMenu(e);
	});
});

