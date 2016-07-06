<?php

namespace Btcc\Http\Controllers;

use Btcc\Events\Event;
use Btcc\Events\UserRegisteredPartner;
use Btcc\Http\Requests\UserCreateRequest;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\User;
use Btcc\Repositories\UserRepository;
use Illuminate\Http\Request;
use JavaScript;
use Btcc\Http\Requests;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;

class PartnerController extends Controller {
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //todo List transactions

        //$partners = \Auth::user()->subPartners(5);
        $user = \Auth::getUser();

        /**@var User $user * */

        // get an nested collection of LinearTree with User inside
        $binaryPartnersCount = $user->getTreeBinary()->countPartners();

        $partners = [];

        return view('partner.index', compact('binaryPartnersCount','partners'));

    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $userId = \Auth::getUser()->id;

        list($parent, $jsonNodes) = TreeBinary::generateJsonBinary($userId);

        JavaScript::put(['childrenNodes'=>$jsonNodes,'parent'=>$parent,'user'=>user(),'tree'=>'binary']);

        //dd($userId,$jsonNodes,$parent);
        return view('partner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request){
        $passwordPlain = \Illuminate\Support\Str::random(8);


       // $allValues = $userRequest->all();

        $user = [
            'email'      => $request->email,
            'password'   => bcrypt($passwordPlain),
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
        ];

        $profile = [
            'country_code' => $request->country_code,
            'package_id' => $request->package_id
        ];
        $binary = ['position' => $request->input('binary-position'),'parent_id' => $request->input('binary-parent-id')];




        //dd($request);

        //d($user,$profile,$binary);

        $newUser = UserRepository::createNewUserBundle(user()->id, $user, $profile, $binary);

        if ($newUser instanceof User) {
            event(new UserRegisteredPartner($newUser,$passwordPlain));

            
            //$activationResult = \Auth::activate($newUser);


            \Flash::success('Partner successfully added! Password ' . $passwordPlain);
           // \Flash::warning('Activation ' . $activationResult);
            return redirect(route('partner.index'));
        }


        return redirect()->back()->withInput($request->except('password'));
        //return redirect(route('partner.create'))->back()->withInput($request->except('password'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $partner)
    {

        return $partner;
        // return view('partner.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('transaction.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
