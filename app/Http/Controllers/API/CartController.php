<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cart;
use Illuminate\Http\Request;
use App\Models\Carts;
use Validator;
use Auth;
use App\Http\Controllers\API\BaseController as BaseController;
class CartController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Carts::where('user_id',Auth::user()->id)->where('order_id',null)->get();

        return $this->sendResponse($carts, 'Carts Retrieved Successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{

            $input = $request->all();

            $validator = Validator::make($input, [
                'size_id' => 'required',
                'product_id' => 'required',
                //'order_id' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'amount' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors(),500);
            }


            $cart = Carts::create([
                'size_id' => $request->input('size_id'),
                'product_id' => $request->input('product_id'),
                //'order_id' => $request->input('order_id'),
                'user_id' => Auth::user()->id,
                'price' => $request->input('price'),
                'status' => 'new',
                'quantity' => $request->input('quantity'),
                'amount'=> $request->input('amount'),
            ]);

            return $this->sendResponse($cart,'Cart Created Successfully.');
        }
        catch(\Exception $e)
        {
            return response()->json(['success'=>false,'error'=> $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cart = Carts::find($id);

        if (is_null($cart)) {
            return $this->sendError('Cart not found.');
        }

        return $this->sendResponse(new Cart($cart), 'Cart Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cart = Carts::find($id);
        $cart->delete();

        return $this->sendResponse([], 'Cart Deleted Successfully.');
    }
}
