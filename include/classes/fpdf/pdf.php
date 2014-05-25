<?php

/**
 * pdf.php
 *
 * PHP version 5
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  PDF
 * @package   PDF
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 * @see       FPDF
 */

require('mc_table.php');

/**
 * The PDF class extends the FPDF class and the PDF_MC_Table class for creating an PDF with the repertorization result
 *
 * The PDF class writes a customized header and footer on each PDF page
 * and creates the result table prepared for PDF.
 *
 * @category  PDF
 * @package   PDF
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 */
class PDF extends PDF_MC_Table {
	
	/**
	 * Header writes the header of the PDF page
	 *
	 * @return void
	 * @access public
	 */
	function Header() {
		// Arial bold 15
		$this->SetFont('Arial','B',18);
		// Title
		$h1 = _("Repertorization result");
		$this->Cell(0,0,$h1,0,0,'C');
		// Line break
		$this->Ln(12);
	}
	
	/**
	 * Footer writes the footer of the PDF page
	 *
	 * @return void
	 * @access public
	 */
	function Footer() {
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',9);
		// powered by
		$this->Cell(0,7,'powered by OpenHomeopath',0,0,'C',false,'http://openhomeo.org/openhomeopath');
	}
	
	/**
	 * create_result_table creates the result table for the PDF
	 *
	 * @param array   $header_ar         contains the data for the table header
	 * @param array   $first_row_ar      contains the data of the first row of the table
	 * @param array   $data_ar           contains the data of the table body
	 * @param integer $symptom_col_width Column width for the second column containing the symptom.
	 *                                   If 0 it will be calculated.
	 * @param integer $col_width         Column width for the following columns.
	 *                                   If 0 it will be calculated.
	 * @return void
	 * @access public
	 */
	function create_result_table($header_ar, $first_row_ar, $data_ar, $symptom_col_width = 0, $col_width = 0) {
		$col_num = count($header_ar);
		$this->SetFont('Arial','B',8.5);
		
		// column width for the first column containing the rubric degree
		$col_width_ar[0] = 9.2;
		if ($symptom_col_width === 0) {
			$symptom_col_width = $this->GetStringWidth($first_row_ar[1]);
			$this->SetFont('','');
			foreach($data_ar as $row_ar) {
				if ($this->GetStringWidth($row_ar[1]) > $symptom_col_width) {
					$symptom_col_width = $this->GetStringWidth($row_ar[1]);
				}
			}
			$this->SetFont('','B');
		}
		$col_width_ar[1] = $symptom_col_width;
		$cur_col_width = $col_width;
		for($i=2;$i<$col_num;$i++) {
			if ($col_width === 0) {
				$cur_col_width = max($this->GetStringWidth($header_ar[$i]), $this->GetStringWidth($first_row_ar[$i])) + 2.4;
			}
			$col_width_ar[$i] = $cur_col_width;
		}
		$aligns_ar[0] = 'C';
		$aligns_ar[1] = 'L';
		for($i=2;$i<$col_num;$i++) {
			$aligns_ar[$i] = 'C';
		}
		$this->SetWidths($col_width_ar);
		$this->table_header($header_ar, $first_row_ar, $aligns_ar);
		// Data
		$fill = false;
		foreach($data_ar as $row_ar) {
			$this->SetFill($fill);
			if ($this->Row($row_ar) === false) {
				$this->table_header($header_ar, $first_row_ar, $aligns_ar);
				$fill = false;
				$this->SetFill($fill);
				$this->Row($row_ar);
			}
			$fill = !$fill;
		}
	}


	/**
	 * table_header builds the table header
	 *
	 * table_header builds the table header including the first row of the repertorization table.
	 * The header row contains the remedies and the first row the grades/hits.
	 *
	 * @param array $header_ar    contains the content of the header row
	 * @param array $first_row_ar contains the content of the first row
	 * @param array $aligns_ar    contains the column alignments
	 * @return void
	 * @access public
	 */
	function table_header($header_ar, $first_row_ar, $aligns_ar) {
		$this->SetFont('Arial','B',14);
		$h2 = _("Result table");
		if ($this->table_page > 1) {
			$h2 .= " (" . _("page") . " " . $this->table_page . ")";
		}
		$this->Cell(0,0,$h2,0,0,'C');
		$this->Ln(8);
		$this->SetFont('','B',8.5);
		$this->SetAligns('C');
		$this->SetCellHeight(7);
		// Colors, line width
		$this->SetDrawColor(153);
		$this->SetLineWidth(.3);
		// Header
		$this->SetFill(true);
		$this->SetFillColor(230,230,127);
		$this->Row($header_ar);
		// Color restoration
		$this->SetFillColor(253,247,181);
		// first row
		$this->Row($first_row_ar);
		$this->SetFont('','');
		// reset for Data
		$this->SetAligns($aligns_ar);
		$this->SetCellHeight(6);
	}
	
}
?>