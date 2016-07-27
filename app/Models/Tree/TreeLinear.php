<?php

namespace Btcc\Models\Tree;

use Baum\Node;
use Btcc\Models\User;

//use Btcc\Traits\LinearTreeable;

/**
 * TreeLinear
 * @property integer                                                                  $id
 * @property integer                                                                  $user_id
 * @property integer                                                                  $parent_id
 * @property integer                                                                  $lft
 * @property integer                                                                  $rgt
 * @property integer                                                                  $depth
 * @property string                                                                   $comment
 * @property \Carbon\Carbon                                                           $created_at
 * @property \Carbon\Carbon                                                           $updated_at
 * @property-read \Btcc\Models\User                                                   $user
 * @property-read \Btcc\Models\Tree\TreeLinear                                        $parent
 * @property-read \Baum\Extensions\Eloquent\Collection|\Btcc\Models\Tree\TreeLinear[] $children
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Tree\TreeLinear whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 * @mixin \Eloquent
 */
class TreeLinear extends Node implements UserTreeable {
    //use LinearTreeable;

    //use Singleton;
    protected $primaryKey = 'user_id';

    protected $userId;
    protected $userModel;

    public function initTreeParent(User $user)
    {
        $this->userModel = $user;
        $this->userId = $user->id;
    }

    /**
     * Table name.
     * @var string
     */
    protected $table = 'tree_linear';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');   // TODO: Implement user() method.
    }

    public function getParentUser()
    {
        return $this->parent()->get();
    }


    /**
     * @return bool
     */
    public function isTopUser()
    {
        return $this->isRoot();
    }

    /**
     * @return bool
     */
    public function hasPartners()
    {
        return !$this->isLeaf();
    }

    public function getPartners()
    {
        return $this->descendants()->get();
    }

    /**
     * Returns those who on level $depthLimit below this user
     * @return mixed
     */
    public function getPartnersLimit($depthLimit = 5)
    {
        return $this->descendants()->limitDepth($depthLimit)->get();
    }

    /**
     * Returns those who on level 1 below this user
     * @return mixed
     */
    public function getFirstPartners()
    {
        return $this->children()->get();
    }

    public function getParents()
    {
        return $this->ancestors()->get();
    }

    /**
     * @return int
     */
    public function countPartners()
    {
        return $this->descendants()->count();
    }

    /**
     * @return int
     */
    public function countFirstPartners()
    {
        return $this->children->count();
    }

    /**
     * @return int
     */
    public function countParents()
    {
        return $this->ancestors()->count();
    }


    // /**
    // * With Baum, all NestedSet-related fields are guarded from mass-assignment
    // * by default.
    // *
    // * @var array
    // */
    protected $guarded
        = [
          
            'parent_id',
            'lft',
            'rgt',
            'depth'
        ];



    // /**
    //  * Column name which stores reference to parent's node.
    //  *
    //  * @var string
    //  */
    // protected $parentColumn = 'parent_id';

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
