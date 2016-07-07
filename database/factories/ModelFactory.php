<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Btcc\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'password' => bcrypt('123456'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(Btcc\Models\Package::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'start_balance' => $faker->numberBetween(100,1000),
        'description' => $faker->sentence,
    ];
});




$factory->define(Itabletki\ContentPage::class, function (Faker\Generator $faker) {

    return [
        'slug'=>$faker->slug,
        'title'=> $faker->words(3,true),
        'body'=>$faker->text,
        'created_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),
        'updated_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),

    ];
});

$factory->define(Itabletki\Feedback::class, function (Faker\Generator $faker) {

    return [
        'name'=>$faker->name,
        'subject'=> $faker->words(3, true),
        'body'=>$faker->text,
        'email'=>$faker->email,
        'phone'=>$faker->phoneNumber,
        'advertise'=>$faker->boolean(),
        'contacts'=>$faker->address,
        'client_ip'=>$faker->ipv4,
        'created_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),
        'updated_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),

    ];
});



$factory->define(Itabletki\Drug::class, function (Faker\Generator $faker) {

    return [
        'name'=>$faker->name,
        'slug'=> $faker->slug(3),
        'top_drug'=>$faker->randomNumber(),
        'hide'=>$faker->boolean(),
        'latin_name'=>$faker->words(3, true),
        'created_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),
        'updated_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),

    ];
});


$factory->define(Itabletki\ActiveIngredient::class, function (Faker\Generator $faker) {

    return [
        'name'=> $faker->words(2,true),
        'slug'=> $faker->slug(3),
        'latin_name'=>$faker->words(2,true),
        'description'=>$faker->paragraph(),
        'created_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),
        'updated_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),

    ];
});


$factory->define(Itabletki\DrugRating::class, function (Faker\Generator $faker) {

    return [
        'comment'=> $faker->name,

        'effectiveness'=>$faker->numberBetween(0,10),
        'tolerability'=>$faker->numberBetween(0,10),
        'easeofuse'=>$faker->numberBetween(0,10),
        'recommend'=>$faker->numberBetween(0,10),
        'published'=>$faker->boolean(),
        'client_ip'=>$faker->ipv4,
        'created_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),
        'updated_at'=>\Carbon\Carbon::createFromFormat('Y-m-d',$faker->date()),

    ];
});
/*
|--------------------------------------------------------------------------
| Transaction Factory
|--------------------------------------------------------------------------
*/

$factory->define(Btcc\Repositories\Users\Transaction\Transaction::class, function (Faker\Generator $faker) {
    return [

        // Transaction table data

    ];
});

$factory->define(Btcc\Models\SupportTicket::class, function (Faker\Generator $faker) {
    return [
        'user_id' =>  $faker->randomNumber() ,
        'status' =>  $faker->randomNumber() ,
        'operator_id' =>  $faker->randomNumber() ,
        'subject' =>  $faker->word ,
        'message' =>  $faker->text ,
    ];
});

$factory->define(Btcc\Models\Transaction\BaseTransaction::class, function (Faker\Generator $faker) {
    return [
        'parent_user_id' =>  function () {
             return factory(Btcc\Models\User::class)->create()->id;
        } ,
        'reciever' =>  function () {
             return factory(Btcc\Models\User::class)->create()->id;
        } ,
        'sender' =>  function () {
             return factory(Btcc\Models\User::class)->create()->id;
        } ,
    ];
});

$factory->define(Btcc\Models\UserTransaction::class, function (Faker\Generator $faker) {
    return [
        'user_id' =>  $faker->randomNumber() ,
        'amount' =>  $faker->randomNumber() ,
        'type' =>  $faker->randomNumber() ,
        'status' =>  $faker->randomNumber() ,
        'sender_id' =>  $faker->randomNumber() ,
        'reciever_id' =>  $faker->randomNumber() ,
        'debit_flag' =>  $faker->boolean ,
        'credit_flag' =>  $faker->boolean ,
        'comment' =>  $faker->word ,
        'hash' =>  $faker->word ,
        'parent_user_id' =>  function () {
             return factory(Btcc\Models\User::class)->create()->id;
        } ,
        'reciever' =>  function () {
             return factory(Btcc\Models\User::class)->create()->id;
        } ,
        'sender' =>  function () {
             return factory(Btcc\Models\User::class)->create()->id;
        } ,
    ];
});

$factory->define(Btcc\Models\Wallet::class, function (Faker\Generator $faker) {
    return [
        'user_id' =>  function () {
             return factory(Btcc\Models\User::class)->create()->id;
        } ,
    ];
});

$factory->define(Btcc\Models\Profile::class, function (Faker\Generator $faker) {
    return [
       /*'user_id' =>  function () {
             return factory(Btcc\Models\User::class)->create()->id;
        } ,*/
        'package_id' =>  $faker->randomNumber() ,
        'country_code' =>  $faker->randomElement(['ru','us','uk']) ,
        'phone' =>  $faker->phoneNumber ,
        'city_id' =>  $faker->randomNumber() ,
    ];
});

