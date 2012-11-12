<?php

namespace Saaspose\Pdf;

/*
* Deals with PDF document level aspects
*/
class Document
{
        public $FileName = "";


		public function Document($fileName)
        {
            $this->FileName = $fileName;
        }

		/*
		* Gets the page count of the specified PDF document
		*/
        public function GetPageCount()
        {
			 //build URI
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/pages";

			//sign URI
			$signedURI = Utils::Sign($strURI);

			//get response stream
			$responseStream = Utils::ProcessCommand($signedURI, "GET", "");

			$json = json_decode($responseStream);

			 return count($json->Pages->List);
        }
	/*
    * Merges two PDF documents
	* @param string $basePdf (name of the base/first PDF file)
	* @param string $newPdf (name of the second PDF file to merge with base PDF file)
	* @param string $startPage (page number to start merging second PDF: enter 0 to merge complete document)
	* @param string $endPage (page number to end merging second PDF: enter 0 to merge complete document)
	* @param string $sourceFolder (name of the folder where base/first and second input PDFs are present)
	*/
	public function AppendDocument($basePdf, $newPdf, $startPage, $endPage, $sourceFolder) {
       try {
			//check whether files are set or not
			if ($basePdf == "")
				throw new Exception("Base file not specified");
			if ($newPdf == "")
				throw new Exception("File to merge is not specified");

			//build URI to merge PDFs
			if ($sourceFolder == "")
				$strURI = Product::$BaseProductUri . "/pdf/" . $basePdf .
							"/appendDocument?appendFile=" . $newPdf . "&startPage=" .
							$startPage . "&endPage=" . $endPage;
			else
				$strURI = Product::$BaseProductUri . "/pdf/" . $basePdf .
							"/appendDocument?appendFile=" . $sourceFolder . "/" . $newPdf .
							"&startPage=" . $startPage . "&endPage=" . $endPage .
							"&folder=" . $sourceFolder;

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save merged PDF on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($sourceFolder . "/" . $basePdf);
				$outputPath = SaasposeApp::$OutPutLocation . $basePdf;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }


	/*
    * Merges tow or more PDF documents
	* @param array $sourceFiles (list of PDF files to be merged)
	*/
	public function MergeDocuments(array $sourceFiles = array()) {
       try {
		   	$mergedFileName = $this->FileName;
			//check whether files are set or not
			if ($mergedFileName == "")
				throw new Exception("Output file not specified");
			if (empty($sourceFiles))
				throw new Exception("File to merge are not specified");
			if (count($sourceFiles) < 2)
				throw new Exception("Two or more files are requred to merge");


			//Build JSON to post
			$documentsList = array('List'=> $sourceFiles);
			$json = json_encode($documentsList);

			$strURI = Product::$BaseProductUri . "/pdf/" . $mergedFileName . "/merge";

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = json_decode(Utils::processCommand($signedURI, "PUT", "json", $json));

			if($responseStream->Code == 200)
				return true;
			else
				return false;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Creates a PDF from HTML
	* @param string $pdfFileName (name of the PDF file to create)
	* @param string $htmlFileName (name of the HTML template file)
	*/
	public function CreateFromHtml($pdfFileName, $htmlFileName) {
       try {
			//check whether files are set or not
			if ($pdfFileName == "")
				throw new Exception("PDF file name not specified");
			if ($htmlFileName == "")
				throw new Exception("HTML template file name not specified");

			//build URI to create PDF
			$strURI = Product::$BaseProductUri . "/pdf/" . $pdfFileName .
							"?templateFile=" . $htmlFileName . "&templateType=html";

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save PDF file on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($pdfFileName);
				$outputPath = SaasposeApp::$OutPutLocation . $pdfFileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Creates a PDF from XML
	* @param string $pdfFileName (name of the PDF file to create)
	* @param string $xsltFileName (name of the XSLT template file)
	* @param string $xmlFileName (name of the XML file)
	*/
	public function CreateFromXml($pdfFileName, $xsltFileName, $xmlFileName) {
       try {
			//check whether files are set or not
			if ($pdfFileName == "")
				throw new Exception("PDF file name not specified");
			if ($xsltFileName == "")
				throw new Exception("XSLT file name not specified");
			if ($xmlFileName == "")
				throw new Exception("XML file name not specified");

			//build URI to create PDF
			$strURI = Product::$BaseProductUri . "/pdf/" . $pdfFileName . "?templateFile=" .
						$xsltFileName . "&dataFile=" . $xmlFileName .  "&templateType=xml";

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save PDF file on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($pdfFileName);
				$outputPath = SaasposeApp::$OutPutLocation . $pdfFileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
	* Gets the FormField count of the specified PDF document
	*/
    public function GetFormFieldCount()
    {
		//build URI
		$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/fields";

		//sign URI
		$signedURI = Utils::Sign($strURI);

		//get response stream
		$responseStream = Utils::ProcessCommand($signedURI, "GET", "");

		$json = json_decode($responseStream);

		 return count($json->Fields->List);
    }

	/*
	* Gets the list of FormFields from the specified PDF document
	*/
    public function GetFormFields()
    {
		//build URI
		$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/fields";

		//sign URI
		$signedURI = Utils::Sign($strURI);

		//get response stream
		$responseStream = Utils::ProcessCommand($signedURI, "GET", "");

		$json = json_decode($responseStream);

		 return $json->Fields->List;
    }

	/*
	* Gets a particular form field
	* $fieldName
	*/
    public function GetFormField($fieldName)
    {
		//build URI
		$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/fields/" . $fieldName;

		//sign URI
		$signedURI = Utils::Sign($strURI);

		//get response stream
		$responseStream = Utils::ProcessCommand($signedURI, "GET", "");

		$json = json_decode($responseStream);

		 return $json->Field;
    }

	/*
    * Creates an Empty Pdf document
	* @param string $pdfFileName (name of the PDF file to create)
	*/
	public function CreateEmptyPdf($pdfFileName) {
       try {
			//check whether files are set or not
			if ($pdfFileName == "")
				throw new Exception("PDF file name not specified");

			//build URI to create PDF
			$strURI = Product::$BaseProductUri . "/pdf/" . $pdfFileName;

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save PDF file on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($pdfFileName);
				$outputPath = SaasposeApp::$OutPutLocation . $pdfFileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Adds new page to opened Pdf document
	*/
	public function AddNewPage() {
       try {
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			//build URI to add page
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/pages";

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save PDF file on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->FileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->FileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Deletes selected page from Pdf document
	* $pageNumber
	*/
	public function DeletePage($pageNumber) {
       try {
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			//build URI to delete page
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/pages/" . $pageNumber;

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save PDF file on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->FileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->FileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Moves selected page in Pdf document to new location
	* $pageNumber
	* $newLocation
	*/
	public function MovePage($pageNumber, $newLocation) {
       try {
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			//build URI to move page
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/pages/" . $pageNumber .
						"/movePage?newIndex=" . $newLocation;

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save PDF file on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->FileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->FileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Replaces Image in PDF File using Local Image Stream
	* $pageNumber
	* $imageIndex
	* $imageStream
	*/
	public function ReplaceImageUsingStream($pageNumber, $imageIndex, $imageStream) {
       try {
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			//build URI to replace image
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/pages/" . $pageNumber .
						"/images/" . $imageIndex;

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "", $imageStream);

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save PDF file on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->FileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->FileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Replaces Image in PDF File using Local Image Stream
	* $pageNumber
	* $imageIndex
	* $fileName
	*/
	public function ReplaceImageUsingFile($pageNumber, $imageIndex, $fileName) {
       try {
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			//build URI to replace image
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/pages/" . $pageNumber .
						"/images/" . $imageIndex . "?imageFile=" . $fileName;

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save PDF file on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->FileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->FileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Get all the properties of the specified document
	*/

	public function GetDocumentProperties(){
		try{

			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			//build URI to replace image
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::Sign($strURI);
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$response_arr = json_decode($responseStream);

			return $response_arr->DocumentProperties->List;


		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Get specified properity of the document
	* @param string $propertyName
	*/

	public function GetDocumentProperty($propertyName=""){
		try{

			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			if ($propertyName == "")
				throw new Exception("Property name not specified");

			//build URI to replace image
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/documentProperties/" . $propertyName;

			//sign URI
			$signedURI = Utils::Sign($strURI);
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$response_arr = json_decode($responseStream);

			return $response_arr->DocumentProperty;


		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Set specified properity of the document
	* @param string $propertyName
	* @param string $propertyValue
	*/

	public function SetDocumentProperty($propertyName="",$propertyValue=""){
		try{

			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			if ($propertyName == "")
				throw new Exception("Property name not specified");

			//build URI to replace image
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/documentProperties/" . $propertyName;

			$put_arr["Value"] = $propertyValue;
			$json = json_encode($put_arr);

			//sign URI
			$signedURI = Utils::Sign($strURI);
			$responseStream = Utils::processCommand($signedURI, "PUT", "json", $json);

			$response_arr = json_decode($responseStream);

			return $response_arr->DocumentProperty;


		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Remove all properties of the document
	*/

	public function RemoveAllProperties(){
		try{

			if ($this->FileName == "")
				throw new Exception("PDF file name not specified");

			//build URI to replace image
			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::Sign($strURI);
			$responseStream = Utils::processCommand($signedURI, "DELETE", "", "");

			$response_arr = json_decode($responseStream);

			return $response_arr->Code == 200?true:false;


		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}


}