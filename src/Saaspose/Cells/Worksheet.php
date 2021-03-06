<?php

namespace Saaspose\Cells;

use Saaspose\Common\Utils;
use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;

/**
* This class contains features to work with charts
*/
class Worksheet
{
	public $fileName = "";
	public $worksheetName = "";

    public function __construct($fileName, $worksheetName)
    {
        $this->fileName = $fileName;
		$this->worksheetName = $worksheetName;

		//check whether file is set or not
		if ($this->fileName == "") {
			throw new Exception("No file name specified");
		}

		//check whether workshett name is set or not
		if ($this->worksheetName == "") {
			throw new Exception("Worksheet name not specified");
		}
    }

	/**
    * Gets a list of cells
	* $offset
	* $count
	*/
	public function getCellsList($offset, $count)
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells?offset=" .
						$offset . "&count=" . $count;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			$listCells = array();

			foreach ($json->Cells->CellList as $cell) {
				$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells" . $cell->link->Href;

				$signedURI = Utils::sign($strURI);

				$responseStream = Utils::processCommand($signedURI, "GET", "", "");
				$json = json_decode($responseStream);

				array_push($listCells, $json->Cell);
			}
			return $listCells;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets a list of rows from the worksheet
	*/
	public function getRowsList()
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells/rows";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			$listRows = array();

			foreach ($json->Rows->RowsList as $row) {
				$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells/rows" . $row->link->Href;

				$signedURI = Utils::sign($strURI);

				$responseStream = Utils::processCommand($signedURI, "GET", "", "");
				$json = json_decode($responseStream);

				array_push($listRows, $json->Row);
			}
			return $listRows;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets a list of columns from the worksheet
	*/
	public function getColumnsList()
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells/columns";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			$listColumns = array();

			foreach ($json->Columns->ColumnsList as $column) {
				$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells/columns" . $column->link->Href;

				$signedURI = Utils::sign($strURI);

				$responseStream = Utils::processCommand($signedURI, "GET", "", "");
				$json = json_decode($responseStream);

				array_push($listColumns, $json->Column);
			}
			return $listColumns;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets maximum column index of cell which contains data or style
	* $offset
	* $count
	*/
	public function getMaxColumn($offset, $count)
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells?offset=" .
						$offset . "&count=" . $count;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->Cells->MaxColumn;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets maximum row index of cell which contains data or style
	* $offset
	* $count
	*/
	public function getMaxRow($offset, $count)
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells?offset=" .
						$offset . "&count=" . $count;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->Cells->MaxRow;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets cell count in the worksheet
	* $offset
	* $count
	*/
	public function getCellsCount($offset, $count)
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/cells?offset=" .
						$offset . "&count=" . $count;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->Cells->CellCount;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets AutoShape count in the worksheet
	*/
	public function getAutoShapesCount()
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/autoshapes";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->AutoShapes->AuotShapeList);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets a specific AutoShape from the sheet
	* $index
	*/
	public function getAutoShapeByIndex($index)
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/autoshapes/" . $index;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->AutoShape;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets charts count in the worksheet
	*/
	public function getChartsCount()
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/charts";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->Charts->ChartList);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets a specific chart from the sheet
	* $index
	*/
	public function getChartByIndex($index)
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/charts/" . $index;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->Chart;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets hyperlinks count in the worksheet
	*/
	public function getHyperlinksCount()
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/hyperlinks";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->Hyperlinks->HyperlinkList);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets a specific hyperlink from the sheet
	* $index
	*/
	public function getHyperlinkByIndex($index)
	{
		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/hyperlinks/" . $index;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->Hyperlink;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


}