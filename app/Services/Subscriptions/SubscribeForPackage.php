<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/14/2016
 * Time: 4:18 AM
 * Filename: Packages.php
 */

namespace Btcc\Services\Subscriptions;

use Countable;
use Exception;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;

/**
 * Class Packages
 * @package Btcc\Services\Subscriptions
 */
class SubscribeForPackage implements Countable, IteratorAggregate, JsonSerializable {
    /**
     * All of the defined packages.
     * @var array
     */
    protected $packages = [];

    /**
     * Create a new package collection instance.
     *
     * @param array $packages
     *
     * @internal param array $packages
     */
    public function __construct1(array $packages = [])
    {
        $this->packages = $packages;
    }


    /**
     * Create a new package collection instance.
     *
     * @param array $packages
     *
     * @internal param array $packages
     */
    public function __construct(array $packagesConfig = [])
    {
       // $this->packages = $packages;

        foreach ($packagesConfig as $c) {


            $this->create($c['name'],$c['id'])->price($c['price'])->features($c['features']);
        }

    }

    /**
     * Create a new package instance.
     *
     * @param  string $name
     * @param  string $id
     *
     * @return \Btcc\Services\Subscriptions\Package
     */
    public function create($name, $id)
    {
        return $this->add(new Package($name, $id));
    }

    /**
     * Get package matching a given ID.
     *
     * @param  string $id
     *
     * @return \Btcc\Services\Subscriptions\Package
     * @throws \Exception
     */
    public function find($id)
    {
        foreach ($this->packages as $package) {
            if ($package->id === $id) {
                return $package;
            }
        }

        throw new Exception("Unable to find package with ID [{$id}].");
    }

    /**
     * Add a package to the package collection.
     *
     * @param  \Btcc\Services\Subscriptions\Package $package
     *
     * @return \Btcc\Services\Subscriptions\Package
     */
    public function add(Package $package)
    {
        $this->packages[] = $package;

        return $package;
    }

    /**
     * Determine if the package collection has paid packages.
     * @return bool
     */
    public function hasPaidPackages()
    {
        return count($this->paid()) > 0;
    }

    /**
     * Get all of the packages that require payment (price > 0).
     * @return array
     */
    public function paid()
    {
        return new self(array_values(array_filter($this->packages, function ($package) {
            return $package->price > 0;
        })));
    }

    /**
     * Get all of the monthly packages for a given tier.
     * @return array
     */
    public function tier($tier)
    {
        return new self(array_values(array_filter($this->packages, function ($package) use ($tier) {
            return $package->tier === $tier;
        })));
    }

    /**
     * Get all of the monthly packages for the application.
     * @return array
     */
    public function monthly()
    {
        return new self(array_values(array_filter($this->packages, function ($package) {
            return $package->isMonthly();
        })));
    }

    /**
     * Get all of the yearly packages for the application.
     * @return array
     */
    public function yearly()
    {
        return new self(array_values(array_filter($this->packages, function ($package) {
            return $package->isYearly();
        })));
    }

    /**
     * Get all of the packages that are active.
     * @return array
     */
    public function active()
    {
        return new self(array_values(array_filter($this->packages, function ($package) {
            return $package->isActive();
        })));
    }

    /**
     * Determine the number of packages in the collection.
     * @return int
     */
    public function count()
    {
        return count($this->packages);
    }

    /**
     * Get an iterator for the collection.
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->packages);
    }

    /**
     * Get the JSON serializable fields for the object.
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->packages;
    }

    public function getPackagesIds()
    {
        return collect($this->packages)->map(function(Package $package) {
            return $package->id;
        });
    }
}