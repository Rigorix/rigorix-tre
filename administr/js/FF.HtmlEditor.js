/**
 * @author paolo
 */

FF.HtmlEditor = {
	
	active		: false,
	status		: false,
	imagePath	: Dashboard.currentAppDir + 'js/i',
	editorPath	: Dashboard.currentAppDir + 'js/editor.php',
	externalCss	: new Array('../css/common.css'),
	defaultOpt	: {
		width	: 560,
		height	: 230
	},
	
	replaceTextarea: function(t, userOptions) 
	{
		this.options = Object.extend(Object.clone(this.defaultOpt), userOptions);
		var HtmlEditor = new Element('div', {id: t.identify() + '_htmle'}).addClassName('HtmlEditor').setStyle({width: this.options.width + 'px', height: this.options.height + 'px'});
		var Toolbar = new Element('div').addClassName('Toolbar');
		var Editor = new Element('iframe', {frameBorder: 0, name: 'Edit_' + t.identify(), src: this.editorPath}).addClassName('Editor').setStyle({width: (this.options.width - 4) + 'px', height: (this.options.height - 32) + 'px'});;
		var Resizer = new Element('div').addClassName('Resizer').observe('mousedown', FF.HtmlEditor.startResize.bind())//.observe('mouseup', FF.HtmlEditor.stopResize.bind())
		
		Toolbar.insert(this.buildMenu());
		HtmlEditor.insert(Toolbar);
		HtmlEditor.insert(this.buildDialog());
		HtmlEditor.insert(Editor);
		HtmlEditor.insert(Resizer);
		
		HtmlEditor.config = {
			textarea	: t,
			id			: t.identify() + '_htmle',
			editor		: Editor,
			editorName	: 'Edit_' + t.identify(),
			toolbar		: Toolbar,
			options		: FF.HtmlEditor.options
		}
		
		HtmlEditor.updateTextarea = this.updateTextarea;
		HtmlEditor.updateHtmlEditor = this.updateHtmlEditor;
		HtmlEditor.log = this.log;
		this.buildToolbar(HtmlEditor);
		Editor.observe('load', function() {
			this.contentWindow.document.designMode = "on";
			var doc = this.contentDocument || this.contentWindow.document
			FF.HtmlEditor.addExternalCss(this);
			HtmlEditor.updateHtmlEditor();
			HtmlEditor.updateTextarea();
		})
		t.insert({before: HtmlEditor});
		t.addClassName('Editor');
		t.hide();
		this.setActive(HtmlEditor);
		this.status = true;
	},
	
	buildToolbar: function(HtmlEditor)
	{
		Object.keys(this.Commands).each(function(k) {
			if(k.indexOf('separator') > -1)
				HtmlEditor.config.toolbar.insert(new Element('div').writeAttribute('title', k).addClassName('separator'));
			else {
				var button = new Element('a', {href: 'javascript://;', title: k}).addClassName('btn').update(FF.HtmlEditor.Commands[k].label).observe('click', function(){
					FF.HtmlEditor.execute(this);
				});
				HtmlEditor.config.toolbar.insert(button);
			}
		});
		HtmlEditor.config.toolbar.insert('<br clear="all" />');
	},
	
	buildDialog: function()
	{
		var Dialog = new Element('div').addClassName('htmlDialog');
		var syntax = /(^|.|\r|\n)(\<%=\s*(\w+)\s*%\>)/;
		var code = new Template('<h3><%=title%></h3><br />' + 
			'<p><%=description%></p>' +
			'<div><%=content%></div>' + 
			'<div><%=buttons%></div>', 
		syntax);
		Dialog.code_template = code;
		return Dialog;
	},
	
	buildMenu: function() 
	{
		var Menu = new Element('div').addClassName('Menu').update('<a>MENU</a>');
		Menu.insert(new Element('ul'));
		Menu.down('ul').insert(new Element('li').update('<strong>CSS injection</strong>'));
		this.externalCss.each(function(css) {
			Menu.down('ul').insert(new Element('li').insert(new Element('a').update(css).observe('click', function() {
				this.toggleClassName('remove');
				FF.HtmlEditor.toggleExtCss(this);
			})));
		});
		Menu.down('ul').hide();
		Menu.down('a').observe('click', function() {
			this.next().toggle();
			this.toggleClassName('open');
		})
		return Menu;
	},
	
	startResize: function(ev) 
	{
		if (!this.conf) {
			this.conf = {
				mousex: Event.pointerX(ev),
				mousey: Event.pointerY(ev),
				r_w: this.offsetWidth,
				r_h: this.offsetHeight,
				h_w: (this.up('.HtmlEditor').getDimensions().width - 8),
				h_h: (this.up('.HtmlEditor').getDimensions().height - 8),
				e_w: (this.previous('.Editor').getDimensions().width - 4),
				e_h: (this.previous('.Editor').getDimensions().height - 4)
			};
			this.setStyle({
				width	: this.conf.e_w + 'px',
				height	: this.conf.e_h + 'px'
			});
			var elem = this;
			document.body.observe('mousemove', FF.HtmlEditor.doResize.bind(elem));
			document.body.observe('mouseup', FF.HtmlEditor.stopResize.bind(elem));
		}
	},
	
	stopResize: function(ev) 
	{
		if (this.conf) {
			this.setStyle({
				width	: this.conf.r_w + 'px',
				height	: this.conf.r_h + 'px'
			});
			this.conf = false;
			document.body.stopObserving('mousemove');
			document.body.stopObserving('mouseup');
		}
	},
	
	doResize: function(ev)
	{
		if (this.conf) {
			var gapx = this.conf.mousex - Event.pointerX(ev);
			var gapy = this.conf.mousey - Event.pointerY(ev);
			this.up('.HtmlEditor').setStyle({
				width: this.conf.h_w - gapx + 'px',
				height: this.conf.h_h - gapy + 'px'
			});
			this.previous('.Editor').setStyle({
				width: this.conf.e_w - gapx + 'px',
				height: this.conf.e_h - gapy + 'px'
			});
		}
	},
	
	toggleExtCss: function(m) 
	{
		if(m.hasClassName('remove'))
			$A(m.up('.HtmlEditor').config.editor.contentWindow.document.getElementsByTagName('head')[0].getElementsByTagName('style')).each(function(style) {
				if(style.innerHTML.include(m.innerHTML.strip()))
					Element.remove(style);
			});
		else 
			m.up('.HtmlEditor').config.editor.contentWindow.document.getElementsByTagName('head')[0].appendChild(new Element('style').update("@import '"+m.innerHTML.strip()+"';"));
	},
	
	addExternalCss: function(Editor) 
	{
		this.externalCss.each(function(cssPath) {
			Editor.contentWindow.document.getElementsByTagName('head')[0].appendChild(new Element('style').update("@import '"+cssPath+"';"));
		});
	},
	
	setActive: function(e)
	{
		this.active = e;
	},
	
	execute: function(cmd) 
	{
		var Editor = cmd.up('.HtmlEditor');
		var Command = FF.HtmlEditor.Commands[cmd.readAttribute('title')];
		switch(cmd.readAttribute('title')) {
			case 'source':
				if(FF.HtmlEditor.status) {
					Editor.updateTextarea();
					Editor.config.textarea.setStyle({height: Editor.config.editor.getDimensions().height + 'px', width: Editor.config.editor.getDimensions().width + 'px', border: 0});
					Editor.config.editor.hide();
					Editor.config.editor.insert({before: Editor.config.textarea});
					Editor.config.textarea.show();
					cmd.update('<span>wysiwyg</span>');
					FF.HtmlEditor.status = false;
				} else {
					Editor.updateHtmlEditor();
					Editor.config.editor.show();
					Editor.config.textarea.hide();
					cmd.update('<span>Source</span>');
					FF.HtmlEditor.status = true;
				}
				break;
			case 'save':
				cmd.down('img').src = FF.HtmlEditor.imagePath+'/ico_img_saving.gif';
				Editor.updateTextarea();
				Editor.log('Salvato!');
				setTimeout(function() {cmd.down('img').src = FF.HtmlEditor.imagePath+'/ico_save.gif';}, 500);
				break;
			case 'insertimage':
				var Dialog = cmd.up('.HtmlEditor').down('.htmlDialog');
				var opt = {
					title		: 'Inserisci immagine',
					description	: 'Incolla l\'url da internet o cerca all\'interno del filesystem',
					content		: '<input type="text" id="returnImage" style="float: left; width: 210px" /> <a onclick="FF.Contents.getDirectoryUrl(\'returnImage\');"><img src="'+Dashboard.currentAppDir+'i/terminal.gif" align="left"></a>',
					buttons		: '<input type="button" value="Inserisci" onclick="$(\''+cmd.up('.HtmlEditor').identify()+'\').config.editor.contentWindow.document.execCommand(\'insertimage\', \'\', $(\'returnImage\').value)" /> <input type="button" value="Chiudi" onclick="new Effect.Move(this.up(\'.htmlDialog\'), {y: -200, x: \'50%\', duration: .6, mode: \'absolute\'});" />'
				}
				Dialog.update('');
				Dialog.insert(Dialog.code_template.evaluate(opt));
				new Effect.Move(Dialog, {y: 35, x: '50%', duration: .6, mode: 'absolute'});
				break;
			default: 
				switch(Command.type) {
					case 'prompt': 
						Editor.config.editor.contentWindow.document.execCommand(cmd.readAttribute('title'), false, prompt(Command.question, Command.defaultValue));
						break;
					default: 
						if(!Command.value)
							Command.value = '';
						Editor.config.editor.contentWindow.document.execCommand(cmd.readAttribute('title'), false, Command.value);
						break;
				}
				break;
		}
		Editor.updateTextarea();
	},
	
	dialog: function() 
	{
		var d = new Element('div').addClassName('htmlDialog').update('Dialog box');
		return d;
	},
	
	getSelection: function()
	{
		if (window.getSelection) 
	        return window.getSelection();
	    else if (document.getSelection)
		    return document.getSelection();
	    else if (document.selection)
		    return document.selection.createRange().text;
	    else 
			return false;
	},
	
	updateHtmlEditor: function() {
		if (this.config.editor.contentWindow.document.body)
	        this.config.editor.contentWindow.document.body.innerHTML = this.config.textarea.value;
    },
	
	updateTextarea: function() {
		if (this.config.editor.contentWindow.document.body) {
			var html = this.config.editor.contentWindow.document.body.innerHTML.strip();
			$A(FF.HtmlEditor.Map).each(function(cmd, i){
				html = html.replace(cmd[0], cmd[1]);
			});
			this.config.textarea.value = html;
		}
    },
	
	log: function(t) {
		if (!$('FFHEC')) {
			var c = new Element('div', {id: 'FFHEC'}).addClassName('Console').update(t).observe('click', function(){this.remove();});
			setTimeout(function() {
				if ($('FFHEC'))
					$('FFHEC').remove();
			}, 3000);
			this.config.toolbar.insert(c);
		} else 
			$('FFHEC').update(t);
	}
	
}

