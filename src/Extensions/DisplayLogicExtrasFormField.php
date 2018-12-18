<?php
namespace WebbuildersGroup\DisplayLogicExtras\Extensions;

use UncleCheese\DisplayLogic\Extensions\DisplayLogic;
use UncleCheese\DisplayLogic\Criteria;
use SilverStripe\View\Requirements;

class DisplayLogicExtrasFormField extends DisplayLogic {
    protected $display_logic_classes;
    
    
    /**
     * If the criteria evaluate true, the field should display
     * @param  string $master The name of the master field
     * @return UncleCheese\DisplayLogic\Criteria
     */
    public function displayIf($master) {
        $this->display_logic_classes='display-logic display-logic-hidden display-logic-display';
        
        return parent::displayIf($master);
    }
    
    /**
     * If the criteria evaluate true, the field should hide.
     * The field will be hidden with CSS on page load, before the script loads.
     * @param  string $master The name of the master field
     * @return UncleCheese\DisplayLogic\Criteria
     */
    public function hideIf($master) {
        $this->display_logic_classes='display-logic display-logic-hide';
        
        return parent::hideIf($master);
    }
    
    /**
     * Clears the display logic criteria
     * @return SilverStripe\Forms\FormField
     */
    public function clearDisplayLogicCriteria() {
        //Reset the display logic criteria to an empty array
        $this->displayLogicCriteria=array();
        
        
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
    public function DisplayLogic() {
        $parentResult=parent::DisplayLogic();
        
        if($this->displayLogicCriteria) {
            Requirements::javascript('unclecheese/display-logic: client/dist/js/bundle.js');
            Requirements::javascript('webbuilders-group/silverstripe-display-logic-extras: client/dist/DisplayLogicExtras.js');
        }
        
        return $parentResult;
    }
    
    /**
     * Prefixes all child criteria with the given prefix
     * @param string $prefix Prefix to append to the criteria
     */
    public function prefixDisplayLogicCriteria($prefix) {
        $newCriteria=array();
        foreach($this->displayLogicCriteria as $id=>$child) {
            $newCriteria[$prefix.'['.$id.']']=$child;
            
            if($child instanceof Criteria) {
                $child->prefixCriteria($prefix);
            }
        }
        
        $this->displayLogicCriteria=$newCriteria;
    }
    
    /**
     * Gets the css classes needed for display logic
     * @return string
     */
    public function getDisplayLogicClasses() {
        return $this->display_logic_classes;
    }
}
?>