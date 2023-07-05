<?php
namespace WebbuildersGroup\DisplayLogicExtras;

use UncleCheese\DisplayLogic\Criterion;

class DisplayLogicExtrasCriterion extends Criterion
{
    /**
     * Applies a prefix to the display logic dispatcher name, this will act like the prefix is an array
     * @param string $prefix Prefix to apply
     */
    public function prefixDispatcher($prefix)
    {
        $this->dispatcher = $prefix . '[' . $this->dispatcher . ']';
    }
}
