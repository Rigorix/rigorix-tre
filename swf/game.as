stop();

/* GLOBAL FLASH settings */
import mx.xpath.XPathAPI;
MovieClip.prototype.tabEnabled = false;

// Setto i suoni
_root.setSounds();
//_root.gameXML = 'http://rigorix3/swf/xml_sfida.php?id_sfida=43&backType=';

/* Carico i settaggi del gioco */
var confXML:XML = new XML();
confXML.ignoreWhite = true;
confXML.onLoad = function(success) {
	
	trace ("caricato XML ");
	if(success && this.status == 0) {
		
		_root.loader.load_mcc.deb.text += "loaded\n\r" + this;
		
		var configXML = XPathAPI.selectSingleNode(this.firstChild, "game");
		//_root.nextFrame();
		trace (this);
		applySettings(configXML);
		
	}
	
}
confXML.load(_root.gameXML || 'getXmlSfida.xml');

_root.loader.load_mcc.deb.text = _root.gameXML + "\n\r";

function setSounds() {
	
	_root.winSound = new Sound(_root);
	_root.winSound.attachSound("winSound");
	_root.winSound.setVolume(70)

	_root.looseSound = new Sound(_root);
	_root.looseSound.attachSound("looseSound");
	_root.looseSound.setVolume(80);

	_root.stadiumLoop = new Sound(_root);
	_root.stadiumLoop.attachSound("stadiumLoop");
	_root.stadiumLoop.start(0, 99);
	
	_root.whisper = new Sound(_root);
	_root.whisper.attachSound("fischio");
	_root.whisper.setVolume(90);
	
	_root.parata = new Sound(_root);
	_root.parata.attachSound("parata");
	_root.parata.setVolume(110);
	
	_root.suonoRete = new Sound(_root);
	_root.suonoRete.attachSound("suonoRete");
	_root.suonoRete.setVolume(100);
	
}

function applySettings(CONF) {
	
	var PLAYERS = XPathAPI.selectNodeList(CONF,"game/players/player");
		
	// SETTAGGI GENERALI GIOCO
	_root.shooter = "player1";
	_root.firstShooter = "player1";
	_root.keeper = "player2";
	_root.firstKeeper = "player2";
	_root.delayAfterShoot_time = Number(XPathAPI.selectSingleNode(CONF,"game/settings").attributes.delayAfterShoot_time);
	_root.transitionTime = Number(XPathAPI.selectSingleNode(CONF,"game/settings").attributes.transitionTime);
	_root.currentShoot = 1//Number(XPathAPI.selectSingleNode(CONF,"game/settings").attributes.currentShoot);
	_root.totalShots = Number(XPathAPI.selectSingleNode(CONF,"game/settings").attributes.totalShots);
	_root.gameStatus = "running";
	
	// SETTAGGI GIOCATORE
	_root.settingsObj = new Object();
	
	for(var i=1; i<=PLAYERS.length; i++) {
		
		settingsObj['player' + i] = new Object();
		settingsObj['player' + i].nome = XPathAPI.selectSingleNode(PLAYERS[i-1],"player").attributes.name;
		settingsObj['player' + i].numero = XPathAPI.selectSingleNode(PLAYERS[i-1],"player").attributes.number;
		settingsObj['player' + i].coloreMaglia = XPathAPI.selectSingleNode(PLAYERS[i-1],"player/skin").attributes.maglia;
		settingsObj['player' + i].colorePantaloni = XPathAPI.selectSingleNode(PLAYERS[i-1],"player/skin").attributes.pantaloni;
		settingsObj['player' + i].coloreCalzini = XPathAPI.selectSingleNode(PLAYERS[i-1],"player/skin").attributes.calzini;
		settingsObj['player' + i].tipoMaglia = XPathAPI.selectSingleNode(PLAYERS[i-1],"player/skin").attributes.tipoMaglia;
		settingsObj['player' + i].shoots = new Array();
		settingsObj['player' + i].keeps = new Array();
		settingsObj['player' + i].shootIndex = -1;
		settingsObj['player' + i].keepIndex = -1;
		
		if(XPathAPI.selectSingleNode(PLAYERS[i-1],"player").attributes.whatcher == 'true') _root.playerWhatcher = 'player' + i;
		
		// Lista di tiri
		for(var s=0; s < XPathAPI.selectNodeList(PLAYERS[i-1],"player/shoots/shoot").length; s++) {
			settingsObj['player' + i].shoots.push(XPathAPI.selectNodeList(PLAYERS[i-1],"player/shoots/shoot")[s].attributes.target);
		}
		
		// Lista parate
		for(var s=0; s < XPathAPI.selectNodeList(PLAYERS[i-1],"player/keeps/keep").length; s++) {
			settingsObj['player' + i].keeps.push(XPathAPI.selectNodeList(PLAYERS[i-1],"player/keeps/keep")[s].attributes.target);
		}
		
		//_root.settingsObj = settingsObj;
	}
	
	// SETTAGGI TABELLONE
	_root.tabellone.player1_name.text = settingsObj[firstShooter].nome;
	_root.tabellone.player2_name.text = settingsObj[firstKeeper].nome;
	
	initGame();
	
}

