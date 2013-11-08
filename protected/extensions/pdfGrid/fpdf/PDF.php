<?php
Yii::import('ext.pdfGrid.fpdf.fpdf');

class PDF extends fpdf
{
	public $title = 'Informe';
	public $subTitle = '';
	public $widths;
	public $aligns;
	public $rowHeight = 6;
	public $imagePath;
	//longitud total de la tabla
	public $tableWidth = 275;
	public $showLogo = false;
	public $headerDetails = false;
	
	//variables of html parser
	var $B;
	var $I;
	var $U;
	var $HREF;
	var $fontList;
	var $issetfont;
	var $issetcolor;

	// Cabecera de página
	public function Header()
	{
		// Logo
		if($this->showLogo)
			$this->Image($this->imagePath,10,8,0,18);
		// Título
		$this->SetFont('Arial','',14);
		$this->Cell(0, 10, $this->title, 0, 1, 'C');
		// Subítulo
		$this->SetFont('Arial','',12);
		$this->Cell(0, 6, $this->subTitle, 0, 1, 'C');
		
		// Salto de línea
		//$this->Ln(20);
		
		if( isset($this->headerDetails) ) {
			//guardar coordenadas
			$x = $this->GetX(); $y = $this->GetY();
			
			$this->SetY(10);
			$this->SetX(-70);
			$this->SetFont('Arial','',10);
			$txt = "Emitido por: ".Yii::app()->user->name."\n";
			$txt .= "Fecha : ".date('d/m/Y')."\n";
			$txt .= "Página ".$this->PageNo()."/{nb}";
			$this->MultiCell(60, 5, $txt, 0, 'L');
			
			//restaurar coordenadas
			$this->SetX($x); $this->SetY($y);
		}
	}
	
	public function SetBold()
	{
		$this->setFont('', 'B');
	}
	
	public function SetItalic()
	{
		$this->setFont('', 'I');
	}
	

	public function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}

	//Set the array of column alignments
	public function SetAligns($a)
	{
		$this->aligns=$a;
	}

	/**
	 * $config puede tener:
	 * 		border=>true/false
	 * 		fill=>true/false
	 */
	public function Row($data, $config)
	{
		$config['border'] = !empty($config['border']);
		$config['fill'] = !empty($config['fill']);
		$config['header'] = !empty($config['header']);
	
		//Calculate the height of the row
		$nb	= $this->NbLines($data);
		$h	= $this->rowHeight*max($nb);
		
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++) {
			$w=$this->widths[$i];
			if($config['header'])
				$a = 'C';
			else
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			
			//Draw the border
			if($config['border'])
				$this->Rect($x, $y, $w, $h);
				
			//Print the text
			$this->MultiCell($w, $h/$nb[$i], $data[$i], 0, $a, $config['fill']);
			
			//Put the position to the right of the cell
			$this->SetXY($x+$w, $y);
		}
		
		//Go to the next line
		$this->Ln($h);
	}

	private function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	//Computes the number of lines a MultiCell of width w will take
	private function NbLines($data)
	{
		$resp = array();
		for($n=0;$n<count($data);$n++) {
			$w		= $this->widths[$n];
			$txt	= $data[$n];
			
			$cw=&$this->CurrentFont['cw'];
			
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r", '', $txt);
			$nb=strlen($s);
			
			if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
			
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb) {
				$c=$s[$i];
				
				if($c=="\n") {
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
				}
				
				if($c==' ')
					$sep=$i;
					
				$l+=$cw[$c];
				if($l>$wmax) {
					if($sep==-1)
					{
						if($i==$j)
							$i++;
					} else
						$i=$sep+1;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
				}
				else
					$i++;
			}
			
			$resp[] = $nl;
		}
		
		return $resp;
	}

	// public function PDF_HTML($orientation='P', $unit='mm', $format='A4')
	// {
	//     //Call parent constructor
	//     $this->FPDF($orientation,$unit,$format);
	//     //Initialization
	//     $this->B=0;
	//     $this->I=0;
	//     $this->U=0;
	//     $this->HREF='';
	//     $this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
	//     $this->issetfont=false;
	//     $this->issetcolor=false;
	// }

	public function WriteHTML($html)
	{
	    //HTML parser
	    $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
	    $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
	    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
	    foreach($a as $i=>$e)
	    {
	        if($i%2==0)
	        {
	            //Text
	            if($this->HREF)
	                $this->PutLink($this->HREF,$e);
	            else
	                $this->Write(5,stripslashes($e));
	        }
	        else
	        {
	            //Tag
	            if($e[0]=='/')
	                $this->CloseTag(strtoupper(substr($e,1)));
	            else
	            {
	                //Extract attributes
	                $a2=explode(' ',$e);
	                $tag=strtoupper(array_shift($a2));
	                $attr=array();
	                foreach($a2 as $v)
	                {
	                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
	                        $attr[strtoupper($a3[1])]=$a3[2];
	                }
	                $this->OpenTag($tag,$attr);
	            }
	        }
	    }
	}

	public function OpenTag($tag, $attr)
	{
	    //Opening tag
	    switch($tag){
	        case 'STRONG':
	            $this->SetStyle('B',true);
	            break;
	        case 'EM':
	            $this->SetStyle('I',true);
	            break;
	        case 'B':
	        case 'I':
	        case 'U':
	            $this->SetStyle($tag,true);
	            break;
	        case 'A':
	            $this->HREF=$attr['HREF'];
	            break;
	        case 'IMG':
	            if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
	                if(!isset($attr['WIDTH']))
	                    $attr['WIDTH'] = 0;
	                if(!isset($attr['HEIGHT']))
	                    $attr['HEIGHT'] = 0;
	                $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
	            }
	            break;
	        case 'TR':
	        case 'BLOCKQUOTE':
	        case 'BR':
	            $this->Ln(5);
	            break;
	        case 'P':
	            $this->Ln(10);
	            break;
	        case 'FONT':
	            if (isset($attr['COLOR']) && $attr['COLOR']!='') {
	                $coul=hex2dec($attr['COLOR']);
	                $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
	                $this->issetcolor=true;
	            }
	            if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
	                $this->SetFont(strtolower($attr['FACE']));
	                $this->issetfont=true;
	            }
	            break;
	    }
	}

	public function CloseTag($tag)
	{
	    //Closing tag
	    if($tag=='STRONG')
	        $tag='B';
	    if($tag=='EM')
	        $tag='I';
	    if($tag=='B' || $tag=='I' || $tag=='U')
	        $this->SetStyle($tag,false);
	    if($tag=='A')
	        $this->HREF='';
	    if($tag=='FONT'){
	        if ($this->issetcolor==true) {
	            $this->SetTextColor(0);
	        }
	        if ($this->issetfont) {
	            $this->SetFont('arial');
	            $this->issetfont=false;
	        }
	    }
	}

	public function SetStyle($tag, $enable)
	{
	    //Modify style and select corresponding font
	    $this->$tag+=($enable ? 1 : -1);
	    $style='';
	    foreach(array('B','I','U') as $s)
	    {
	        if($this->$s>0)
	            $style.=$s;
	    }
	    $this->SetFont('',$style);
	}

	public function PutLink($URL, $txt)
	{
	    //Put a hyperlink
	    $this->SetTextColor(0,0,255);
	    $this->SetStyle('U',true);
	    $this->Write(5,$txt,$URL);
	    $this->SetStyle('U',false);
	    $this->SetTextColor(0);
	}
}