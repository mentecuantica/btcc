<?php

namespace Btcc\Services\Users;

use Btcc\Events\ProfileWasUpdated;
use Btcc\Http\Requests\Request;
use Btcc\Http\Requests\AddNewUserRequest;
use Btcc\Models\Profile;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\Tree\TreeLinear;
use Btcc\Models\User;

class UserService {

    /**
     * Creates new User, links with LinearTree, Profile
     *
     * @param \Btcc\Http\Requests\AddNewUserRequest $request
     *
     * @return bool|\Btcc\Models\User
     * @throws \Exception
     */
    public function addNewUser(AddNewUserRequest $request)
    {

        \DB::beginTransaction();
        try {

            $newUser = $this->createUserModel($request);

            $this->addLinearTree($newUser);

            $this->addProfileToUser($request, $newUser);

            $this->addBinaryRelation($request, $newUser);

            \DB::commit();

            return $newUser;
        }
        catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Creating user failed ' . $e->getMessage());
            throw $e;

        }

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

        $user = [
            'email'    => 'test@repo.ru',
            'password' => '123456'
        ];
        $package_id = \Btcc\Models\Package::all('id')->random()->id;

        $profile = [
            'phone'      => '9222222222',
            'package_id' => $package_id,
        ];

        $binary = [
            'parent_id' => 4,
            'position'  => 'R'
        ];

        static::addNewUser(1, $user, $profile, $binary);
    }

    /**
     * @param \Btcc\Http\Requests\AddNewUserRequest $request
     *
     * @return User
     */
    protected function createUserModel(AddNewUserRequest $request)
    {
        $passwordPlain = \Illuminate\Support\Str::random(8);
        $name = $request->first_name;
        $newUser = User::create(array_merge($request->only([
            'email',
            'first_name',
            'last_name'
        ]), [
            'password' => bcrypt($passwordPlain),
            'name'     => $name
        ]));

        if ($newUser == FALSE) {
            throw new \Exception(sprintf('Cannot create user'));
        }

        $newUser->passwordPlain = $passwordPlain;




        return $newUser;
    }

    /**
     * @param $newUser
     *
     * @return mixed
     */
    protected function addLinearTree(User $newUser)
    {
        $parentId = \Auth::user()->id;
        $newUser->linear->parent_id = $parentId;
        $status = $newUser->linear->save();

        if ($status == FALSE) {
            throw new \Exception(sprintf('Cannot save linear: parent %d, user %d occupied',$parentId,$newUser->id));
        }

        return $status;
    }

    /**
     * @param \Btcc\Http\Requests\AddNewUserRequest $request
     * @param                                       $newUser
     *
     * @throws \Exception
     */
    protected function addBinaryRelation(AddNewUserRequest $request, User $newUser)
    {
        $parentId = $request->get('binary-parent-id');
        $position = $request->get('binary-position');
        $binaryId = TreeBinary::addToParent($parentId, $position, $newUser->id);

        if ($binaryId == (-1 || FALSE)) {
            throw new \Exception(sprintf('Binary: parent %d, position %s occupied',$parentId,$position));
        }
    }

    /**
     * @param \Btcc\Http\Requests\AddNewUserRequest $request
     * @param                                       $newUser
     */
    protected function addProfileToUser(AddNewUserRequest $request, $newUser)
    {
        $result = $newUser->profile()->create($request->only([
            'country_code',
            'package_id'
        ]));
        if ($result == FALSE) {
            throw new \Exception(sprintf('Cannot add profile to user'));
        }
    }
}