/**
 * @author paolo moretti
 */

var dashboard = {
	
	config : {
		MENU_STATUS					: 'OPEN',
		OPEN_MENU_WINDOW_WIDTH		: '*',
		HEADER_STATUS				: 'OPEN',
		OPEN_HEADER_WINDOW_HEIGHT	: '*',
		reportDelayBeforeClose		: 5000,
		multifield_separator		: '***'
	},
	
	ui : {
		HEADER_HEIGHT				: 60,
		zindex : {
			
			textfield	: 2900,
			combobox	: 3000,
			lightbox	: 100000
			
		}
	},
	
	content : {
		rowCompression				: false,
		rowSelection				: 'None'
	},
	
	dictionary: {
		show_console		: 'Mostra la console',
		
	},
	
	reports	: new Array(),
	reportsToShow : new Array()
	
}