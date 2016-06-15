<?php

namespace Btcc\Repositories;

use Btcc\Events\ProfileWasUpdated;
use Btcc\Http\Requests\Request;
use Btcc\Models\Profile;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\Tree\TreeLinear;
use Btcc\Models\User;
use Illuminate\Support\Facades\Schema;

class UserRepository
{
    public function __construct(User $user = NULL)
    {
        $this->model = $user;
    }

    /**
     * Returns all users
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Returns all paginated $MODEL_NAME_PLURAL$
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated($paginate)
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($paginate);
    }

    /**
     * Search Transaction
     *
     * @param string $input
     *
     * @return User
     */
    public function search($input, $paginate)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        $columns = Schema::getColumnListing('users');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input.'%');
        };

        return $query->paginate($paginate);
    }

    /**
     * Stores User into database
     *
     * @param array $input
     *
     * @return User
     */
    public function create($input)
    {
        return $this->model->create($input);
    }

    /**
     * Find User by given id
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|User
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Destroy User
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|User
     */
    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Updates User in the database
     *
     * @param int $id
     * @param array $inputs
     *
     * @return User
     */
    public function update($id, $inputs)
    {
        $user = $this->model->find($id);
        $user->fill($inputs);
        $user->save();

        return $user;
    }

    /**
     *
     * Creates new User, links with LinearTree, Profile
     *
     * @todo Hash password
     *
     */
    public static function createNewUserBundle(int $currentUserId,array $userInput,array $profileInput, array $binaryInput)
    {
        /**
         * 1. Create user
         * 1.1 Create linear connection
         * 2. Create profile
         *
         *
         */

        \DB::beginTransaction();
         try {

            $result = false;
            $newUser = new User();
            $newUser->email = $userInput['email'];
            $newUser->password = $userInput['password'];
            $newUser->first_name = $userInput['first_name'];
            $newUser->last_name = $userInput['last_name'];


       

            if ($newUser->isInvalid()) {
                \Request::session()->registerBag($newUser->getErrors());


                \Log::info('User data invalid: ',['userErrors'=>$newUser->getErrors()->toJson()]);
                return FALSE;
            }

            $result =  $newUser->save();

             if (false==$result) {
                 throw new \Exception('User model havent been saved');
             }

            \Log::info('New user save status: ', compact('result'));

            // Update the nested set relation
             $linearRelated = TreeLinear::create(['user_id'=>$newUser->id]);
            $linearRelated->parent_id = $currentUserId;
            $result = $linearRelated->save();


             if (false==$result) {
                 throw new \Exception('Linear User link havent been saved');
             }
            \Log::info('Linear nested set update: ', compact('result'));

            $newUserProfile = new Profile();
            $newUserProfile->country_id = $profileInput['country_id'];
            $newUserProfile->package_id = $profileInput['package_id'];


            $result = $newUser->profile()->save($newUserProfile);
             if (false==$result) {
                 throw new \Exception('User Profile havent been saved');
             }
            
            \Log::info('Linear nested set update: ', compact('result'));
            
            $result = TreeBinary::addToParent($binaryInput['parent_id'],$binaryInput['position'],$newUser->id);

             \Log::info('Added to binary tree: ', compact('result'));
            
             \DB::commit();

             return $newUser;
         }
         catch (\Exception $e) {
             \DB::rollBack();
             \Log::error('Creating user failed');
             return false;
         }

        return false;



    }

    public static function testCreateTreeUserBundle()
    {
        $email = 'test@repo.ru';

        $oldUser = \Btcc\Models\User::with('linear')->where('email', '=', $email)->first();

        if ($oldUser) {
            /**@var User $oldUser * */
            $oldUser->linear->delete();
            $oldUser->profile->delete();
            $oldUser->delete();
        }


        $user = ['email'    => 'test@repo.ru',
                 'password' => '123456'
        ];
        $package_id = \Btcc\Models\Package::all('id')->random()->id;

        $profile = [
            'phone'=>'9222222222',
            'package_id' => $package_id,
        ];

        $binary = [
            'parent_id'=>4,
            'position'=>'R'
        ];

        static::createNewUserBundle(1, $user, $profile, $binary);
    }
}