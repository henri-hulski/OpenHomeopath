<?php

/**
 * mc_table.php
 *
 * @category  PDF
 * @package   PDF_MC
 * @author    Olivier <olivier@fpdf.org>
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @link      http://fpdf.de/downloads/addons/3/
 * @see       FPDF
 */

require('fpdf.php');

/**
 * The PDF_MC_Table class extends FPDF for creating tables with MultiCells.
 *
 * The goal of this class is to build a table from MultiCells.
 * As MultiCells go to the next line after being output, the base idea consists in saving the current position,
 * printing the MultiCell and resetting the position to its right.
 * There is a difficulty, however, if the table is too long: page breaks.
 * Before outputting a row, it is necessary to know whether it will cause a break or not.
 * If it does overflow, a manual page break must be done first.
 * To do so, the height of the row must be known in advance; it is the maximum of the heights of the MultiCells it is made up of.
 * To know the height of a MultiCell, the NbLines() method is used: it returns the number of lines a MultiCell will occupy.
 *
 * @category  PDF
 * @package   PDF_MC
 * @author    Olivier <olivier@fpdf.org>
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 */
class PDF_MC_Table extends FPDF {

	/**
	 * The array of column widths
	 * @var array
	 * @access public
	 */
	public $widths;
	
	/**
	 * The array of column alignments or one alignment for all columns
	 * @var array|string
	 * @access public
	 */
	public $aligns;
	
	/**
	 * The cell height
	 * @var number
	 * @access public
	 */
	public $cell_height = 6;
	
	/**
	 * True if the table row should be colored
	 * @var boolean
	 * @access public
	 */
	public $fill = false;
	
	/**
	 * The page number
	 * @var integer
	 * @access public
	 */
	public $table_page = 1;
	
	/**
	 * Set the array of column widths
	 *
	 * @param array $w column widths array
	 * @return void
	 * @access public
	 */
	function SetWidths($w) {
		$this->widths=$w;
	}
	
	/**
	 * Set the array of column alignments or one alignment for all columns
	 *
	 * @param array|string $a column alignments array or one alignment for all columns
	 * @return void
	 * @access public
	 */
	function SetAligns($a) {
		$this->aligns=$a;
	}
	
	/**
	 * Set the cell height
	 *
	 * @param number $ch cell height
	 * @return void
	 * @access public
	 */
	function SetCellHeight($ch) {
		$this->cell_height=$ch;
	}
	
	/**
	 * Set if the row should be colored
	 *
	 * @param boolean $f true if the table row should be colored
	 * @return void
	 * @access public
	 */
	function SetFill($f) {
		$this->fill=$f;
	}
	
	/**
	 * Print a row if no page break is needed
	 *
	 * @param array $data contains the row data
	 * @return boolean true if success, false if page break is needed
	 * @access public
	 */
	function Row($data) {
		// Calculate the height of the row
		$nb=0;
		for ($i=0;$i<count($data);$i++) {
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		}
		$h=$this->cell_height*$nb;
		// Issue a page break first if needed and return so that you can add the table header again
		if ($this->CheckPageBreak($h)) {
			return false;
		} else {
			// Draw the cells of the row
			$style = $this->fill ? 'DF' : 'D';
			for ($i=0;$i<count($data);$i++) {
				$w=$this->widths[$i];
				$a='L';
				if (isset($this->aligns)) {
					$a=is_array($this->aligns) ? $this->aligns[$i] : $this->aligns;
				}
				// Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				// Draw the border
				$this->Rect($x,$y,$w,$h,$style);
				// Print the text
				$this->MultiCell($w,$this->cell_height,$data[$i],0,$a);
				// Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
			return true;
		}
	}
	
	/**
	 * Check if page break is needed
	 *
	 * @param number $h the height of the row
	 * @return boolean true if a page break is needed
	 * @access public
	 */
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if ($this->GetY()+$h>$this->PageBreakTrigger) {
			$this->AddPage($this->CurOrientation);
			$this->table_page++;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Computes the number of lines a MultiCell of width w will take
	 *
	 * @param number $w   cell width
	 * @param string $txt cell text
	 * @return integer number of lines a MultiCell of width w will take
	 * @access public
	 */
	function NbLines($w,$txt)
	{
		$cw=&$this->CurrentFont['cw'];
		if ($w==0) {
			$w=$this->w-$this->rMargin-$this->x;
		}
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if ($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while ($i<$nb) {
			$c=$s[$i];
			if ($c=="\n") {
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if ($c==' ') {
				$sep=$i;
			}
			$l+=$cw[$c];
			if ($l>$wmax) {
				if ($sep==-1) {
					if ($i==$j) {
						$i++;
					}
				} else {
					$i=$sep+1;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			} else {
				$i++;
			}
		}
		return $nl;
	}
}