function initGame() {
	
	_root.finale._x = -1000;
	new mx.transitions.Tween(_root.loader, "_alpha", mx.transitions.easing.Regular.easeOut, 100, 0, transitionTime, true);
	setTimeout(function() {
		_root.loader.removeMovieClip();
	}, (transitionTime * 1000 + 400));
	_root.newShoot();
	
}

function newShoot() {
	
	_root.whisper.start(0, 1);
	settingsObj[_root.shooter].shootIndex++;
	settingsObj[_root.keeper].keepIndex++;
	
	_root.shootType = settingsObj[_root.shooter].shoots[settingsObj[_root.shooter].shootIndex];
	_root.keepType = settingsObj[_root.keeper].keeps[settingsObj[_root.keeper].keepIndex];
	
	_root.gameScreen.tiratore.gotoAndPlay("shoot");
	_root.gameScreen.portiere.gotoAndPlay("idle");
	_root.gameScreen.pallone.gotoAndStop(1);
	
	// Special keeps
	if(
		_root.keepType == "01" || 
		_root.keepType == "10" || 
		_root.keepType == "02" || 
		_root.keepType == "20" || 
		_root.keepType == "12" || 
		_root.keepType == "21"
	) {
		
		// C'è una parata speciale
		_root.gameScreen.portiere.braccioSx.aura._alpha = 100;
		_root.gameScreen.portiere.braccioSx.superGuanto._alpha = 100;
		_root.gameScreen.portiere.braccioDx.aura._alpha = 100;
		_root.gameScreen.portiere.braccioDx.superGuanto._alpha = 100;
		_root.specialKeep = "1";
		trace("Keep: " + _root.keepType)
		if(_root.keepType.indexOf(_root.shootType) != -1) {
			// Una delle due posizioni del portiere corrisponde al tiro.
			_root.keepType = _root.shootType;
			trace("Tiro speciale, keeptype diventa: " + _root.keepType + " e para")
		} else {
			// Sprecata la parata speciale, il tiro va in goal
			_root.keepType = substring(_root.keepType, 0, 1);
			trace("Tiro speciale, diventa: " + _root.keepType + " ma non para")
		}
		
	} else {
		
		_root.gameScreen.portiere.braccioSx.aura._alpha = 0;
		_root.gameScreen.portiere.braccioSx.superGuanto._alpha = 0;
		_root.gameScreen.portiere.braccioDx.aura._alpha = 0;
		_root.gameScreen.portiere.braccioDx.superGuanto._alpha = 0;
		_root.specialKeep = "99";
		
	}
	
	setPlayersSkin();
	
	new mx.transitions.Tween(_root.gameScreen.tiratore, "_alpha", mx.transitions.easing.Regular.easeOut, 0, 100, transitionTime, true);
	new mx.transitions.Tween(_root.gameScreen.portiere, "_alpha", mx.transitions.easing.Regular.easeOut, 0, 100, transitionTime, true);
	new mx.transitions.Tween(_root.gameScreen.pallone, "_alpha", mx.transitions.easing.Regular.easeOut, 0, 100, transitionTime, true);
	
	
}

function ballKick() {
	
	_root.gameScreen.pallone.gotoAndPlay("shoot_" + _root.shootType);
	_root.gameScreen.portiere.gotoAndPlay("shoot_" + _root.keepType);	
	
}

function collisionAction() {
	
	_root.gameScreen.pallone.pallla.gotoAndStop(1);
	
	if(_root.shootType == _root.keepType) {
		_root.parata.start(0, 1);
		_root.gameScreen.pallone.gotoAndPlay("shoot_" + _root.shootType + "_keep");
	} else {
		_root.suonoRete.start(0, 1);
		_root.gameScreen.pallone.gotoAndPlay("shoot_" + _root.shootType + "_goal");
	}
	
	if(_root.playerWhatcher == _root.shooter) {
		if (_root.shootType != _root.keepType) _root.winSound.start(0, 1);
		else _root.looseSound.start(0, 1);
	} else {
		if (_root.shootType == _root.keepType) _root.winSound.start(0, 1);
		else _root.looseSound.start(0, 1);		
	}
	
}

function setSubColors(mc) {
	var maglia_color = new Color(mc.maglia);
	maglia_color.setRGB(_root.settingsObj[_root.shooter].coloreMaglia);
	mc.gfxMaglia.gotoAndStop(_root.settingsObj[_root.shooter].tipoMaglia);

	var pantaloni_color = new Color(mc.pantaloni);
	pantaloni_color.setRGB(_root.settingsObj[_root.shooter].colorePantaloni);

	var calzini_color = new Color(mc.calzini);
	calzini_color.setRGB(_root.settingsObj[_root.shooter].coloreCalzini);
}

