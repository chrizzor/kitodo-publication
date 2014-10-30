<?php
namespace EWW\Dpf\Services;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * MetsExporter
 */
class MetsExporter extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * formData
	 *
	 * @var array
	 */
	protected $formData = array();

	/**
	 * metsData
	 *
	 * @var  string
	 */
	protected $metsData = '';

	protected $modsData = '';

	/**
	 * metsHeader
	 * @var string
	 */
	protected $metsHeader = '';

	protected $modsHeader = '';

	/**
	 * simpleXMLElement
	 */
	protected $sxe = NULL;

	/**
	 * xPathXMLGenerator
	 * @var object
	 */
	protected $parser = NULL;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->buildMets();

		// Constructor
		$this->sxe = new \SimpleXMLElement($this->metsHeader);

		// Parser
		include_once('xPathXMLGenerator.php');

		$this->parser = new xPathXMLGenerator();
	}

	/**
	 * returns the mets xml string
	 * @return string mets xml
	 */
	public function getMetsData()
	{
		return $this->metsData;
	}


	/**
	 * Build mets data structure
	 * @return string mets xml
	 */
	public function buildMets()
	{
		// Try to build mets structure
		
		// mets data beginning
		$this->metsHeader = '<mets:mets xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    						xmlns:mets="http://www.loc.gov/METS/" xmlns:xlink="http://www.w3.org/1999/xlink"
    						xsi:schemaLocation="http://www.loc.gov/METS/ http://www.loc.gov/standards/mets/version19/mets.v1-9.xsd">';

    	// Mets structure end
    	$this->metsHeader .= '</mets:mets>';



    	$this->modsHeader = '<mods:mods xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:mods="http://www.loc.gov/mods/v3" xmlns:slub="http://slub-dresden.de/"
    xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-5.xsd"
    version="3.5">';

    	$this->modsHeader .= '</mods:mods>';

    	$this->modsData = new \DOMDocument();
    	$this->modsData->loadXML($this->modsHeader);

    	// $this->metsData .= '<mets:dmdSec ID="DMD_000">
     //    					<mets:mdWrap MDTYPE="MODS">
     //        				  <mets:xmlData>';


        // Put mods xml here


        // $this->metsData .= '</mets:xmlData>
        // 					</mets:mdWrap>
    				// 		  </mets:dmdSec>';

    	// Put file sec here
    	// 
    	// 
    	

    	// Put structMap here
    	// 
    	//



    	
	}

	public function wrapMods($xml)
	{
		$newXML = '<mods:mods xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:mods="http://www.loc.gov/mods/v3" xmlns:slub="http://slub-dresden.de/"
    xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-5.xsd"
    version="3.5">';

    	$newXML .= $xml;
    	$newXML .= '</mods:mods>';

    	return $newXML;
	}

	public function buildModsFromForm()
	{
		// Build xml mods from form fields
		// print_r($this->formData);

		$this->walkFormDataRecursive($this->formData);

	}

	public function parseXPath($xPath)
	{
		//
		$xml = $this->parser->parse($xPath);

		return $xml;
	}

	/**
	 * Walks the form data array recursive to build mods xml
	 * @param  array $array   Array from form
	 * @param  string $lastKey Last key from node
	 * @return [type]          [description]
	 */
	public function walkFormDataRecursive($array, $lastKey = '')
	{
		foreach ($array as $key => $value) {
			if(is_array($value)){
				$this->walkFormDataRecursive($value, $key);
			} else {
				if($lastKey != 'files'){
					// $key == metadataObjectId
					// get object
					// get xpath and try to build mods xml
					print_r($key);
					print_r($value);
				}
			}	
		}
	}


	/**
	 * [customXPath description]
	 * @param  [type] $xPath [description]
	 * @param  SimpleXMLElement $sxe   SimpleXMLElement
	 * @return [type]        [description]
	 */
	public function customXPath($xPath)
	{
		$sxe = new \SimpleXMLElement($this->wrapMods('<mods:test>bla</mods:test>'));
		// Explode xPath
		$newPath = explode('#', $xPath);
		print_r($newPath);

		// xml erstellen...
		// und dann nacheinander schauen ob der pfad schon existiert
		// in dem gesamten xml !?
		// xml merge bauen!?
		// 
		// sxe in domdocument umwandeln
		// per importNode das soeben generierte xml importieren
		
		$praedicateFlag = false;
		$test = explode('[', $newPath[0]);
		if(count($test) > 1) {
			// praedicate is given
			// den 1. teil des xpath vor dem prädikat per sxe und dann value hinzufügen
			$path = $test[0]; 
			$praedicateFlag = true;

		} else {
			$path = $newPath[0];
			//$xPathAdd = $newPath[0].'='.$newPath[1];
		}


		if($sxe->xPath($newPath[0])) {
			// der xPath existiert es muss nur noch der 2. part hinzugefügt werden
			$node = $sxe->xPath($path);
			$domElement = dom_import_simplexml($node);
			$domElement->importNode($this->parseXPath($newPath[1]));

			print_r($domElement);
			// $sxe->addChild('bla', 'blubb');
			
			// 
		} else {
			// der xPath existiert nicht bzw. nur zum Teil
			// aktuelles XML Dokument nehmen
			// um importNode benutzen zu können
			
			$xml1 = $this->parseXPath($newPath[0]);

			$doc1 = new \DOMDocument();
			$doc1->loadXML($this->wrapMods($xml1));

			$domXPath = new \DOMXpath($doc1);

			// pfad bis 1 praedikat
			// print_r($path);
			$testElement = $domXPath->query('/mods:mods'.$path);






			$xml2 = $this->parseXPath($path.$newPath[1]);

			$doc2 = new \DOMDocument();
			$doc2->loadXML($this->wrapMods($xml2));

			$node = $doc2->getElementsByTagName("name")->item(0); //DOMNode


			// füge pfad nach # hinzu
			// print_r($testElement->item(0)); // DOMNode
			// print_r($testElement->item(0)->appendChild($node));

			// print_r($doc1->importNode($node));



			// print_r($node);

			$nodeToBeAppended = $doc1->importNode($node, true);
			// $doc1->documentElement->appendChild($nodeToBeAppended);
			$testElement->item(0)->appendChild($nodeToBeAppended);
			print_r($doc1);

			// print_r($xml1);
			// print_r($xml2);
			print_r("merged");
			print_r($doc1->saveXML());









			//print_r($this->wrapMods('<mods:test>bla</mods:test>'));print_r("\n");

			$sxe = new \SimpleXMLElement($this->wrapMods('<mods:test>bla</mods:test>'));


			$sxe2 = new \SimpleXMLElement($this->wrapMods($xml2));
			// $sxe2 = simplexml_load_string($xml);

			// print_r("\nSXE1");
			// print_r($sxe->asXML());
			// print_r("\nSXE2");
			// print_r($sxe2->asXML());

			// print_r($sxe2->xPath('/mods:mods')[0]->asXML());


			// foreach ($sxe2->children('mods') as $test) {print_r("test");
			// 	print_r($test);
			// }

			// $this->mergeXML($sxe, $sxe2);

			// print_r("\nmerged");
			// print_r($sxe->asXML());
			
			// $xml = $this->parseXPath($newPath[0]);
			// $doc = new \DOMDocument();
			// $doc->loadXML($this->wrapMods($xml));
			// $this->modsData->importNode($doc);

			// print_r($xml);
			// #print_r($this->parseXPath($newPath[1]));
			// $sxe2 = new \SimpleXMLElement($this->modsHeader); 
			// // $sxe = simplexml_load_string($xml);
			// print_r("test\n");
			// print_r($sxe2->xPath('mods:name'));
			// var_dump($newPath[0]);
			// $sxe2 = $sxe2->xPath($newPath[0]);

			
			// print_r($sxe2->asXML());

			// get node by xpath with path
			// importNode parseXPath newPath
			


			// add to xpath only if no praedicate is given
			// $test = explode('[', $newPath[0]);
			// print_r($test);

			

			


			// return $this->parseXPath($xPathAdd);

		}

		// print_r($newPath);
	}


	function mergeXML(&$base, $add) 
	{ 
	    if ( $add->count() != 0 ) 
	        $new = $base->addChild($add->getName()); 
	    else 
	        $new = $base->addChild($add->getName(), $add); 
	    foreach ($add->attributes() as $a => $b) 
	    { 
	        $new->addAttribute($a, $b); 
	    } 
	    if ( $add->count() != 0 ) 
	    { 
	        foreach ($add->children() as $child) 
	        { 
	            mergeXML($new, $child); 
	        } 
	    } 
	} 

	/**
	 * Builds the xml wrapping part for mods
	 * @return xml
	 */
	public function buildModsWrap()
	{
		// Build wrap for mods
		
		$sxe = new \SimpleXMLElement($this->metsHeader);

		$dmdSec = $sxe->addChild('dmdSec');
		$dmdSec->addAttribute('ID', 'DMD_000');

		$mdWrap = $dmdSec->addChild('mdWrap');
		$mdWrap->addAttribute('MDTYPE', 'MODS');

		$xmlData = $mdWrap->addChild('xmlData');

		return $sxe;

	}

	/**
	 * Builds the xml fileSection part
	 * @return xml
	 */
	public function buildFileSection()
	{
		// Build xml Mets:fileSec
		$sxe = new SimpleXMLElement($this->metsHeader);

		// file section
		$fileSec = $sxe->addChild('fileSec');

		$fileGrp = $fileSec->addChild('fileGrp');
		

		$i = 0;
		// set xml for uploded files
		foreach ($this->formData[0]['files'] as $key => $value) {
			// convert counter to string (FILE_000)
			$counter = (string) $i;
			
			if(strlen($counter) == 1){

				$fileId = '00'.$counter;

			} else if(strlen($counter) == 2){

				$fileId = '0'.$counter;

			}

			// set xml
			$file = $fileGrp->addChild('file');

			$file->addAttribute('ID', 'FILE_'.$fileId);
			$file->addAttribute('MIMETYPE', 'application/pdf');

			$FLocat = $file->addChild('FLocat');
			$FLocat->addAttribute('LOCTYPE', 'URL');
			$FLocat->addAttribute('xlink:href', $value);

			$i++;
		}		

		return $sxe[0]->asXML();
	}

	/**
	 * Builds the xml structMap part
	 * @return xml
	 */
	public function buildStructureMap()
	{
		// Build xml Mets:structMap
		
		$sxe = new SimpleXMLElement($this->metsHeader);

		$structMap = $sxe->addChild('structMap');
		$structMap->addAttribute('TYPE', 'LOGICAL');

		$div = $structMap->addChild('div');
		$div->addAttribute('DMDID', 'DMD_000');

		$i = 0;
		// set xml for uploded files
		foreach ($this->formData[0]['files'] as $key => $value) {
			// convert counter to string (FILE_000)
			$counter = (string) $i;
			
			if(strlen($counter) == 1){

				$fileId = '00'.$counter;

			} else if(strlen($counter) == 2){

				$fileId = '0'.$counter;

			}

			$fptr = $div->addChild('fptr');
			$fptr->addAttribute('FILEID', 'FILE_'.$fileId);

			$i++;
		}

		var_dump($sxe->asXML());

	}

	// public function addXMLTag($xPath, $name, $value)
	// {
	// 	// Add XML Tag to the defined xpath
	// 	$result = $this->sxe->xpath($xPath);
	// 	$result[0]->addChild($name, $value);
	// }


	public function buildTestDataArray()
	{
		// build test data array
		
		$post = array();

		// title example
		$fields['12'] = 'TitelXY';
		$fields['13'] = 'SubTitelXY';
		$group['titleInfo'] = $fields;


		$fields2['14'] = 'ger';
		$group['language'] = $fields2;


		$roleTerm['15'] = 'author';
		$role['role'] = $roleTerm;
		$name['namePart'] = $role;
		$group['name'] = $name;

		// files
		$files[] = 'test.pdf';
		$files[] = 'document.pdf';
		$files[] = 'tof.pdf';
		$group['files'] = $files;


		$post['0'] = $group;

		$this->formData = $post;

		var_dump($post);
	}

}