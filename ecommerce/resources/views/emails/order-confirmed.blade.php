@component('mail::message')
# Dear {{$user->name}},<br><br>

#### Thank you for your order **#{{$orderInfo['number']}}** placed on: **{{$orderInfo['created_at']}}**.<br>

#### You will find summary of your order below.<br><br>

<table width="100%">
<thead>
<tr>
<th>Product name</th>
<th>Quantity</th>
<th>Price</th>
</tr>
</thead>
<tbody>
<br>
@php $i = 0 @endphp
@foreach ($orderedProducts as $product) 
<tr>
<td>{{$product->title}}</td>
<td style="text-align:center">{{$orderInfo['products'][$i]['qty']}}</td>
<td style="text-align:right">${{$product->price}}</td>
</tr>
@php $i++ @endphp
@endforeach
</tbody>
</table>
<br>
<div style="text-align: right; width:100%;">Total:  ${{$orderInfo['total']}}</div><br>

#### Address of shipping:<br>

<div>{{Auth::user()->name}},</div>

<div>{{$address->address1}}</div>

<div>{{$address->address2}} {{$address->zip_code}} - {{Countries::getOne($address->country, 'en')}}</div>

<div>Phone: {{Auth::user()->phone}}</div>

@component('mail::button', ['url' => route('dashboard', ['view' => 'orders'])])
View order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