function setPlayersSkin() {
	
	// Setto numero e nome tiratore
	_root.nome_tiratore = settingsObj[_root.shooter].nome;
	_root.numero_tiratore = settingsObj[_root.shooter].numero;
	
	/*************
	** Tiratore **
	*************/
	
	// Setto il colore della maglia
	tiratore_color = new Color(_root.gameScreen.tiratore.busto.coloreMaglia);
	tiratore_color.setRGB(settingsObj[_root.shooter].coloreMaglia);
	
	// Setto il tipo di maglia
	_root.gameScreen.tiratore.busto.gotoAndStop(settingsObj[_root.shooter].tipoMaglia);
	
	// Setto il colore dei calzini
	tiratore_calzini_color = new Color(_root.gameScreen.tiratore.gamba_dx.coloreGambaDx);
	tiratore_calzini_color.setRGB(settingsObj[_root.shooter].coloreCalzini);
	tiratore_calzini_color = new Color(_root.gameScreen.tiratore.gamba_sx.coloreGambaSx);
	tiratore_calzini_color.setRGB(settingsObj[_root.shooter].coloreCalzini);
	tiratore_calzini_color = new Color(_root.gameScreen.tiratore.gambaSx2.colore);
	tiratore_calzini_color.setRGB(settingsObj[_root.shooter].coloreCalzini);
		
	// Setto il colore dei pantaloni
	tiratore_pantaloni_color = new Color(_root.gameScreen.tiratore.pantaloni.pantaloniColor);
	tiratore_pantaloni_color.setRGB(settingsObj[_root.shooter].colorePantaloni);
	
	/*************
	** Portiere **
	*************/
	
	// Setto il colore della maglia
	color = new Color(_root.gameScreen.portiere.corpo.maglia);
	color.setRGB(settingsObj[_root.keeper].coloreMaglia);
	_root.gameScreen.portiere.corpo.gfxMaglia.gotoAndStop(settingsObj[_root.keeper].tipoMaglia);
	
	// Setto il colore dei calzini
	color = new Color(_root.gameScreen.portiere.gambaDx.colore);
	color.setRGB(settingsObj[_root.keeper].coloreCalzini);
	color = new Color(_root.gameScreen.portiere.gambaSx.colore);
	color.setRGB(settingsObj[_root.keeper].coloreCalzini);
			
	// Setto il colore dei pantaloni
	color = new Color(_root.gameScreen.portiere.corpo.pantaloni);
	color.setRGB(settingsObj[_root.keeper].colorePantaloni);
	
	//_root.setSubColors(_root.gameScreen.tiratore.tiratore_goal);
	//_root.setSubColors(_root.gameScreen.tiratore.keep_player);

	
}

function manageResult(mc) {
	
	if(_root.shootType == _root.keepType) {		// PARATA = KEEP
		
		_root.gameScreen.portiere.gotoAndPlay("shoot_" + _root.keepType + "_keep");
		_root.gameScreen.tiratore.gotoAndPlay("keep");
		
		// Setto i colori del tiratore
		_root.tabellone["shoot" + _root.currentShoot].gotoAndStop("ko");
		
		//_root.setSubColors(_root.gameScreen.tiratore.tiratore_goal);
		_root.setSubColors(_root.gameScreen.tiratore.keep_player);
		
		//_root.tabellone[_root.keeper + "_score"].text = Number(Math.floor(_root.tabellone[_root.keeper + "_score"].text) + 1);
		
	} else {										// GOAL
		
		_root.gameScreen.portiere.gotoAndPlay("shoot_" + _root.keepType + "_goal");
		_root.gameScreen.tiratore.gotoAndPlay("goal");
		_root.tabellone[_root.shooter + "_score"].text = Number(Math.floor(_root.tabellone[_root.shooter + "_score"].text) + 1);
		
		_root.tabellone["shoot" + _root.currentShoot].gotoAndStop("ok");

		_root.setSubColors(_root.gameScreen.tiratore.tiratore_goal);
		//_root.setSubColors(_root.gameScreen.tiratore.keep_player);
	}
	
	_root.delayAfterShoot = setInterval(function(){_root.resetShoot();}, _root.delayAfterShoot_time);
	
}

function setupConfiguration() { // Imposta i valori di maglia e tuttto il resto per il tiro corrente
	
	if((Number(_root.currentShoot)+1) <= _root.totalShots) {

		// Reimposta shooter e keeper
		_root.currentShoot++;
		
		var temp = _root.shooter;
		_root.shooter = _root.keeper;
		_root.keeper = temp;
		_root.specialKeep = false;
		
		_root.newShoot();
		
	} else {
		
		gameFinished();
		
	}
	
}

