<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilCustomInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\LocationField;
use SRAG\ILIAS\Plugins\MetaData\Field\TextField;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\LocationRecordValue;

/**
 * Class InputfieldGoogleMaps
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldGoogleMaps extends BaseInputfield
{

    public function __construct(LocationField $field, $lang = '')
    {
        parent::__construct($field, $lang);
    }


    public function getILIASFormInputs(Record $record)
    {
        $options = $this->field->options();
        if ($options->isOnlyDisplay()) {
            $input = new ilCustomInputGUI($this->field->getLabel($this->lang));
            $input->setHtml(self::output()->getHTML(self::dic()->ui()->factory()->listing()->descriptive($record->getValue())));
        } else {
            $input = new \ilLocationInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            $input->setRequired($options->isRequired());
            $value = $record->getValue();
            $input->setLongitude($value['long']);
            $input->setLatitude($value['lat']);
            $input->setZoom($value['zoom']);
            $input->setAddress($value['address']);
        }
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }

        return array($input);
    }


    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        $value = $form->getInput($this->getPostVar($record));
        $value['lat'] = $value['latitude'];
        $value['long'] = $value['longitude'];

        return $value;
    }
}