FF.HtmlEditor.Map = new Array(
    [/<(B|b|STRONG)>(.*?)<\/\1>/gm, "<strong>$2</strong>"],
    [/<(I|i|EM)>(.*?)<\/\1>/gm, "<em>$2</em>"],
    [/<P>(.*?)<\/P>/gm, "<p>$1</p>"],
    [/<H1>(.*?)<\/H1>/gm, "<h1>$1</h1>"],
    [/<H2>(.*?)<\/H2>/gm, "<h2>$1</h2>"],
    [/<H3>(.*?)<\/H3>/gm, "<h3>$1</h3>"],
    [/<PRE>(.*?)<\/PRE>/gm, "<pre>$1</pre>"],
    [/<A (.*?)<\/A>/gm, "<a $1</a>"],
    [/<IMG (.*?)>/gm, "<img $1 />"],
    [/<LI>(.*?)<\/LI>/gm, "<li>$1</li>"],
    [/<UL>(.*?)<\/UL>/gm, "<ul>$1</ul>"],
    [/<span style="font-weight: normal;">(.*?)<\/span>/gm, "$1"],
    [/<span style="font-weight: bold;">(.*?)<\/span>/gm, "<strong>$1</strong>"],
    [/<span style="font-style: italic;">(.*?)<\/span>/gm, "<em>$1</em>"],
    [/<span style="(font-weight: bold; ?|font-style: italic; ?){2}">(.*?)<\/span>/gm, "<strong><em>$2</em></strong>"],
    [/<([a-z]+) style="font-weight: normal;">(.*?)<\/\1>/gm, "<$1>$2</$1>"],
    [/<([a-z]+) style="font-weight: bold;">(.*?)<\/\1>/gm, "<$1><strong>$2</strong></$1>"],
    [/<([a-z]+) style="font-style: italic;">(.*?)<\/\1>/gm, "<$1><em>$2</em></$1>"],
    [/<([a-z]+) style="(font-weight: bold; ?|font-style: italic; ?){2}">(.*?)<\/\1>/gm, "<$1><strong><em>$3</em></strong></$1>"],
    [/<(br|BR)>/g, "<br />"],
    [/<(hr|HR)( style="width: 100%; height: 2px;")?>/g, "<hr />"]
);
FF.HtmlEditor.Commands = {
	save		: { label: '<img src="'+FF.HtmlEditor.imagePath+'/ico_save.gif" title="inserisci link" alt="Save" />' },
	separator0	: { label: '', type: 'separator'},
	bold		: { label: '<span><strong>B</strong></span>' },
	italic		: { label: '<span><em>i</em></span>' },
	underline	: { label: '<span><u>U</u></span>' },
	separator1	: { label: '', type: 'separator'},
	formatblock	: { label: '<span>H1</span>', value: '<h1>'},
	separator2	: { label: '', type: 'separator'},
	superscript	: { label: '<span class="high">x<sup><strong>y</strong></sup></span>'}, 
	subscript	: { label: '<span class="high">x<sub><strong>y</strong></sub></span>'},
	separator3	: { label: '', type: 'separator'},
	createlink	: {	label: '<img src="'+FF.HtmlEditor.imagePath+'/ico_link.gif" title="inserisci link" hspace="4" vspace="5" alt="Link" />', type: 'prompt', question: 'Inserisci il link', defaultValue: 'http://', value: 'http://www.maledetto.it' },
	unlink		: {	label: '<img src="'+FF.HtmlEditor.imagePath+'/ico_unlink.gif" title="rimuovi link" hspace="4" vspace="3" alt="Unlink" />' },
	insertimage	: {	label: '<img src="'+FF.HtmlEditor.imagePath+'/ico_img.gif" title="inserisci immagine" alt="Image" />' },
	separator4	: { label: '', type: 'separator' },
	removeformat: { label: '<img src="'+FF.HtmlEditor.imagePath+'/ico_removeformatting.gif" title="Remove formatting" alt="Save" />'},
	separator5	: { label: '', type: 'separator' },
	source		: {	label: '<span>Source</span>' }
}
/*
document.observe("dom:loaded", function() {
	$$('textarea').each(function(t) {
		FF.HtmlEditor.replaceTextarea(t);
	});	
});
*/