<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \App\Order as Order;
use \App\Http\Controllers\ToppingController as ToppingController;
use \App\Status as Status;

class OrderController extends Controller
{
    public function addOrder(Request $request){
        $order = new Order;
        $order -> address =
            $request -> streetName      . " " .
            $request -> buildingNumber    .
            ($request -> apartamentNumber ? "/" .  $request -> apartamentNumber . ", " : ", ") .
            $request -> cityName;

        $order -> phone = $request -> phoneNumber;

        $order -> toppings = $request -> toppings;

        $order -> size = $request -> size;

        $order -> price = $request -> price;

        if( $order -> save() ){
            return "OK";
        } else {
            return "Order not saved";
        }
    }

    public function getOrders(){
        $orders = Order::all();
        $colors = array();
        foreach($orders as $order){
            $order -> toppings = self::formatToppings($order -> toppings);
            switch ($order -> size){
                case 0: $order -> size = "mała"; break;
                case 1: $order -> size = "średnia"; break;
                case 2: $order -> size = "duża"; break;
                case 3: $order -> size = "mega"; break;
            }
            switch ($order -> status){
                case 1: array_push($colors, "lightcoral"); break;
                case 2: array_push($colors, "palegoldenrod"); break;
                case 3: array_push($colors, "palegoldenrod"); break;
                case 4: array_push($colors, "lightcoral"); break;
                case 5: array_push($colors, "yellowgreen"); break;
                case 6: array_push($colors, "yellowgreen"); break;
            }
        }
        return view("orders", array(
            "orders" => $orders,
            "colors" => $colors,
            "statuses" => Status::all()
        ));
    }

    private function formatToppings($toppings){
        $toppings = json_decode($toppings);
        $toppingController = new ToppingController();

        foreach($toppings as $key => $topping){
            $toppings[$key] = $toppingController -> getToppingNameById($topping);
        }

        return $toppings;
    }

    public function changeStatus(Request $request){
        $order = Order::find($request -> orderId);
        $order -> status = $request -> statusId;
        $order -> save();
        return redirect("/orders");
    }

    public function deleteOrder($id){
        $order = Order::find($id);
        $order -> delete();
        return redirect("/orders");
    }
}
