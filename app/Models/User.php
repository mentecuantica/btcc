<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/1/2016
 * Time: 3:35 PM
 * Filename: User.php
 */

namespace Btcc\Models;
use Baum\Extensions\Eloquent\Collection;
use Btcc\Models\Transaction\UserTransaction;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\Tree\TreeLinear;
use Btcc\Models\Wallet;
use Btcc\Services\BinaryTreeTrait;
use Btcc\Services\PackageService;
use Btcc\Services\Subscriptions\Package;
use Btcc\Traits\UserWithTrees;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * Class User
 *
 * @package Btcc\Models
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $permissions
 * @property string $last_login
 * @property string                                                                       $first_name
 * @property string                                                                       $last_name
 * @property \Carbon\Carbon                                                               $created_at
 * @property \Carbon\Carbon                                                               $updated_at
 * @property-read \Btcc\Models\Tree\TreeBinary                                            $binary
 * @property-read \Btcc\Models\Tree\TreeLinear                                            $linear
 * @property-read \Btcc\Models\Profile                                                    $profile
 * @property-read \Btcc\Models\Wallet                                                     $wallet
 * @property-read \Illuminate\Database\Eloquent\Collection|\Btcc\Models\Invite[]          $invitesIssued
 * @property-read \Illuminate\Database\Eloquent\Collection|\Btcc\Models\UserTransaction[] $transactionsSent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Btcc\Models\UserTransaction[] $transactionsRecieved
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User wherePermissions($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereLastLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @property string $role
 * @property string $package_id
 * @property string $remember_token
 * @property-read \Illuminate\Database\Eloquent\Collection|\Btcc\Models\SupportTicket[] $supportTickets
 * @property-read \Illuminate\Database\Eloquent\Collection|\Btcc\Models\Transaction\UserTransaction[] $transactions
 * @property-read mixed $total_sum
 * @property-read mixed $package
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User wherePackageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\User whereRememberToken($value)
 */
class User  extends Authenticatable {

 use UserWithTrees;




   

    /**
     * New inline validation from
     * use Watson\Validating\ValidatingTrait;
     *
     * @var array
     */
    protected $rules =[
        'email'           => 'required|email|max:255|unique:users',
        'password'    => 'required|max:64',
       // 'first_name' => 'required',
       // 'last_name' => 'required',
    ];



    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub


        static::created(function(User $newUser) {
            $newUser->createRelatedLinearRelation();
        });
    }

    /**
     * When new user is created - add LINEAR RELATION (NESTED SET OBJECT)
     *
     */
    public function createRelatedLinearRelation()
    {
        $linearRelated = TreeLinear::create(['user_id'=>$this->id]);
    }




    /**
     * Returns a nested collection
     *
     * @return Collection
     */
    public function getLinearTreeCollection()
    {
        return $this->linear->descendantsAndSelf()->with('user')->get()->toHierarchy();

    }

    public function setPasswordPlain($value)
    {
        $this->passwordPlain = $value;
        $this->password = bcrypt($value);
    }
    

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function linear()
    {

        return $this->hasOne(TreeLinear::class,'user_id');
    }



    protected $fillable = [
        'email',
        'name',
        'username',
        'password',
        'last_name',
        'first_name',
        'passwordPlain',
        'package_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden
        = [
            'password',
        ];

    protected $loginNames = ['email','username'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function wallet()
    {

        return $this->hasOne(Wallet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(UserTransaction::class, 'user_id');
    }

    public function getTotalSumAttribute() {
        return static::join('users_transactions','user_id','=','users.id')->where(['users.id'=>$this->id])->groupBy('user_id')->sum('amount');

    }

    /**
     * @return Package
     */
    public function getPackageAttribute()
    {
        $package = app(PackageService::class)->find($this->package_id);


        return $package;

    }

    public function transactionsSent()
    {

        return $this->hasMany(UserTransaction::class, 'sender_id');
    }

    public function transactionsRecieved()
    {
        return $this->hasMany(UserTransaction::class, 'reciever_id');
    }






  


    /**
     * @todo Wrong relation
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    /* public function binary()
     {
         return $this->hasOne(TreeBinary::class,'parent_id','id');
     }*/

    /* public function transactionsTotalSum()
   {
       return $this->hasMany(UserTransaction::class, 'user_id')->selectRaw('sum(amount) as aggregate')->groupBy('user_id');
   }*/

    /*public function getTransactionsTotalSumAttribute()
    {
        if (! array_key_exists('transactionsTotalSum',$this->relations)) {
            $this->load('transactionsTotalSum');
        }

        $related = $this->getRelation('transactionsTotalSum');

        return ($related) ? (int) $related->aggregate : 0;

    }*/

}