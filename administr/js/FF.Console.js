// JavaScript Document
FF.Console = {
	
	Console					: false,
	CommandHistory			: new Array(),
	CommandPointer			: false,
	ViewType				: 'inpage',
	path					: '',
	currentDir				: '',
	prevAutocompleteWord	: '',
	KeyObserving			: {},
	
	createConsole: function() {
		var Console = new Element('div', {id: 'Console'});
		Console.insert(
			new Element('a').addClassName('PopupOpener').observe('click', function() {
				FF.Console.hide();
				Dashboard.consoleConfig = FF.Console;
				window.open(Dashboard.currentAppDir + "Console.php", "newconsole", "width=500,height=550");
			}).update('Popup VIEW')
		);
		var ConsoleContainer = new Element('div').addClassName('ConsoleContainer');
		ConsoleContainer.insert(new Element('div', {id: 'consoleShower'}));
		ConsoleContainer.insert(new Element('input', {id: 'consoleInput', value: '> '}));
		Console.insert(ConsoleContainer);
		document.body.appendChild(Console);
		this.Console = Console;
	},
	
	show: function(path, opt) {
		if(path != null)
			this.path = path;
		if(this.Console == false) 
			this.createConsole();
		if(opt.returnField)
			this.returnField = opt.returnField;
		
		$('Console').setStyle({left: FF.Console.getLeftPosition()})
		new Effect.Move($('Console'), {
			y: 0, 
			x: FF.Console.getLeftPosition(), 
			duration: FF.UI.AnimationSpeed, 
			mode:'absolute', 
			transition: Effect.Transitions.sinoidal, 
			afterFinishInternal: function() {
				FF.Console.addMessage("Console Ready!");
				if(opt.initDir)
					FF.Console.run('cd ' + opt.initDir);
				if(opt.customMessage)
					FF.Console.addMessage(opt.customMessage);
				$('consoleInput').focus();
				$('consoleInput').observe('keydown', FF.Console.manageKey);
			}
		});
		Element.setOpacity($('Console'), .8);
	},
	
	hide: function(s) {
		if (this.ViewType == 'inpage') {
			new Effect.Move($('Console'), {
				y: -500,
				x: FF.Console.getLeftPosition(),
				duration: s || FF.UI.AnimationSpeed,
				mode: 'absolute',
				transition: Effect.Transitions.sinoidal,
				afterFinishInternal: function(){
					$('consoleInput').stopObserving('keyup', FF.Console.manageKey);
				}
			});
		} else {
			window.close();
		}
	},
	
	getCommandFromHistory: function(v) {
		if((this.CommandPointer + v) >= 0 && (this.CommandPointer + v) < this.CommandHistory.length) {
			var cmd = this.CommandHistory[this.CommandPointer + v];
			this.CommandPointer += v;
			this.resetCursor(cmd);
		}
		if ((this.CommandPointer + v) == this.CommandHistory.length)
			this.resetCursor();
	},
	
	manageKey: function(event) {
		switch(event.keyCode) {
			case Event.KEY_RETURN:
				FF.Console.run(event.element().value);
				break;
			
			case Event.KEY_UP:
				FF.Console.getCommandFromHistory(-1);
				break;
			
			case Event.KEY_DOWN:
				FF.Console.getCommandFromHistory(1);
				break;
				
			case Event.KEY_TAB:
				FF.Console.Commands('autocomplete '+ $('consoleInput').value);
				break;
				
			case Event.KEY_ESC:
				FF.Console.resetCursor();
				break;
			
			default:
				var $SharedKeys = {
					89	: 'y',
					78	: 'n'
				};
				if($SharedKeys[event.keyCode] && FF.Console.KeyObserving[$SharedKeys[event.keyCode]]) 
					FF.Console.KeyObserving[$SharedKeys[event.keyCode]].fn();
				break;
		}
	},
	
	addUserQuery: function(opt) {
		var Istance = {
			type		: 'confirm'
		}
		Object.extend(Istance, opt);
		switch(Istance.type) {
			case 'confirm':
				FF.Console.KeyObserving[Istance.CONFIRM_key] = {
					id	: Istance.id,
					fn	: Istance.CONFIRM_fn
				}
				FF.Console.KeyObserving[Istance.STOP_key] = {
					id	: Istance.id,
					fn	: Istance.STOP_fn
				}
				break;
		}
	},
	
	removeUserQueryById: function(id) {
		for (var Method in FF.Console.KeyObserving) {
			if (FF.Console.KeyObserving[Method].id && FF.Console.KeyObserving[Method].id == id) 
				FF.Console.KeyObserving[Method] = null;
		}
	},
	
	storeCommand: function(cmd) {
		this.CommandHistory.push(cmd);
		this.CommandPointer = this.CommandHistory.length;
	},
	
	run: function(cmd) {
		var cmd = this.parseCommand(cmd);
		this.storeCommand(cmd);
		this.addMessage(cmd);
		$('consoleInput').value = "";
		$('consoleInput').readOnly = true;
		FF.Console.Commands(cmd);
	},
	
	resetCursor: function(update) {
		$('consoleInput').readOnly = false;
		$('consoleInput').value = this.currentDir + "> ";
		if(update != null)
			$('consoleInput').value += update;
		$('consoleInput').focus();
	},
	
	addMessage: function(msg, system, reset) {
		if(!system) var pre = this.currentDir + "> ";
		else var pre = "";
		$('consoleShower').innerHTML += pre + msg + "<br />";
		if(reset != false)
			this.resetCursor();
	},
	
	parseCommand: function(cmd) {
		cmd = cmd.replace(this.currentDir, '');
		if(cmd[0] == ">" && cmd[1] == " ") 
			return cmd.split("> ")[1];
		else 
			return cmd;
	},
	
	
	Commands: function(extCommand) {
		$this = this;
		var cmd = extCommand.split(" ")[0];
		params = extCommand.split(" ").without(cmd).join(" ");
		
		switch(cmd) {
			
			case 'autocomplete':
				params = this.parseCommand(params);
				params = params.split(" ");
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=autocomplete&path=' + $this.currentDir + '/&word=' + params[1] + '&prevWord=' + $this.prevAutocompleteWord, {
					method: 'get',
					onComplete: function(ajax){
						FF.Console.prevAutocompleteWord = params[1];
						if (ajax.responseText.strip() != "") {
							$this.resetCursor(params[0] + " " + ajax.responseText.strip());
						}
						else if(params[1] != undefined)
							$this.resetCursor(params[0] + " " + params[1]);
						else
							$this.resetCursor(params[0]);
							
					}
				});
				break;
			
			case 'cd':
				if (params != '..' && params != '.') {
					switch(params) {
						case 'docroot':
							var path = params;
							break;
						case 'moduleroot':
							var path = params;
							break;
						case '/':
							var path = '/';
							break;
						default:
							var path = $this.currentDir + '/' + params + "/";
							break;
					}
					
					new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=cd&path=' + path, {
						method: 'get',
						onComplete: function(ajax){
							if (ajax.responseText.strip() == 'KO') 
								$this.addMessage('Directory does not exist!', true);
							else {
								if (params == 'docroot') {
									$this.currentDir = ajax.responseText.strip();
								} else if (params == 'moduleroot') {
									$this.currentDir = ajax.responseText.strip();
								}
								else if (params == '/') 
									$this.currentDir = '';
								else {
									if (params.endsWith('/')) 
										params = params.truncate(params.length - 1, '')
									$this.currentDir += "/" + params;
								}
							}
							$this.resetCursor();
						}
					});
				}
				if(params == '..')
					this.Commands('cd..');
				break;
			
			case 'cd..':
				if(this.currentDir != "" && this.currentDir != "/")
					this.currentDir = this.currentDir.split('/').without(this.currentDir.split('/')[this.currentDir.split('/').length-1]).join('/');
				this.resetCursor();
				break;
			
			case 'clear':
				if(params != null && params != "") {
					if(params == 'log') {
						new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=clearLog', {
							method: 'get',
							onComplete: function(ajax) {
								FF.Console.addMessage(ajax.responseText, true);
							}
						});
					} else if(params == 'errorlog') {
						new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=clearerrorlog', {
							method: 'get',
							onComplete: function(ajax) {
								FF.Console.addMessage(ajax.responseText);
							}
						});
					}
				} else {
					$('consoleShower').innerHTML = "";
					this.resetCursor();
				}
				break;
			
			case 'del':
				
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=del&file=' + $this.currentDir + '/' + params, {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText, true, false);
						FF.Console.addUserQuery({
							id			: 'DEL_CONFIRM',
							type		: 'confirm',
							CONFIRM_key	: 'y',
							STOP_key	: 'n',
							CONFIRM_fn	: function() {
								FF.Console.addMessage('y', true);
								new Ajax.Request(FF.Console.path + 'inc/ajaxRequests.php?ConsoleCmd=confirmdel', {
									onComplete: function(ajax) {
										FF.Console.addMessage(ajax.responseText, true);
									}
								});
								FF.Console.removeUserQueryById('DEL_CONFIRM');
							},
							STOP_fn		: function() {
								FF.Console.addMessage('n', true);
								FF.Console.addMessage('Discarded by user decision!', true);
								FF.Console.removeUserQuery('DEL_CONFIRM');
							}							
						});
					}
				});
				break;
			
			case 'errorlog': 
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=errorlog', {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText);
					}
				});
				break;
			
			case 'exit':
				this.Commands('clear');
				this.addMessage("BYE BYE BYE BYE BYE BYE BYE BYE BYE BYE BYE BYE BYE BYE BYE BYE<br>", true);
				setTimeout(function(){$this.hide();}, 1000);
				break;
			
			case 'help':
				this.addMessage("** HELP **<br><br>'clear': Pulisce la console<br><br>'refresh': Ricarica la pagina corrente<br><br>'exit': Chiude la console mantenendo i messaggi<br><br>'query: +arguments': Effettua una query a DB e stampa un dump del risultato. Importante scrivere 'query:' seguito da uno spazio e dalla query vera e propria<br><br>'log': Stampa il log dell'FF Admin<br>", true);
				break;
			
			case '?':
				this.addMessage("** HELP **<br><br>'clear': Pulisce la console<br><br>'refresh': Ricarica la pagina corrente<br><br>'exit': Chiude la console mantenendo i messaggi<br><br>'query: +arguments': Effettua una query a DB e stampa un dump del risultato. Importante scrivere 'query:' seguito da uno spazio e dalla query vera e propria<br><br>'log': Stampa il log dell'FF Admin<br>", true);
				break;
			
			case 'log':
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=log', {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText);
					}
				});
				break;
			
			case 'ls':
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=ls&path=' + this.currentDir + "/", {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText, true);
					}
				});
				break;
			
			case 'mkdir':
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=mkdir&dir=' + $this.currentDir + '/' + params, {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText, true);
					}
				});
				
			case 'md':
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=mkdir&dir=' + $this.currentDir + '/' + params, {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText, true);
					}
				});
			
			case 'mkfile':
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=mkfile&filename='+params+'&filepath=' + $this.currentDir + '/' + params, {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText, true);
					}
				});
			
			case 'query:':
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=query&params='+params, {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText, true);
					}
				});
				break;
			
			case 'php:':
				new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=php&params='+params, {
					method: 'get',
					onComplete: function(ajax) {
						FF.Console.addMessage(ajax.responseText, true);
					}
				});
				break;
			
			case 'return':
				if(this.returnField) {
					if (params == 'dir') {
						if($this.ViewType == 'inpage') 
							$this.returnField.value = FF.Utils.rebuildHostUrl($this.currentDir);
						else 
							opener.document.getElementById($this.returnField.identify()).value = FF.Utils.rebuildHostUrl($this.currentDir); 
					} else {
						if($this.ViewType == 'inpage') 
							$this.returnField.value = FF.Utils.rebuildHostUrl($this.currentDir) + params;
						else 
							opener.document.getElementById($this.returnField.identify()).value = FF.Utils.rebuildHostUrl($this.currentDir) + params; 
					}
					this.hide(.2);
				} else 
					this.addMessage("Errore! Manca il campo di ritorno.", true);
				break;
			
			case 'refresh':
				if(params != null && params != "") {
					if(params == 'ALL') parent.window.location.reload();
					if(params == 'menu') parent.menu.window.location.reload();
					if(params == 'header') parent.header.window.location.reload();
					this.addMessage(params + " reloaded", true);
				} else 	window.location.reload();
				break;
			
			case 'save':
				var Editor = $(params);
				Editor.down('form').submit();
				Editor.down('textarea').disabled = 'disabled';
				$('_EditorActions').update('Saving...');
				$('_EditorActions').SaveDone = function() {
					var msg = Editor.id.split('_editor')[0];
					Element.remove(Editor);
					FF.Console.addMessage('<br /> ' + msg + ' saved successfully!', true);
				}
				$('_EditorActions').SaveError = function(_ERROR) {
					var msg = Editor.id.split('_editor')[0];
					Element.remove(Editor);
					FF.Console.addMessage('<br />Error saving: ' + msg + '! [REASON] ' + _ERROR, true);
				}
				break;
			
			case 'vi':
				switch (params) {
					
					case 'module':
						this.addMessage('Module name: ');
						break;
					
					default:
						// openfile
						new Ajax.Request(this.path + 'inc/ajaxRequests.php?ConsoleCmd=vi&file=' + $this.currentDir + '/' + params, {
							method: 'get',
							onComplete: function(ajax) {
								FF.Console.addMessage(ajax.responseText, true);
								if($($this.currentDir + '/' + params + '_editor')) {  //C'è un editor
									var Editor = $($this.currentDir + '/' + params + '_editor');
									Editor.down('textarea').observe('keypress', function() {
										if (!$('_EditorActions')) {
											var ED = new Element('div', {
												id: '_EditorActions'
											}).update('The file has been changed! <input type="button" value="SAVE" onclick="FF.Console.Commands(\'save \' + this.up(1).id)" /> &nbsp;<input type="button" value="DISCARD" onclick="Element.remove(this.up(1))" />');
											Element.setStyle(ED, {
												'position': 'absolute',
												'top': '0',
												'right': '0',
												'background': 'red',
												'padding': '4px',
												'fontWeight': 'bold',
												'fontSize': '14px'
											});
											Editor.appendChild(ED);
										}
									});
								}
							}
						});
					
				}
				break;
			
			default:
				this.addMessage("Comando non conosciuto!", true);
				break;
			
		}
	},
	
	getLeftPosition: function() {
		return (document.body.getDimensions().width / 2 - 250);
	}
	
	
}
