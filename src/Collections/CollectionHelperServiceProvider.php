<?php

namespace SebastiaanLuca\Helpers\Collections;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class CollectionHelperServiceProvider extends ServiceProvider
{
    /**
     * Boot all collection macros.
     */
    protected function bootMacros()
    {
        // Create Carbon instances from items in a collection
        Collection::macro('carbonize', function() {
            return collect($this->items)->map(function($time) {
                if (empty($time)) {
                    return null;
                }
                
                return new Carbon($time);
            });
        });
        
        // Reduce the collection to only include strings found between another start and end string
        Collection::macro('between', function($start, $end) {
            return collect($this->items)->reduce(function($items, $method) use ($start, $end) {
                if (preg_match('/^' . $start . '(.*)' . $end . '$/', $method, $matches)) {
                    $items[] = $matches[1];
                }
                
                return collect($items);
            });
        });
        
        // Return
        Collection::macro('methodize', function($method) {
            return collect($this->items)->map(function($item) use($method){
                return call_user_func($method, $item);
            });
        });
    }
    
    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
    
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->bootMacros();
    }
}
