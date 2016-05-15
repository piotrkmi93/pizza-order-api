<?php

namespace App\Http\Controllers;

//use \App\Input;
use File;
use Illuminate\Http\Request;
use App\Http\Requests;
use \Validator;
use \App\Topping as Topping;

class ToppingController extends Controller
{
    public function showToppings() {
        $toppings = json_decode(Topping::all());
        foreach($toppings as $topping){
            $topping -> prices = json_decode($topping -> prices);
        }
        $data = array("toppings" => $toppings);
        return view('toppings-edit', $data);
    }

    public function addTopping(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'img' => 'required',
            'img_pizza' => 'required',
            'price1' => 'required',
            'price2' => 'required',
            'price3' => 'required',
            'price4' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/toppings-edit')
                ->withInput()
                ->withErrors($validator);
        }

        $img_src = 'img/' . strtolower( $request -> name ) . '.' . $request->img->extension();
        $img_pizza_src = 'pizza_img/' . str_replace(' ', '_', strtolower( $request -> name )) . '.' . $request->img_pizza->extension();

        if (!file_exists($img_src)){
            move_uploaded_file($request -> img, $_SERVER['DOCUMENT_ROOT'] . "/" . $img_src);
        }

        if(!file_exists($img_pizza_src)){
            move_uploaded_file($request -> img_pizza, $_SERVER['DOCUMENT_ROOT'] . "/" . $img_pizza_src);
        }


        $topping = new Topping;
        $topping -> name = $request -> name;
        $topping -> img = $img_src;
        $topping -> prices = "[" .
            floatval($request -> price1) . ", " .
            floatval($request -> price2) . ", " .
            floatval($request -> price3) . ", " .
            floatval($request -> price4) . "]";
        $topping -> img_pizza = $img_pizza_src;
        $topping -> save();
        return redirect('/toppings-edit');

    }

    public function deleteTopping($id){
        $topping = Topping::find($id);

        if ( file_exists($_SERVER['DOCUMENT_ROOT'] . $topping -> img) ) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $topping -> img);
        }

        if ( file_exists($_SERVER['DOCUMENT_ROOT'] . $topping -> img_pizza) ) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $topping -> img_pizza);
        }

        $topping -> delete();
        return redirect('/toppings-edit');

    }

    public function editTopping(Request $request){
        $validator = Validator::make($request->all(), []);

        $topping = Topping::find($request->id);

        if ($request -> name != "" && $request -> name != $topping -> name){
            $topping -> name = $request -> name;
        }

        if ($request -> img){
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $topping -> img))
                unlink($_SERVER['DOCUMENT_ROOT'] . $topping -> img);
            $img_src = 'img/' . strtolower( $topping -> name ) . '.' . $request->img->extension();
            move_uploaded_file($request -> img, $_SERVER['DOCUMENT_ROOT'] . "/" . $img_src);
            $topping -> $img_src;
        }

        if ($request -> img_pizza){
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $topping -> img_pizza))
                unlink($_SERVER['DOCUMENT_ROOT'] . $topping -> img_pizza);
            $img_pizza_src = 'pizza_img/' . str_replace(' ', '_', strtolower( $topping -> name )) . '.' . $request->img_pizza->extension();
            move_uploaded_file($request -> img_pizza, $_SERVER['DOCUMENT_ROOT'] . "/" . $img_pizza_src);
            $topping -> img_pizza = $img_pizza_src;
        }
//
        $prices = json_decode($topping -> prices);

        if ($request -> price1 != "" && floatval($request -> price1) != $prices[0]){
            $prices[0] = floatval($request -> price1);
        }

        if ($request -> price2 != "" && floatval($request -> price2) != $prices[1]){
            $prices[1] = floatval($request -> price2);
        }

        if ($request -> price3 != "" && floatval($request -> price3) != $prices[2]){
            $prices[2] = floatval($request -> price3);
        }

        if ($request -> price4 != "" && floatval($request -> price4) != $prices[3]){
            $prices[3] = floatval($request -> price4);
        }

        $topping -> prices = "[" .
            $prices[0] . ", " .
            $prices[1] . ", " .
            $prices[2] . ", " .
            $prices[3] .
        "]";


        $topping -> save();
        return redirect('/toppings-edit');
    }

    public function getAllToppings() {
		return Topping::all();
    }
    
    public function getToppingById($id) {
        return Topping::find($id)['attributes'];
    }

    public function getToppingNameById($id){
        $topping = Topping::find($id);
        return $topping -> name;
    }
}
