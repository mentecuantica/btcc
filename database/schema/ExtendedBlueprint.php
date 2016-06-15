<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/15/2016
 * Time: 8:26 AM
 * Filename: ExtendedBlueprint.php
 */

namespace Database\Schema;

use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;





/**
 * Class ExtendedBlueprint
 * @package database\schema
 */
class ExtendedBlueprint extends Blueprint {
    /**
     * Create a new 'set' column on the table.
     *
     * @param  string  $column
     * @param  array   $allowed
     * @return \Illuminate\Support\Fluent
     */
    public function enum_pg($column, $enumTypeName)
    {
        return $this->addColumn('enum_pg', $column, compact('enumTypeName'));
    }
}