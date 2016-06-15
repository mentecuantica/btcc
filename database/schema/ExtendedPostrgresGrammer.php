<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 6/15/2016
 * Time: 8:29 AM
 * Filename: ExtendedPostrgresGrammer.php
 */

namespace Database\Schema;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Support\Fluent;

/**
 * Class ExtendedPostrgresGrammer
 * @package database\schema
 */
class ExtendedPostrgresGrammer extends PostgresGrammar {
    /**
     * Create the column definition for an 'set' type.
     *
     * @param  Fluent  $column
     * @return string
     */
    protected function typeEnum_pg(Fluent $column)
    {
        return $column->enumTypeName;
    }
}