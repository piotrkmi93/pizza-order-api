<?php

namespace App\Http\Controllers;

use Input;
use App\Http\Requests;
use Illuminate\Http\Request;

class toppingsEditController extends Controller
{

    
    public function addTopping(){
        $name = Input::post('name');
        $this -> showToppings();
    }
}
