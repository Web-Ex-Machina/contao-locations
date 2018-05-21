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

use WEM\Location\Model\Map;
use WEM\Location\Model\Location;

/**
 * Provide backend functions to Locations Extension
 */
class Callback extends Backend
{
	/**
	 * Return a form to choose a CSV file and import it
	 *
	 * @return string
	 */
	public function importLocations()
	{
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
			
				foreach($sheetData as $intRow => $arrRow){
					/*$arrLocations[] = [
						 'nom' => $arrRow["B"]
						,'secteur' => $arrSecteurs[$arrRow["C"]]
						,'structure' => $strStructure
						,'adresse' => $arrRow["H"]." ".$arrRow["I"]
						,'postal' => $arrRow["J"]
						,'ville' => $arrRow["K"]
						,'pays' => $strPays
						,'telephone' => $arrRow["N"]
						,'fax' => $arrRow["O"]
						,'email' => $arrRow["P"]
						,'gender' => $arrRow["D"]
						,'firstname' => $arrRow["F"]
						,'lastname' => $arrRow["G"]
						,'website' => $arrRow["R"]
						,'region' => $arrRow["T"]
					];*/
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

<div class="tl_tbox">
  <h3>'.$GLOBALS['TL_LANG']['MSC']['source'][0].'</h3>'.$objUploader->generateMarkup().(isset($GLOBALS['TL_LANG']['MSC']['source'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_wem_location']['import'][0]).'">
</div>

</div>
</form>';
	}
}