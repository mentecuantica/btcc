<?php

class RegisterTest extends TestCase {
    /**
     * @var FakerGenerator
     */
    protected $faker;

    /**
     * Setup faker
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    /**
     * My test implementation
     */
    public function testForecastIsGeneric()
    {
        $this->visit('/register');
        $this->type($this->faker->name, 'name');
        $this->type($this->faker->email, 'email');
        $this->press('Register');
    }
}