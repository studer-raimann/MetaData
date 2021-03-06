<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\FloatField;
use SRAG\ILIAS\Plugins\MetaData\Field\FloatFieldOptions;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldFloat
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldFloat extends BaseInputfield
{

    public function __construct(FloatField $field, $lang = '')
    {
        parent::__construct($field, $lang);
    }


    public function getILIASFormInputs(Record $record)
    {
        $options = $this->field->options();
        if ($options->isOnlyDisplay()) {
            $input = new ilNonEditableValueGUI($this->field->getLabel($this->lang));
        } else {
            $input = new \ilNumberInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            $input->setRequired($options->isRequired());
            $decimals = $options->getNDecimals() ? $options->getNDecimals() : 2;
            $input->setDecimals($decimals);
            if ($options->getMinValue()) {
                $input->setMinValue($options->getMinValue());
            }
            if ($options->getMaxValue()) {
                $input->setMaxValue($options->getMaxValue());
            }
        }
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }
        $input->setValue($record->getValue());

        return array($input);
    }


    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        return $form->getInput($this->getPostVar($record));
    }
}