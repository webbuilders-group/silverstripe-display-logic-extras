<?php
namespace WebbuildersGroup\DisplayLogicExtras;

use UncleCheese\DisplayLogic\Criteria;

class DisplayLogicExtrasCriteria extends Criteria {
    /**
     * Applies a prefix to the display logic master name as well as all of the children criterion, this will act like the prefix is an array
     * @param {string} $prefix Prefix to apply
     */
    public function prefixMasters($prefix) {
        $this->master=$prefix.'['.$this->master.']';
        
        foreach($this->criteria as $child) {
            if($child instanceof DisplayLogicCriteria) {
                $child->prefixMasters($prefix);
            }else {
                $child->prefixMaster($prefix);
            }
        }
    }
    
    /**
     * Ends the current group
     * @return {DisplayLogicExtrasCriteria|FormField} Returns the parent criteria set or the form field
     */
    public function endGroup() {
        if($this->parent) {
            $this->parent->addCriterion($this);
            
            return $this->parent;
        }
        
        return $this->slave;
    }
}
?>