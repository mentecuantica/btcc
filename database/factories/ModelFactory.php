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
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});



/**
 * SAMPLES
 *
 *
 */

$factory->define(Itabletki\User::class, function ($faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
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