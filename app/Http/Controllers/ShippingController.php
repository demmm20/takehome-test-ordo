<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Coupon;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shipping=Shipping::orderBy('id','DESC')->paginate(10);
        return view('backend.shipping.index')->with('shippings',$shipping);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.shipping.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $shipping = Shipping::create($validated);
            
            return redirect()->route('shipping.index')
                ->with('success', 'Shipping successfully created');
        } catch (\Exception $e) {
            \Log::error('Shipping creation failed: ' . $e->getMessage());
            return redirect()->route('shipping.index')
                ->with('error', 'Error, Please try again');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shipping = Shipping::findOrFail($id);
        return view('backend.shipping.edit')->with('shipping', $shipping);
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
        $shipping = Shipping::findOrFail($id);
        
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $status = $shipping->update($validated);
            
            return redirect()->route('shipping.index')
                ->with($status ? 'success' : 'error',
                    $status ? 'Shipping successfully updated' : 'Error, Please try again');
        } catch (\Exception $e) {
            \Log::error('Shipping update failed: ' . $e->getMessage());
            return redirect()->route('shipping.index')
                ->with('error', 'Error, Please try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $shipping = Shipping::findOrFail($id);
            $status = $shipping->delete();
            
            return redirect()->route('shipping.index')
                ->with($status ? 'success' : 'error',
                    $status ? 'Shipping successfully deleted' : 'Error, Please try again');
        } catch (\Exception $e) {
            \Log::error('Shipping deletion failed: ' . $e->getMessage());
            return redirect()->route('shipping.index')
                ->with('error', 'Shipping could not be deleted');
        }
    }
}
