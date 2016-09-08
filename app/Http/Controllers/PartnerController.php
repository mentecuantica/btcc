<?php

namespace Btcc\Http\Controllers;

use Btcc\Events\UserRegisteredPartner;
use Btcc\Http\Requests\AddNewUserRequest;
use Btcc\Jobs\PayForNewUserPackage;
use Btcc\Jobs\SendInviteEmail;
use Btcc\Models\Tree\TreeBinary;
use Btcc\Models\User;
use Faker\Generator;
use JavaScript;

class PartnerController extends Controller {

    /**
     * @todo Show highlight free items
     * @todo
     * Show the form for creating a new resource.
     *
     * @param Generator $faker
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = user();

        //$this->authorize('addPartner');

/*        if (policy($user)->addPartner($user)) {
            \Debugbar::addMessage('policy helper - can','error');
        }*/


        if ($user->cannot('add-partner')) {
            \Debugbar::addMessage('User can user can','error');
            \Flash::warning('You have no funds to add user');
           // return redirect('')->
        };

        $tree = $this->treeRepo->binaryChildren($user->id, 10)->joinUsers()->get();

        $parent = TreeBinary::extractParentRowJson($tree, $user->id);
         $jsonNodes = TreeBinary::generateJson($tree, $user->id);

        JavaScript::put([
            'childrenNodes' => $jsonNodes,
            'parent'        => $parent,
            'user'          => $user,
            'tree'          => 'binary'
        ]);

        //dd($userId,$jsonNodes,$parent);

        $newUser = $this->createFakerFilledUser();
        return view('partner.create', ['newUser'=>$newUser]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**@var User $user * */

        // get an nested collection of LinearTree with User inside
        $partnersCount = user()->linear->countPartners();

        $partners = [] ; // user()->linear->getPartners();

        
       // \Debugbar::addMessage('Sending eeeeeeee job');
      //  $job = (new SendInviteEmail())->delay(60*2);
     //   $this->dispatch($job);




        return view('partner.index', compact('partnersCount','partners'));

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
        ], ['error' => 'Not enough money'], ['packages' => \Subscription::getPackagesIds()]);

        $newUser = $this->userService->addNewUser($request);

        if (!($newUser instanceof User)) {
            \Flash::error('Partner successfully added!' . $newUser);

            return redirect()->back();

        }

        event(new UserRegisteredPartner($newUser));

        $this->dispatchNow(new PayForNewUserPackage($newUser));


        \Flash::warning(sprintf('Partner %s successfully to plan %s added! Remember his password <b>%s</b>', $newUser->email, $newUser->getPackageAttribute()->name, $newUser->passwordPlain));

        return redirect(route('partner.index'));

    }




    /**
     * @param \Faker\Generator $faker
     *
     * @return \Btcc\Models\User
     */
    protected function createFakerFilledUser()
    {

        $faker = \Faker\Factory::create();


        $newUser = new User();
        $newUser->first_name = $faker->firstName;
        $newUser->last_name = $faker->lastName;
        $newUser->email = $faker->email;

        return $newUser;
}

}
