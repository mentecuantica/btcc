<?php

namespace Btcc\Http\Controllers;

use Btcc\Events\Event;
use Btcc\Events\UserRegisteredPartner;
use Btcc\Http\Requests\AddNewUserRequest;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\User;
use Btcc\Repositories\UserRepository;
use Btcc\Services\Users\UserService;
use Illuminate\Http\Request;
use JavaScript;
use Btcc\Http\Requests;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;

class PartnerController extends Controller {

    protected $userService;

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     * param [ mixed $args [, $... ]]
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
    public function store(AddNewUserRequest $request){
      

        $user = $this->userService->addNewUser($request);


        if ($user instanceof User) {
            event(new UserRegisteredPartner($user));
            \Flash::success('Partner successfully added!');

            \Flash::warning(sprintf('Partner %s successfully added! Remember his password <b>%s</b>',$user->email,$user->passwordPlain));

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
