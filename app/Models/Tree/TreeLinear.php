<?php

namespace Btcc\Models\Tree;

use Baum\Node;
use Btcc\Models\User;
use Btcc\Traits\LinearTreeable;

/**
 * TreeLinear
 */
class TreeLinear extends Node implements UserTreeable {
    use LinearTreeable;

    /**
     * Table name.
     * @var string
     */
    protected $table = 'tree_linear';

    public function user()
    {
        return $this->belongsTo(User::class, 'parent_id');   // TODO: Implement user() method.
    }





    // /**
    //  * Column name which stores reference to parent's node.
    //  *
    //  * @var string
    //  */
    // protected $parentColumn = 'parent_id';

    // /**
    //  * Column name for the left index.
    //  *
    //  * @var string
    //  */
    // protected $leftColumn = 'lft';

    // /**
    //  * Column name for the right index.
    //  *
    //  * @var string
    //  */
    // protected $rightColumn = 'rgt';

    // /**
    //  * Column name for the depth field.
    //  *
    //  * @var string
    //  */
    // protected $depthColumn = 'depth';

    // /**
    //  * Column to perform the default sorting
    //  *
    //  * @var string
    //  */
    // protected $orderColumn = null;

    // /**
    // * With Baum, all NestedSet-related fields are guarded from mass-assignment
    // * by default.
    // *
    // * @var array
    // */
    // protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');

    //
    // This is to support "scoping" which may allow to have multiple nested
    // set trees in the same database table.
    //
    // You should provide here the column names which should restrict Nested
    // Set queries. f.ex: company_id, etc.
    //

    // /**
    //  * Columns which restrict what we consider our Nested Set list
    //  *
    //  * @var array
    //  */
    // protected $scoped = array();

}
