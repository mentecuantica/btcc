<?php

namespace Btcc\Services\Users;

use Btcc\Http\Requests\AddNewUserRequest;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\User;
use League\Flysystem\Exception;

class UserService {

    protected $passwordLength = 8;

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



    /**
     * @param \Btcc\Http\Requests\AddNewUserRequest $request
     *
     * @return \Btcc\Models\User
     * @throws \Exception
     */
    protected function createUserModel(AddNewUserRequest $request)
    {
        $passwordPlain = $this->generateRandomPassword();


        $name = $request->first_name;

        $newUser = User::create(array_merge($request->only([
            'email',
            'first_name',
            'last_name',
            'package_id',
        ]), [
            'password' => bcrypt($passwordPlain),
            'name'     => $name,
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



/*
        if ($binaryId == (-1 || FALSE)) {
            throw new \Exception(sprintf('Binary: parent %d, position %s occupied',$parentId,$position));
        }*/
    }

    /**
     * @param \Btcc\Http\Requests\AddNewUserRequest $request
     * @param                                       $newUser
     */
    protected function addProfileToUser(AddNewUserRequest $request, $newUser)
    {
        $result = $newUser->profile()->create($request->only([
            'country_code',

        ]));
        if ($result == FALSE) {
            throw new \Exception(sprintf('Cannot add profile to user'));
        }
    }

    /**
     * @return string
     */
    protected function generateRandomPassword()
    {
        return str_random($this->passwordLength);

    }
}