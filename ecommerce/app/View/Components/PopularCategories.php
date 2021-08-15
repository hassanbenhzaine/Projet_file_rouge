<?php

namespace App\View\Components;

use App\Http\Controllers\CategoryController;
use Illuminate\View\Component;

class PopularCategories extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $categoriesController = new CategoryController;
        $categoriesResult = $categoriesController->popular();


        return view('components.popular-categories', ['categories' => $categoriesResult]);
    }
}
