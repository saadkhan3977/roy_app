<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\User;
use App\Models\Carts;
use Validator;
use Auth;
use Stripe;
use Str;
use App\Http\Controllers\API\BaseController as BaseController;
class OrderController extends BaseController
{
    public function order(Request $request)
    {
         //print_r( $request->products);die;
        try{
            $validator = Validator::make($request->all(), [
                'payment_method'=> 'required',
                'first_name'=> 'required',
                'last_name'=> 'required',
                'address1'=> 'required',
                //'coupon'=> 'required',
                'phone'=> 'required',
                'country'=> 'required',
                'post_code'=> 'required',
                'email'=> 'required',
            ]);
            if($validator->fails())
            {
                return $this->sendError($validator->errors()->first(),500);
            }
            // $cart = Carts::where(['user_id'=> Auth::user()->id , 'order_id'=> null])->first();
            // $quantity = Carts::where('user_id',Auth::user()->id)->where('order_id',null)->sum('quantity');
            // if(empty($cart))
            // {
            //     return $this->sendError('Your Cart Is Empty Please Add Your Cart First');
            // }
            if($request->payment_method == 'stripe')
            {
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                Stripe\Charge::create ([
                        "amount" => $request->amount * 100,
                        "currency" => "usd",
                        "source" => $request->stripeToken,
                        "description" => "Test payment from Fashion Store Application"
                ]);
            }

            // $total_amount = Carts::where('user_id',Auth::user()->id)->where('order_id',null)->sum('amount');
            $order_no = 'ORD-'.strtoupper(Str::random(10));
            $order = Orders::create([
                'user_id' => Auth::user()->id,
                'payment_method' => $request->input('payment_method'),
                'order_number' => $order_no,
                'first_name' => $request->input('first_name'),
				'payment_status' => ($request->input('payment_method') == 'cod') ? 'unpaid' : 'paid' ,
                'last_name' => $request->input('last_name'),
                'address1' => $request->input('address1'),
                'address2'=> $request->input('address2'),
                'coupon'=> $request->input('coupon'),
                'phone'=> $request->input('phone'),
                'country'=> $request->input('country'),
                'post_code'=> $request->input('post_code'),
                'email'=> $request->input('email'),
                'total_amount'=> $request->input('amount'),
				'discount_amount' => $request->input('discount_amount'),
                'sub_total'=> $request->input('amount'),
                'quantity'=> $request->input('total_quantity'),
            ]);

            foreach($request->products as $key => $row )
            {
                $cart = Carts::create([
                    'size_id' => $request->products[$key]['size_id'],
                    'product_id' =>  $request->products[$key]['id'],
                    'order_id' => $order->id,
                    'user_id' => Auth::user()->id,
                    'price' =>  $request->products[$key]['price'],
                    'status' => 'new',
                    'quantity' => $request->products[$key]['quantity'],
                    'amount'=>  $request->products[$key]['price'] *  $request->products[$key]['quantity'],
                ]);
            }

			$orderr = Orders::with('item_info', 'item_info.product_info','item_info.category_info', 'item_info.sub_cat_info', 'item_info.child_cat_info', 'item_info.varation')->find($order->id);

            return response()->json(['success'=>true,'message'=>'Your Order has been Sent','order_info'=>$orderr]);

        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'message'=>$e->getMessage()]);
        }
    }
    public function order_list()
    {
        $data = Orders::with('item_info', 'item_info.product_info','item_info.category_info', 'item_info.sub_cat_info', 'item_info.child_cat_info', 'item_info.varation')->where('user_id',Auth::user()->id)->get();
        return response()->json(['success'=>true,'order_info'=>$data,'message'=>'order List']);
    }
}

