<?php

namespace App\View\Components;

use App\Facades\cart;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CartMenu extends Component
{
    public $items;
    public $total;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->items=cart::get(); // here we used a facade and get a method from this class
        $this->total=cart::total(); // get total from

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cart-menu');
    }
}
