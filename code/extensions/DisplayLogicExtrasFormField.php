<?php
class DisplayLogicExtrasFormField extends DisplayLogicFormField {
    protected $display_logic_classes;
    
    
    /**
     * If the criteria evaluate true, the field should display
     * @param  string $master The name of the master field
     * @return DisplayLogicCriteria
     */
    public function displayIf($master) {
        $this->display_logic_classes='display-logic display-logic-hidden display-logic-display';
        
        return parent::displayIf($master);
    }
    
    /**
     * If the criteria evaluate true, the field should hide.
     * The field will be hidden with CSS on page load, before the script loads.
     * @param  string $master The name of the master field
     * @return DisplayLogicCriteria
     */
    public function hideIf($master) {
        $this->display_logic_classes='display-logic display-logic-hide';
        
        return parent::hideIf($master);
    }
    
    /**
     * Gets the fields display logic criteria
     * @return {DisplayLogicCriteria}
     */
    public function getDisplayLogicCriteria() {
        return $this->displayLogicCriteria;
    }
    
    /**
     * Clears the display logic criteria
     * @return {FormField}
     */
    public function clearDisplayLogicCriteria() {
        //Reset the display logic criteria to null
        $this->displayLogicCriteria=null;
        
        
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
     * @return {string}
     */
    public function DisplayLogic() {
        $parentResult=parent::DisplayLogic();
        
        if($this->displayLogicCriteria) {
            Requirements::javascript(SS_DLE_BASE.'/javascript/DisplayLogicExtras.js');
        }
        
        return $parentResult;
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