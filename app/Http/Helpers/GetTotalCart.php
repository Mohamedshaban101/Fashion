<?php 

namespace App\Http\Helpers;


class GetTotalCart{
    public static  function getTotalCart(){
        $cart = session()->get('cart' , []);

        $total = 0;

        foreach($cart as $item){
            $total += $item['regular_price'] * $item['quantity'];
        }
        return $total;
    } 
}

?>