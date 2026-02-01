@extends('user.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Order       
  <a href="{{route('order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right">
    <i class="fas fa-download fa-sm text-white-50"></i> Generate PDF
  </a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>S.N.</th>
            <th>Order No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Quantity</th>
            <th>Charge</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->order_number}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->quantity}}</td>
            {{-- Fix: Add null check for shipping to prevent error when shipping_id is null --}}
            <td>${{$order->shipping ? number_format($order->shipping->price, 2) : '0.00'}}</td>
            <td>${{number_format($order->total_amount,2)}}</td>
            <td>
                @if($order->status=='new')
                  <span class="badge badge-primary">{{$order->status}}</span>
                @elseif($order->status=='process')
                  <span class="badge badge-warning">{{$order->status}}</span>
                @elseif($order->status=='delivered')
                  <span class="badge badge-success">{{$order->status}}</span>
                @else
                  <span class="badge badge-danger">{{$order->status}}</span>
                @endif
            </td>
            <td>
                <form method="POST" action="{{route('order.destroy',[$order->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>

        </tr>
      </tbody>
    </table>

        <section class="order-products-section mt-4">
      <div class="card">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Order Products</h5>
        </div>
        <div class="card-body">
          @if($order->cart_info && $order->cart_info->count() > 0)
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="thead-light">
                  <tr>
                    <th width="10%">Image</th>
                    <th width="30%">Product Name</th>
                    <th width="25%">Description</th>
                    <th width="10%">Price</th>
                    <th width="10%">Quantity</th>
                    <th width="15%">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->cart_info as $cart)
                  <tr>
                    {{-- Foto dari snapshot, bukan dari $cart->product->photo.     --}}
                    {{-- Kalau produk diedit/dihapus admin, foto ini tetap dari    --}}
                    {{-- waktu checkout. Kalau snapshot kosong, tampil placeholder. --}}
                    <td class="text-center">
                      @if($cart->product_photo)
                        <img src="{{$cart->product_photo}}"
                             alt="{{$cart->product_title}}"
                             class="img-thumbnail"
                             style="width: 80px; height: 80px; object-fit: cover;">
                      @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; margin: 0 auto;">
                          <i class="fas fa-image fa-2x"></i>
                        </div>
                      @endif
                    </td>

                    {{-- Nama dari snapshot. "View Product" link hanya muncul     --}}
                    {{-- kalau produk masih ada di database (belum dihapus admin). --}}
                    <td>
                      <strong>{{$cart->product_title ?? 'Product Not Available'}}</strong>
                      @if($cart->product && $cart->product->id)
                        <br>
                        <small class="text-muted">
                          <a href="{{route('product-detail', $cart->product->slug)}}" target="_blank" class="text-info">
                            <i class="fas fa-external-link-alt"></i> View Product
                          </a>
                        </small>
                      @endif
                    </td>

                    {{-- Deskripsi dari snapshot, dipotong 100 karakter --}}
                    <td>
                      <small>
                        {{$cart->product_summary ? Str::limit($cart->product_summary, 100) : 'No description available'}}
                      </small>
                    </td>

                    {{-- Price dan amount sudah ada di carts table sejak add to cart --}}
                    <td class="text-right">
                      <strong>${{number_format($cart->price, 2)}}</strong>
                    </td>
                    <td class="text-center">
                      <span class="badge badge-primary badge-pill">{{$cart->quantity}}</span>
                    </td>
                    <td class="text-right">
                      <strong class="text-success">${{number_format($cart->amount, 2)}}</strong>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                {{-- Footer ringkasan harga --}}
                <tfoot class="bg-light">
                  <tr>
                    <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right"><strong>${{number_format($order->sub_total, 2)}}</strong></td>
                  </tr>
                  <tr>
                    <td colspan="5" class="text-right"><strong>Shipping Charge:</strong></td>
                    {{-- Fix: Add null check for shipping to prevent error when shipping_id is null --}}
                    <td class="text-right"><strong>${{$order->shipping ? number_format($order->shipping->price, 2) : '0.00'}}</strong></td>
                  </tr>
                  {{-- Row diskon hanya muncul kalau ada coupon yang dipakai --}}
                  @if($order->coupon > 0)
                  <tr>
                    <td colspan="5" class="text-right"><strong>Discount:</strong></td>
                    <td class="text-right text-danger"><strong>-${{number_format($order->coupon, 2)}}</strong></td>
                  </tr>
                  @endif
                  <tr class="table-success">
                    <td colspan="5" class="text-right"><h5 class="mb-0"><strong>Total Amount:</strong></h5></td>
                    <td class="text-right"><h5 class="mb-0"><strong>${{number_format($order->total_amount, 2)}}</strong></h5></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          @else
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i> No products found in this order.
            </div>
          @endif
        </div>
      </div>
    </section>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">ORDER INFORMATION</h4>
              <table class="table">
                    <tr class="">
                        <td>Order Number</td>
                        <td> : {{$order->order_number}}</td>
                    </tr>
                    <tr>
                        <td>Order Date</td>
                        <td> : {{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                        <td>Quantity</td>
                        <td> : {{$order->quantity}}</td>
                    </tr>
                    <tr>
                        <td>Order Status</td>
                        <td> : {{$order->status}}</td>
                    </tr>
                    <tr>
                      {{-- Ambil shipping dari relasi $order->shipping--}}
                        <td>Shipping Charge</td>
                        {{-- Fix: Add null check for shipping to prevent error when shipping_id is null --}}
                        <td> : ${{$order->shipping ? number_format($order->shipping->price, 2) : '0.00'}}</td>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <td> : $ {{number_format($order->total_amount,2)}}</td>
                    </tr>
                    <tr>
                      <td>Payment Method</td>
                      <td> : @if($order->payment_method=='cod') Cash on Delivery @else Paypal @endif</td>
                    </tr>
                    <tr>
                        <td>Payment Status</td>
                        <td> : {{$order->payment_status}}</td>
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">SHIPPING INFORMATION</h4>
              <table class="table">
                    <tr class="">
                        <td>Full Name</td>
                        <td> : {{$order->first_name}} {{$order->last_name}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td> : {{$order->email}}</td>
                    </tr>
                    <tr>
                        <td>Phone No.</td>
                        <td> : {{$order->phone}}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td> : {{$order->address1}}, {{$order->address2}}</td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td> : {{$order->country}}</td>
                    </tr>
                    <tr>
                        <td>Post Code</td>
                        <td> : {{$order->post_code}}</td>
                    </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
