<?php
/**
 * Author: Alan Karr
 * User: octopus
 * Date: 7/27/2016
 * Time: 10:25 AM
 * Filename: FormMacroServiceProvider.php
 */

namespace Btcc\Providers;

/**
 * Class FormMacroServiceProvider
 * @package Btcc\Providers
 */

use Form;
use Illuminate\Support\ServiceProvider;

class FormMacroServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot()
    {

        Form::macro('bs3Input', function ($name, $text, $placeholder = NULL, $type = 'text') {
            $label = Form::label($name, $text, ['class' => 'control-label']);
            $input = Form::input($type, $name, NULL, ['placeholder' => $placeholder]);

            $item
                = <<<HTML
            <div class="control-group">
                $label
                <div class="controls">
                      $input
                </div>
            </div>
HTML;

            return $item;
        }

        );

        Form::macro('selectWeekDay', function () {
            $days = [
                'monday'    => 'Monday',
                'tuesday'   => 'Tuesday',
                'wednesday' => 'Wednesday',
                'thursday'  => 'Thursday',
                'friday'    => 'Friday',
                'saturday'  => 'Saturday',
                'sunday'    => 'Sunday',
            ];

            return Form::select('day', $days, NULL, ['class' => 'form-control']);
        });
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        //
    }
}