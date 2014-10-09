<?php
function getCol($col)
{
    $col++;
    $alph =array("", 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');  
    $ret = $alph[floor($col/26)].$alph[$col%26];
    return $ret;
}

App::import('Vendor', 'phpexcel', array('file' => 'PHPExcel.php'));
App::import('Vendor', 'iofactory', array('file' => 'PHPExcel'.DS.'IOFactory.php'));
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)->setTitle("Paniere Orizzontale");  
// debug($users); 
// debug($totals); 
// die();

$objPHPExcel->setActiveSheetIndex(0)->getStyle('1')->getFont()->setBold("true");

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Codice');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Descrizione');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Opzione 1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Opzione 2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'UM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Prezzo');

$rownum = 2;
foreach($totals as $product)
{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$rownum", $product['Product']['code']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$rownum", $product['Product']['name']);
    if(!empty($product['Product']['option_1'])) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$rownum", $product['Product']['option_1'].': '.$product['OrderedProduct']['option_1']);
	}
    if(!empty($product['Product']['option_2'])) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$rownum", $product['Product']['option_2'].': '.$product['OrderedProduct']['option_2']);
	}
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$rownum", $product['Product']['units']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F$rownum", $product['Product']['value']);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F$rownum")->getNumberFormat()->setFormatCode( '[$€ ]#,##0.00_-');

    $colnum = 6;
    foreach($product['Users'] as $user)
    {
        if(!empty($user))
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colnum, $rownum, $user[0]['quantity']);
        $colnum++;
    }
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colnum, $rownum, "=SUM(G$rownum:".getCol($colnum-1)."$rownum)");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colnum, 1, 'Totale');
//     echo "=SUM(F$rownum:".getCol($colnum)."$rownum)";
    $rownum++;
}
$colnum = 6;
foreach($users as $user)
{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colnum, 1, $user['User']['last_name']. " ". $user['User']['first_name']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colnum, $rownum, $user['0']['total']);
    $objPHPExcel->setActiveSheetIndex(0)->getStyleByColumnAndRow($colnum,$rownum)->getNumberFormat()->setFormatCode( '[$€ ]#,##0.00_-');
    $colnum++;
    
}

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);


// Andrea INVERTO

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1)->setTitle("Paniere Verticale");  
$objPHPExcel->setActiveSheetIndex(1)->getStyle('A')->getFont()->setBold("true");

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A1', 'Codice');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A2', 'Descrizione');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A3', 'Opzione 1');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A4', 'Opzione 2');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A5', 'UM');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A6', 'Prezzo');

$colnum = 1;
foreach($totals as $product)
{
    $currentColumnName=PHPExcel_Cell::stringFromColumnIndex($colnum);
	
	$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum,1, $product['Product']['code']);
	$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum,2, $product['Product']['name']);
	$tmpCell = $currentColumnName ."1:". $currentColumnName . "4";
	$objPHPExcel->setActiveSheetIndex(1)->getStyle($tmpCell)->getAlignment()->setTextRotation(90);
	
	if(!empty($product['Product']['option_1'])) {
    		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum,3, $product['Product']['option_1'].': '.$product['OrderedProduct']['option_1']);
			
		
		
	}
    if(!empty($product['Product']['option_2'])) {
		$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum,4, $product['Product']['option_2'].': '.$product['OrderedProduct']['option_2']);
	}
    
	$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum,5,$product['Product']['units']);
	$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum,6, $product['Product']['value']);
	
	
    //$objPHPExcel->setActiveSheetIndex(0)->getStyle("F$rownum")->getNumberFormat()->setFormatCode( '[$€ ]#,##0.00_-');
	//$myCell= PHPExcel_Cell::stringFromColumnIndex($colnum) . "6";
	//$objPHPExcel->setActiveSheetIndex(1)->getStyle($myCell)->getNumberFormat()->setFormatCode( '[$€ ]#,##0.00_-');  
	$objPHPExcel->setActiveSheetIndex(1)->getStyle(PHPExcel_Cell::stringFromColumnIndex($colnum) . "6")->getNumberFormat()->setFormatCode( '[$€ ]#,##0.00_-');  

    $rownum = 8;
    foreach($product['Users'] as $user)
    {
        if(!empty($user))
            $objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum, $rownum, $user[0]['quantity']);
        $rownum++;
    }
	
    //$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum, $rownum, "=SUM(G$rownum:".getCol($colnum-1)."$rownum)");
    //$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum, 1, 'Totale');
	
	$currentColumnName=PHPExcel_Cell::stringFromColumnIndex($colnum);
	$tmpCell = $currentColumnName ."8:". $currentColumnName . ($rownum-1);
	//$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum, $rownum, "=SUM(G$rownum:".getCol($colnum-1)."$rownum)");
	$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum, $rownum+1, "=SUM($tmpCell)");
	$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(0, $rownum+1, 'Totale');

    $colnum++;
}

$currentColumnName=PHPExcel_Cell::stringFromColumnIndex($colnum+1);
$tmpCell = $currentColumnName ."7";
$objPHPExcel->setActiveSheetIndex(1)->setCellValue($tmpCell, 'Pezzi');

$currentColumnName=PHPExcel_Cell::stringFromColumnIndex($colnum+2);
$tmpCell = $currentColumnName ."7";
$objPHPExcel->setActiveSheetIndex(1)->setCellValue($tmpCell, 'Totale');


$rownum = 8;
foreach($users as $user)
{
    $objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow(0,$rownum, $user['User']['last_name']. " ". $user['User']['first_name']);
    $tmpCell = "B". $rownum . ":" . PHPExcel_Cell::stringFromColumnIndex($colnum-1) . $rownum;
	$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum+1, $rownum, "=SUM($tmpCell)");
	$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum+2, $rownum, $user['0']['total']);
    $objPHPExcel->setActiveSheetIndex(1)->getStyleByColumnAndRow($colnum+2,$rownum)->getNumberFormat()->setFormatCode( '[$€ ]#,##0.00_-');
    $rownum++;
    
}

// Add grand totals in the lower right corner
$currentColumnName=PHPExcel_Cell::stringFromColumnIndex($colnum+1);
$tmpCell = $currentColumnName ."8:". $currentColumnName . ($rownum-1);
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum+1, $rownum+1, "=SUM($tmpCell)");
$currentColumnName=PHPExcel_Cell::stringFromColumnIndex($colnum+2);
$tmpCell = $currentColumnName ."8:". $currentColumnName . ($rownum-1);
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colnum+2, $rownum+1, "=SUM($tmpCell)");
$objPHPExcel->setActiveSheetIndex(1)->getStyleByColumnAndRow($colnum+2,$rownum+1)->getNumberFormat()->setFormatCode( '[$€ ]#,##0.00_-');

// Set autosize for Name column (A) and for the last column where each users'order total is displayed
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($currentColumnName)->setAutoSize(true);

// Set Horizontal alignment = center
$currentColumnName=PHPExcel_Cell::stringFromColumnIndex($colnum-1);
$tmpCell = "B1:". $currentColumnName . ($rownum+1);
$objPHPExcel->setActiveSheetIndex(1)->getStyle($tmpCell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL);

/*
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension('D')->setAutoSize(true);
*/

// FINITO, SCRIVO IL FILE


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>