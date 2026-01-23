<?php

namespace App\Http\Controllers;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Cart;
class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::orderBy('id', 'DESC')->paginate(10);
        return view('backend.coupon.index')->with('coupons', $coupons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.coupon.create');
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
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $coupon = Coupon::create($validated);
            
            return redirect()->route('coupon.index')
                ->with('success', 'Coupon successfully added');
        } catch (\Exception $e) {
            \Log::error('Coupon creation failed: ' . $e->getMessage());
            return redirect()->route('coupon.index')
                ->with('error', 'Please try again!');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('backend.coupon.edit')->with('coupon', $coupon);
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
        $coupon = Coupon::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $status = $coupon->update($validated);
            
            return redirect()->route('coupon.index')
                ->with($status ? 'success' : 'error', 
                    $status ? 'Coupon successfully updated' : 'Please try again!');
        } catch (\Exception $e) {
            \Log::error('Coupon update failed: ' . $e->getMessage());
            return redirect()->route('coupon.index')
                ->with('error', 'Please try again!');
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
            $coupon = Coupon::findOrFail($id);
            $status = $coupon->delete();
            
            return redirect()->route('coupon.index')
                ->with($status ? 'success' : 'error',
                    $status ? 'Coupon successfully deleted' : 'Error, Please try again');
        } catch (\Exception $e) {
            \Log::error('Coupon deletion failed: ' . $e->getMessage());
            return redirect()->route('coupon.index')
                ->with('error', 'Coupon could not be deleted');
        }
    }

    public function couponStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50'
        ]);

        $coupon = Coupon::where('code', $validated['code'])
            ->where('status', 'active')
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code. Please try again.');
        }

        $totalPrice = Cart::where('user_id', auth()->user()->id)
            ->where('order_id', null)
            ->sum('price');

        if ($totalPrice <= 0) {
            return back()->with('error', 'Your cart is empty.');
        }

        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'value' => $coupon->discount($totalPrice)
        ]);

        return back()->with('success', 'Coupon successfully applied');
    }
}
