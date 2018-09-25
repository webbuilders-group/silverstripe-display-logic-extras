<?php

use UncleCheese\DisplayLogic\Criterion;

class DisplayLogicExtrasCriterion extends Criterion {
    /**
     * Applies a prefix to the display logic master name, this will act like the prefix is an array
     * @param {string} $prefix Prefix to apply
     */
    public function prefixMaster($prefix) {
        $this->master=$prefix.'['.$this->master.']';
    }
}
?>