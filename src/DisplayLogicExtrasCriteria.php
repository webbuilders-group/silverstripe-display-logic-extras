<?php
namespace WebbuildersGroup\DisplayLogicExtras;

use UncleCheese\DisplayLogic\Criteria;

class DisplayLogicExtrasCriteria extends Criteria
{
    /**
     * Applies a prefix to the display logic dispatcher name as well as all of the children criterion, this will act like the prefix is an array
     * @param string $prefix Prefix to apply
     * @return DisplayLogicExtrasCriteria
     */
    public function prefixDispatchers($prefix)
    {
        $this->dispatcher = $prefix . '[' . $this->dispatcher . ']';

        foreach ($this->criteria as $child) {
            if ($child instanceof Criteria) {
                $child->prefixDispatchers($prefix);
            } else {
                $child->prefixDispatcher($prefix);
            }
        }

        return $this;
    }
}
