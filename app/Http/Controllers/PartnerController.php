<?php

namespace Btcc\Http\Controllers;

use Btcc\Events\Event;
use Btcc\Events\UserRegisteredPartner;
use Btcc\Http\Requests\AddNewUserRequest;
use Btcc\Jobs\PayForNewUserPackage;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
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

        /**@var User $user * */

        // get an nested collection of LinearTree with User inside
        $binaryPartnersCount = user()->getTreeBinary()->countPartners();

        $partners = [];

        return view('partner.index', compact('binaryPartnersCount', 'partners'));

    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = user();

        $tree = $this->treeRepo->binaryChildren($user->id, 10)->joinUsers()->get();

        list($parent, $jsonNodes) = TreeBinary::generateJson($tree, $user->id);

        JavaScript::put([
            'childrenNodes' => $jsonNodes,
            'parent'        => $parent,
            'user'          => $user,
            'tree'          => 'binary'
        ]);

        //dd($userId,$jsonNodes,$parent);
        return view('partner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Btcc\Http\Requests\AddNewUserRequest|\Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddNewUserRequest $request)
    {

        $this->validate($request, [
            'package_id' => 'enoughMoney'
        ], ['error' => 'Not enough money'], ['packages' => $this->packageService]);

        $newUser = $this->userService->addNewUser($request);

        if (!($newUser instanceof User)) {
            \Log::critical('Cannot create user threesine');
            \Flash::error('Partner successfully added!' . $newUser);

            return redirect()->back();

        }

        event(new UserRegisteredPartner($newUser));

        \Log::critical('Before Job', [$newUser]);
        $this->dispatchNow(new PayForNewUserPackage($newUser));

        \Log::critical('After dispath Job', [$newUser]);

        \Flash::warning(sprintf('Partner %s successfully to plan %s added! Remember his password <b>%s</b>', $newUser->email, $newUser->getPackageAttribute()->name, $newUser->passwordPlain));

        return redirect(route('partner.index'));

    }

    //return redirect(route('partner.create'))->back()->withInput($request->except('password'));

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

}
