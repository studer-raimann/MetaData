<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use ilPropertyFormGUI;
use srag\CustomInputGUIs\MetaData\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\OrgUnitField;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use srmdGUI;

/**
 * Class InputfieldOrgUnit
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class InputfieldOrgUnit extends BaseInputfield
{

    /**
     * @inheritDoc
     */
    public function __construct(OrgUnitField $field, string $lang = "")
    {
        parent::__construct($field, $lang);
    }


    /**
     * @inheritDoc
     */
    public function getILIASFormInputs(Record $record) : array
    {
        if ($this->field->options()->isOnlyDisplay()) {
            $input = new ilNonEditableValueGUI($this->field->getLabel($this->lang));
            $input->setValue(self::dic()->objDataCache()->lookupTitle(self::dic()->objDataCache()->lookupObjId($record->getValue())));
        } else {
            $input = new MultiSelectSearchNewInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            $input->setRequired($this->field->options()->isRequired());
            $input->setOptions(array_reduce(self::dic()->tree()->getSubTree(self::dic()->tree()->getNodeData($this->field->options()->getOrgUnitParentRefId())), function (array $org_units, array $item) : array {
                $org_units[$item["child"]] = $item["title"];

                return $org_units;
            }, []));
            if ($record->getValue()) {
                $input->setValue([$record->getValue()]);
            }
            //$cmdClass self::dic()->ctrl()->getCmdClass(); // is broken with namespace (ilCtrl), use with filter_input the original raw value
            $cmdClass = filter_input(INPUT_GET, "cmdClass");
            self::dic()->ctrl()->setParameterByClass($cmdClass, "field_id", $this->field->getId());
            $input->setAjaxLink(self::dic()->ctrl()->getLinkTargetByClass($cmdClass, srmdGUI::CMD_ORG_UNITS_AUTOCOMPLETE, "", true, false));
            $input->setLimitCount(1);
            self::dic()->ctrl()->clearParameterByClass($cmdClass, "field_id");
        }

        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }

        return [$input];
    }


    /**
     * @inheritDoc
     */
    public function getRecordValue(Record $record, ilPropertyFormGUI $form)
    {
        return $form->getInput($this->getPostVar($record));
    }
}
