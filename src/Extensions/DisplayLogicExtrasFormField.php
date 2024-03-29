<?php
namespace WebbuildersGroup\DisplayLogicExtras\Extensions;

use UncleCheese\DisplayLogic\Extensions\DisplayLogic;
use UncleCheese\DisplayLogic\Criteria;
use SilverStripe\View\Requirements;

class DisplayLogicExtrasFormField extends DisplayLogic
{
    protected $display_logic_classes;
    protected $nestedLogic = [];


    /**
     * If the criteria evaluate true, the field should display
     * @param  string $dispatcher The name of the dispatcher field
     * @return UncleCheese\DisplayLogic\Criteria
     */
    public function displayIf($dispatcher): Criteria
    {
        $this->display_logic_classes = 'display-logic display-logic-hidden display-logic-display';

        return parent::displayIf($dispatcher);
    }

    /**
     * If the criteria evaluate true, the field should hide.
     * The field will be hidden with CSS on page load, before the script loads.
     * @param  string $dispatcher The name of the dispatcher field
     * @return UncleCheese\DisplayLogic\Criteria
     */
    public function hideIf($dispatcher): Criteria
    {
        $this->display_logic_classes = 'display-logic display-logic-hide';

        return parent::hideIf($dispatcher);
    }

    /**
     * Clears the display logic criteria
     * @return SilverStripe\Forms\FormField
     */
    public function clearDisplayLogicCriteria()
    {
        //Reset the display logic criteria to an empty array
        $this->displayLogicCriteria = [];


        //Remove display logic classes
        $this->owner->removeExtraClass('display-logic');
        $this->owner->removeExtraClass('display-logic-hide');
        $this->owner->removeExtraClass('display-logic-hidden');
        $this->owner->removeExtraClass('display-logic-display');


        //Return the owner
        return $this->owner;
    }

    /**
     * Loads the dependencies and renders the JavaScript-readable logic to the form HTML
     * @return string
     */
    public function DisplayLogic(): ?string
    {
        $parentResult = parent::DisplayLogic();

        if ($this->getDisplayLogicCriteria()) {
            Requirements::javascript('unclecheese/display-logic: client/dist/js/bundle.js');
            Requirements::javascript('webbuilders-group/silverstripe-display-logic-extras: client/dist/DisplayLogicExtras.js');
        }

        return $parentResult;
    }

    /**
     * @param Criteria $criteria
     * @return Criteria
     */
    public function setDisplayLogicCriteria(Criteria $criteria): Criteria
    {
        $this->owner->setField('displayLogic', $criteria);
        return $criteria;
    }

    /**
     * @param Criteria $criteria
     * @return Criteria|null
     */
    public function getDisplayLogicCriteria(): ?Criteria
    {
        return $this->owner->getField('displayLogic');
    }

    /**
     * Gets the css classes needed for display logic
     * @return string
     */
    public function getDisplayLogicClasses()
    {
        return $this->display_logic_classes;
    }
}
