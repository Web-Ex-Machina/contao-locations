<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Backend;

use Contao\Backend;

use Haste\Http\Response\JsonResponse;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

use WEM\Location\Controller\Util;
use WEM\Location\Controller\Provider\GoogleMaps;
use WEM\Location\Controller\Provider\Nominatim;
use WEM\Location\Model\Map;
use WEM\Location\Model\Location;

/**
 * Provide backend functions to Locations Extension
 */
class Callback extends Backend
{
	/**
	 * Geocode a given location
	 * @param  \DataContainer $objDc [Datacontainer to geocode]
	 * @return JSON through AJAX request or Message with redirection
	 */
	public function geocode(\DataContainer $objDc){
		if (\Input::get('key') != 'geocode')
			return '';

		try{
			$objLocation = Location::findByPk($objDc->id);
			$objMap = Map::findByPk($objLocation->pid);

			if(!$objMap->geocodingProvider)
				throw new \Exception($GLOBALS['TL_LANG']['WEM']['LOCATIONS']['ERROR']['missingConfigForGeocoding']);

			switch($objMap->geocodingProvider){
				case 'gmaps':
					$arrCoords = GoogleMaps::geocoder($objLocation, $objMap);
				break;
				case 'nominatim':
					$arrCoords = Nominatim::geocoder($objLocation, $objMap);
				break;
				default:
					throw new \Exception($GLOBALS['TL_LANG']['WEM']['LOCATIONS']['ERROR']['missingConfigForGeocoding']);
			}

			$objLocation->lat = $arrCoords['lat'];
			$objLocation->lng = $arrCoords['lng'];

			if(!$objLocation->save())
				throw new \Exception($GLOBALS['TL_LANG']['WEM']['LOCATIONS']['ERROR']['errorWhenSavingTheLocation']);

			if('ajax' == \Input::get('src'))
				$arrResponse = ["status"=>"success", "response"=>sprintf($GLOBALS['TL_LANG']['WEM']['LOCATIONS']['CONFIRM']['locationSaved'], $objLocation->title), "data"=>$arrCoords];
			else
				\Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['WEM']['LOCATIONS']['CONFIRM']['locationSaved'], $objLocation->title));
			
		}
		catch(\Exception $e){
			if('ajax' == \Input::get('src'))
				$arrResponse = ["status"=>"error", "response"=>$e->getMessage()];
			else
				\Message::addError($e->getMessage());
		}

		if('ajax' == \Input::get('src')){
			$objResponse = new JsonResponse($arrResponse);
			$objResponse->send();
		}
		
		$strRedirect = str_replace(["&key=geocode", "id=".$objLocation->id, "&src=ajax"], ["", "id=".$objMap->id, ""], \Environment::get('request'));
		$this->redirect(ampersand($strRedirect));
	}

	/**
	 * Return a form to choose a CSV file and import it
	 * @return string
	 */
	public function importLocations(){
		if (\Input::get('key') != 'import')
			return '';

		if(!\Input::get('id'))
			return '';

		$objMap = Map::findByPk(\Input::get('id'));

		$this->import('BackendUser', 'User');
		$class = $this->User->uploader;

		// See #4086 and #7046
		if (!class_exists($class) || $class == 'DropZone')
			$class = 'FileUpload';

		/** @var \FileUpload $objUploader */
		$objUploader = new $class();

		$arrExcelPattern = [];
		// Preformat Excel Pattern (key = Excel column, value = DB Column)
		foreach(deserialize($objMap->excelPattern) as $arrColumn)
			$arrExcelPattern[$arrColumn["value"]] = $arrColumn["key"];

		// Import CSS
		if (\Input::post('FORM_SUBMIT') == 'tl_wem_locations_import'){
			$arrUploaded = $objUploader->uploadTo('system/tmp');
			if (empty($arrUploaded)){
				\Message::addError($GLOBALS['TL_LANG']['ERR']['all_fields']);
				$this->reload();
			}

			$time = time();
			$intTotal = 0;
			$intInvalid = 0;			

			foreach ($arrUploaded as $strFile){
				$objFile = new \File($strFile, true);
				$spreadsheet = IOFactory::load(TL_ROOT.'/'.$objFile->path);
				$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
				$arrLocations = array();
			
				foreach($sheetData as $arrRow){
					foreach($arrRow as $strColumn => $strValue){
						// strColumn = Excel Column
						// strValue = Value in the current arrRow, at the column strColumn
						switch($arrExcelPattern[$strColumn]){
							case 'country':
								$arrLocation['country'] = Util::getCountryISOCodeFromFullname($strValue);
							break;
							default:
								$arrLocation[$arrExcelPattern[$strColumn]] = $strValue;
						}
					}

					$arrLocation['continent'] = Util::getCountryContinent($arrLocation['country']);
					$arrLocations[] = $arrLocation;
				}

				$intCreated = 0;
				$intUpdated = 0;
				$intDeleted = 0;
				$arrNewLocations = array();

				foreach($arrLocations as $arrLocation){
					$objLocation = Location::findOneBy('title', $arrLocation['title']);

					// Create if don't exists
					if(!$objLocation){
						$objLocation = new Location();
						$objLocation->pid = $objMap->id;
						$objLocation->published = 1;
						$intCreated++;
					}
					else
						$intUpdated++;

					$objLocation->tstamp = time();

					foreach($arrLocation as $strColumn => $varValue)
						$objLocation->$strColumn = $varValue;

					$objLocation->save();
					$arrNewLocations[] = $objLocation->id;
				}

				$objLocations = Location::findItems(['pid'=>$objMap->id, 'published'=>1]);
				while($objLocations->next()){
					if(!in_array($objLocations->id, $arrNewLocations)){
						$objLocations->delete();
						$intDeleted++;
					}
				}
			}

			\Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['tl_wem_location']['createdConfirmation'], $intCreated));
			\Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_wem_location']['updatedConfirmation'], $intUpdated));
			\Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_wem_location']['deletedConfirmation'], $intDeleted));

			\System::setCookie('BE_PAGE_OFFSET', 0, 0);
			$this->reload();
		}

		// Build an Excel pattern to show
		$arrTh = array();
		$arrTd = array();
		foreach($arrExcelPattern as $strExcelColumn => $strDbColumn){
			$arrTh[] = '<th>'.$strExcelColumn.'</th>';
			$arrTd[] = '<td>'.$GLOBALS['TL_LANG']['tl_wem_location'][$strDbColumn][0].'</td>';
		}

		// Build the country array, to give the correct syntax to users
		$arrCountries = array();
		\System::loadLanguageFile('countries');
		foreach($GLOBALS['TL_LANG']['CNT'] as $strIsoCode => $strName){
			$arrCountries[$strIsoCode]["current"] = $strName;
		}

		\System::loadLanguageFile('countries', 'en');
		foreach($GLOBALS['TL_LANG']['CNT'] as $strIsoCode => $strName){
			$arrCountries[$strIsoCode]["en"] = $strName;
		}

		$strCountries = '';
		foreach($arrCountries as $strIsoCode => $arrNames){
			$strCountries .= '<tr>';
			$strCountries .= '<td>'.$strIsoCode.'</td>';
			$strCountries .= '<td>'.$arrNames["current"].'</td>';
			$strCountries .= '<td>'.$arrNames["en"].'</td>';
			$strCountries .= '</tr>';
		}

		$arrLanguages = \System::getLanguages();

		// Return form
		return '
		<div id="tl_buttons">
		<a href="'.ampersand(str_replace('&key=import', '', \Environment::get('request'))).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
		</div>
		'.\Message::generate().'
		<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_wem_locations_import" class="tl_form" method="post" enctype="multipart/form-data">
		<div class="tl_formbody_edit">
		<input type="hidden" name="FORM_SUBMIT" value="tl_wem_locations_import">
		<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
		<input type="hidden" name="MAX_FILE_SIZE" value="'.\Config::get('maxFileSize').'">

		<fieldset class="tl_tbox nolegend">
			<div class="widget">
			  <h3>'.$GLOBALS['TL_LANG']['tl_wem_location']['source'][0].'</h3>'.$objUploader->generateMarkup().(isset($GLOBALS['TL_LANG']['tl_wem_location']['source'][1]) ? '
			  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_wem_location']['source'][1].'</p>' : '').'
			</div>
		</div>
		</fieldset>

		<div class="tl_formbody_submit">
			<div class="tl_submit_container">
			  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_wem_location']['import'][0]).'">
			</div>
		</div>

		<fieldset class="tl_tbox nolegend">
			<div class="widget">
			<h3>'.$GLOBALS['TL_LANG']['tl_wem_location']['importExampleTitle'].'</h3>
			<table class="wem_locations_import_table">
				<thead>
					<tr>'.implode('', $arrTh).'</tr>
				</thead>
				<tbody>
					<tr>'.implode('', $arrTd).'</tr>
					<tr>'.implode('', $arrTd).'</tr>
				</tbody>
			</table>
			</div>
		</fieldset>

		<fieldset class="tl_tbox nolegend">
			<div class="widget">
			<h3>'.$GLOBALS['TL_LANG']['tl_wem_location']['importListCountriesTitle'].'</h3>
			<table class="wem_locations_import_table">
				<thead>
					<tr><th>ISOCode</th><th>'.$arrLanguages[$GLOBALS['TL_LANGUAGE']].'</th><th>'.$arrLanguages["en"].'</th></tr>
				</thead>
				<tbody>
					'.$strCountries.'
				</tbody>
			</table>
			</div>
		</fieldset>

		</form>';
	}

	/**
	 * Export the Locations of the current map, according to the pattern set
	 */
	public function exportLocations(){
		if (\Input::get('key') != 'export')
			return '';

		if(!\Input::get('id'))
			return '';

		$objMap = Map::findByPk(\Input::get('id'));
		$arrExcelPattern = [];
		// Preformat Excel Pattern (key = DB Column, value = Excel column)
		foreach(deserialize($objMap->excelPattern) as $arrColumn)
			$arrExcelPattern[$arrColumn["key"]] = $arrColumn["value"];

		// Fetch all the locations
		$arrCountries = \System::getCountries();
		$objLocations = Location::findItems(['pid'=>$objMap->id]);

		// Break if no locations
		if(!$objLocations){
			\Message::addError($GLOBALS['TL_LANG']['WEM']['LOCATIONS']['ERROR']['noLocationsFound']);
			$this->reload();
		}

		// Format for the Excel
		$arrRows = array();
		while($objLocations->next()){
			foreach($arrExcelPattern as $strDbColumn => $strExcelColumn){
				switch($strDbColumn){
					case 'country':
						$arrRow[$strExcelColumn] = $arrCountries[$objLocations->$strDbColumn];
					break;
					default:
						$arrRow[$strExcelColumn] = $objLocations->$strDbColumn;
				}
			}
			$arrRows[] = $arrRow;
		}

		// Generate the spreadsheet
		$objSpreadsheet = new Spreadsheet();
		$objSheet = $objSpreadsheet->getActiveSheet();

		// Fill the cells of the Excel
		foreach($arrRows as $intRow => $arrRow)
			foreach($arrRow as $strColumn => $strValue)
				$objSheet->setCellValue($strColumn.($intRow+1), $strValue);

		// And send to browser
		$strFilename = date('Y-m-d_H-i').'_export-locations';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$strFilename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = IOFactory::createWriter($objSpreadsheet, 'Xlsx');
		$writer->save('php://output');
	}
}