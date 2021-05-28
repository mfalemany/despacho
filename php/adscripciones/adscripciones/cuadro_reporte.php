<?php
class cuadro_reporte extends despacho_ei_cuadro
{
	function vista_pdf(toba_vista_pdf $salida)
	{
		//Cambio lo márgenes accediendo directamente a la librería PDF
		$pdf = $salida->get_pdf();
		$pdf->ezSetMargins(80, 50, 30, 30);	//top, bottom, left, right

		//Pie de página
		$formato = 'Pagina {PAGENUM} de {TOTALPAGENUM}';
		$pdf->ezStartPageNumbers(300, 20, 8, 'left', $formato, 1);	//x, y, size, pos, texto, pagina inicio
		
		//Invoco la salida pdf original del cuadro
		parent::vista_pdf($salida);		

		//Encabezado
		foreach ($pdf->ezPages as $pageNum=>$id){
			$pdf->reopenObject($id);
			$imagen = toba::proyecto()->get_path().'/www/img/logo_grande.jpg';
			$pdf->addJpegFromFile($imagen, 30, 750, 100, 80);	//imagen, x, y, ancho, alto
			$pdf->addText (175, 780, 20, "Reporte de Adscripciones");
			$pdf->closeObject();		
		}		
		
	}
}
?>