<?
set_time_limit(1800);
include 'class.ezpdf.php';


$TOTALE = $_POST['costo'];
if($_POST['storno'] == '1') 
	$_POST['costo'] = round($_POST['costo']/1.2, 2);
else {
	$_POST['costo'] = round($_POST['costo'], 2);
	if($_POST['imponibile'] == 'si') 
		$TOTALE = round($_POST['costo'], 2) + round(round($_POST['costo'], 2) * .2, 2);
}

if($_POST['fattura'] == 1) {
	// Italiana
	$data = explode('-', $_POST['data']);
	$DATA_FATTURA = $data[2]."/".$data[1]."/".$data[0];
	$txt = array(
		"titolo"			=> "Madcap Collective",
		"associazione"		=> "Associazione culturale",
		"indirizzo"			=> "Indirizzo",
		"n_fattura"			=> "FATTURA N.",
		"data"				=> "Data",
		"intestatario"		=> "Intestata a",
		"partita_iva"		=> "Partita IVA",
		"descrizione_tit"	=> "DESCRIZIONE",
		"importo_tit"		=> "IMPORTO\n(In Euro)",
		"descrizione"		=> "Descrizione",
		"importo"			=> "Importo",
		"imponibile"		=> "Imponibile",
		"iva_20"			=> "IVA 20%",
		"iva"				=> "IVA",
		"totale"			=> "TOTALE FATTURA"
	);
}
if($_POST['fattura'] == 2) {
	// Estera
	$DATA_FATTURA = str_replace("-", "/", $_POST['data']);
	$txt = array(
		"titolo"			=> "Madcap Collective",
		"associazione"		=> "Cultural association",
		"indirizzo"			=> "Address",
		"n_fattura"			=> "INVOICE N.",
		"data"				=> "Date",
		"intestatario"		=> "To",
		"partita_iva"		=> "VAT Number",
		"descrizione_tit"	=> "DESCRIPTION",
		"importo_tit"		=> "AMOUNT\n(Euro)",
		"descrizione"		=> "Description",
		"importo"			=> "Amount",
		"imponibile"		=> "Imponibile",
		"iva_20"			=> "VAT EXEMPT",
		"iva"				=> "VAT EXEMPT",
		"totale"			=> "TOTAL AMOUNT"
	);
}

$pdf = new Cezpdf('a4','portrait');
$pdf->selectFont('fonts/Helvetica.afm');
//$pdf->ezSetCmMargins(0,0,0,0);
//$pdf->ezNewPage();
$pdf->addJpegFromFile('madcaplogo.jpg', 30, 742, 70);
//$pdf->ezText('Madcap Collective', 17);
$pdf->addText(110, 799, 17, $txt['titolo']);
$pdf->addText(110, 784, 12, $txt['associazione']);
$pdf->addText(110, 764, 9, '<b>'.$txt['indirizzo'].': </b>Via Ricci, 3 - 31100 Treviso (TV) - Italy');
$pdf->addText(110, 754, 9, '<b>C.F: </b>94105160264');
$pdf->addText(110, 744, 9, '<b>P.IVA: </b>04003540269');
//$pdf->addText(110, 748, 11, '<b>Fax: </b>+39 0422 214096');
$pdf->addText(370, 754, 9, '<b>Web: </b>www.maledetto.it');
$pdf->addText(370, 744, 9, '<b>E-mail: </b>madcap@maledetto.it');
$pdf->ezText('', 85);

$cols = array(
	'col1' => $txt['n_fattura'],
	'col2' => $_POST['index']."/".$_POST['anno'],
	'col3' => $txt['data'],
	'col4' => $DATA_FATTURA
);
$data = array(
	array('col1' => " ", 'col2' => " ", 'col3' => " ", 'col4' => " "),
	array('col1' => $txt['intestatario'].":", 'col2' => $_POST['intestatario'], 'col3' => " ", 'col4' => " "),
	array('col1' => $txt['indirizzo'].": ", 'col2' => $_POST['indirizzo'], 'col3' => " ", 'col4' => " "),
	array('col1' => $txt['partita_iva'].":", 'col2' => $_POST['piva']." ", 'col3' => " ", 'col4' => " "),
	array('col1' => " ", 'col2' => " ", 'col3' => " ", 'col4' => " "),
);
$pdf->ezTable($data, $cols,'',
	array(
		'width' => 526,
		'showLines' => 1,
		'innerLineThickness' => .4,
		'shaded' => 0,
		'cols' => array(
			'col1' => array('justification' => 'left', 'width'=>80),
			'col2' => array('justification' => 'left'),
			'col3' => array('justification' => 'left', 'width'=>50),
			'col4' => array('justification' => 'left', 'width'=>70)
		)
	)
);
$pdf->ezText('', 40);



$cols = array(
	$txt['descrizione'] => $txt['descrizione_tit'],
	$txt['importo'] => $txt['importo_tit']
);
$data = array(
	array($txt['descrizione'] => $_POST['descrizione'], $txt['importo'] => $_POST['costo'].'€  ')
);
$pdf->ezTable($data, $cols,'',
	array(
		'width' => 526,
		'showLines' => 1,
		'innerLineThickness' => .4,
		'cols' => array(
			$txt['descrizione'] => array('justification' => 'left'),
			$txt['importo'] => array('justification' => 'right', 'width'=>100)
		)
	)
);

if($_POST['imponibile'] == 'si') {
	
	$data = array(
		array($txt['descrizione'] => $txt['imponibile'], $txt['importo'] => $_POST['costo'].'€  '),
		array($txt['descrizione'] => $txt['iva_20'], $txt['importo'] => round($_POST['costo']*.2, 2).'€  ')
	);
	$pdf->ezTable($data, $cols,'',
		array(
			'width' => 526,
			'showHeadings' => 0,
			'innerLineThickness' => .4,
			'showLines' => 1,
			'shaded' => 0,
			'cols' => array(
				$txt['descrizione'] => array('justification' => 'right'),
				$txt['importo'] => array('justification' => 'right', 'width' => 100)
			)
		)
	);

} else {
	$data = array(
		array($txt['descrizione'] => $txt['imponibile'], $txt['importo'] => $_POST['costo'].'€  '),
		array($txt['descrizione'] => $txt['iva'], $txt['importo'] => 'art.41 D.L. 331/1993')
	);
	$pdf->ezTable($data, $cols,'',
		array(
			'width' => 526,
			'showHeadings' => 0,
			'innerLineThickness' => .4,
			'showLines' => 1,
			'shaded' => 0,
			'cols' => array(
				$txt['descrizione'] => array('justification' => 'right'),
				$txt['importo'] => array('justification' => 'right', 'width' => 100)
			)
		)
	);
}

$data = array(
	array($txt['descrizione'] => $txt['totale'], $txt['importo'] => $TOTALE . '€  ')
);
$pdf->ezTable($data, $cols,'',
	array(
		'width' => 526,
		'showHeadings' => 0,
		'innerLineThickness' => .4,
		'showLines' => 1,
		'cols' => array(
			$txt['descrizione'] => array('justification' => 'right'),
			$txt['importo'] => array('justification' => 'right', 'width' => 100)
		),
		'fontSize' => '13'
	)
);

$pdf->addText(30, 20, 14, $txt['titolo']);

$pdf->ezStream();

?>