function resetShoot() {	// Nasconde i giocatori e torna allo stato iniziale
	
	clearInterval(_root.delayAfterShoot);
	clearInterval(_root.intId)
	_root.gameScreen.sipario.gotoAndPlay(2);
	
	// Imposto le magliette secondo i colori corretti
	_root.setupConfiguration();

}

function gameFinished() {
	
	_root.finale._x = 93;
	_root.gameStatus = "ended";
	
	new mx.transitions.Tween(_root.gameScreen.tiratore, "_alpha", mx.transitions.easing.Regular.easeOut, 100, 0, transitionTime, true);
	new mx.transitions.Tween(_root.gameScreen.portiere, "_alpha", mx.transitions.easing.Regular.easeOut, 100, 0, transitionTime, true);
	new mx.transitions.Tween(_root.gameScreen.pallone, "_alpha", mx.transitions.easing.Regular.easeOut, 100, 0, transitionTime, true);
	
	/* Calcolo il risultato */
	var p1_score = Number(_root.tabellone["player1_score"].text);
	var p2_score = Number(_root.tabellone["player2_score"].text);
	
	/* Bottoni */
	trace("Cambio il bottone");
	trace(_root.finale.finale_btn)
	
	if(_root.backType == 'direct') {
		_root.finale.finale_btn.btn_text.text = 'CHIUDI';
		_root.finale.finale_btn.onPress = function() {
			getURL("javascript:RX.backDirectToGameFromFlash();");
		}
	} else {
		_root.finale.finale_btn.onPress = function() {
			getURL("javascript:RX.backToGameFromFlash();");
		}
	}
	
	if(p1_score == p2_score) {	// PAREGGIO
		
		// Bottoni
		var p1 = _root.finale.attachMovie("player_perso", "perso", 10, {_x: 70, _y:0, _yscale:150, _xscale: 150});
		var p2 = _root.finale.attachMovie("player_perso", "perso2", 11, {_x: 270, _y:0, _yscale:150, _xscale: 150});
		
		// Scritta
		_root.finale.vinto1.text = _root.finale.vinto2.text = "PAREGGIOOOO!!!!"
		
	} else if(p1_score > p2_score) {
		
		var p1 = _root.finale.attachMovie("player_vinto", "vinto", 10, {_x: 70, _y:0, _yscale:150, _xscale: 150});
		var p2 = _root.finale.attachMovie("player_perso", "perso2", 11, {_x: 270, _y:0, _yscale:150, _xscale: 150});
		
		/* Scritta */
		_root.finale.vinto1.text = _root.finale.vinto2.text = _root.settingsObj['player1'].nome + " ha VINTOOO!!!!"
		
		/* Bottoni */
		_root.finale.btn_rilancia._x = -1000;
		
	} else if(p1_score < p2_score) {
		
		var p1 = _root.finale.attachMovie("player_perso", "vinto", 10, {_x: 70, _y:30, _yscale:140, _xscale: 140});
		var p2 = _root.finale.attachMovie("player_vinto", "perso2", 11, {_x: 270, _y:30, _yscale:140, _xscale: 140});
		
		/* Scritta */
		_root.finale.vinto1.autoSize = _root.finale.vinto2.autoSize = true;
		_root.finale.vinto1.text = _root.finale.vinto2.text = _root.settingsObj['player2'].nome + " ha VINTOOO!!!!"
		
	}
	
	
	/* Player 1 */
	
	var p1_maglia = new Color(p1.maglia);
	p1_maglia.setRGB(_root.settingsObj['player1'].coloreMaglia);
	p1.gfxMaglia.gotoAndStop(_root.settingsObj['player1'].tipoMaglia);
	var p1_pantaloni = new Color(p1.pantaloni);
	p1_pantaloni.setRGB(_root.settingsObj['player1'].colorePantaloni);
	var p1_calzini = new Color(p1.calzini);
	p1_calzini.setRGB(_root.settingsObj['player1'].coloreCalzini);
	
	
	/* Player 2 */
	
	var p2_maglia = new Color(p2.maglia);
	p2_maglia.setRGB(_root.settingsObj['player2'].coloreMaglia);
	p2.gfxMaglia.gotoAndStop(_root.settingsObj['player2'].tipoMaglia);
	var p2_pantaloni = new Color(p2.pantaloni);
	p2_pantaloni.setRGB(_root.settingsObj['player2'].colorePantaloni);
	var p2_calzini = new Color(p2.calzini);
	p2_calzini.setRGB(_root.settingsObj['player2'].coloreCalzini);
	
	new mx.transitions.Tween(_root.finale, "_alpha", mx.transitions.easing.Regular.easeOut, 0, 100, transitionTime+transitionTime, true);
	
}






