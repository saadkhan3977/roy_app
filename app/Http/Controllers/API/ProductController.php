<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Product as ProductResource;
use App\Models\Categories;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$products = Product::with('category_info', 'sub_cat_info', 'child_cat_info', 'varation')->orderBy('id','desc')->paginate(20);

        return $this->sendResponse( $products, 'Products lists.');
    }
	
	public function product_by_categories($id)
    {
        $products = Product::with('category_info', 'sub_cat_info', 'child_cat_info', 'varation')->where('cat_id',$id)->paginate(20);
        return $this->sendResponse( $products, 'Products lists.');
    }
	
	public function product_by_sub_categories($id)
    {
        $products = Product::with('category_info', 'sub_cat_info', 'child_cat_info', 'varation')->where('sub_cat_id',$id)->paginate(20);
        return $this->sendResponse( $products, 'Products lists.');
    }

		public function product_by_child_categories($id)
    {
        $products = Product::with('category_info', 'sub_cat_info', 'child_cat_info', 'varation')->where('child_cat_id',$id)->paginate(20);
        return $this->sendResponse( $products, 'Products lists.');
    }
	
	public function category_list()
    {
        $data = Categories::with('sub_categories','sub_categories.child_categories')->get();
        return $this->sendResponse($data,'category List');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	
	public function detail_info($id)
    {
        $products = Product::with('category_info', 'sub_cat_info', 'child_cat_info', 'varation')->find($id);
        return $this->sendResponse( $products, 'Products lists.');
    }
	
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category_info', 'varation')->find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        //return $input;
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $product = Product::find($id);
        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return $this->sendResponse([], 'Product Deleted Successfully.');
    }
}
