<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Nav extends Component
{
    public $items;
    public $active;
    /**
     * Create a new component instance.
     */
    // if i want to pass a para to component we must define it in constructor
    public function __construct()
    {

        $this->items=$this->prepareItem(config('nav'));

        $this->active=\Illuminate\Support\Facades\Route::currentRouteName();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nav');
    }
    public function prepareItem($items){

        $user=Auth::user();
        foreach ($items as $key =>$item){
            //dd($item);
            if(isset($item['ability']) && !$user->can($item['ability'])){
                unset($items[$key]);
            }
        }
        return $items;

    }
}
