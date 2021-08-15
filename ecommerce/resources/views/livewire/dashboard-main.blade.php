@php
    $allItems = [];
@endphp
<div class="container-fluid flex-grow-1">
    <div class="row h-100">
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-4">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a href="{{route('dashboard', ['view' => 'home'])}}" class="nav-link @if($view == "home") active @endif" aria-current="page">
                <span data-feather="home"></span>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('dashboard', ['view' => 'orders'])}}" class="nav-link @if($view == "orders") active @endif">
                <span data-feather="file"></span>
                Orders
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('dashboard', ['view' => 'addresses'])}}" class="nav-link @if($view == "addresses") active @endif">
                <span data-feather="file"></span>
                Addresses
              </a>
            </li>
            @switch(true)
                @case(Auth::user()->is_admin == 1)               
                <li class="nav-item">
                    <a href="{{route('dashboard', ['view' => 'products'])}}" class="nav-link @if($view == "products") active @endif">
                    <span data-feather="shopping-cart"></span>
                    Products
                  </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard', ['view' => 'customers'])}}" class="nav-link @if($view == "customers") active @endif">
                    <span data-feather="users"></span>
                    Customers
                  </a>
                </li>
                    @break
                @default  
            @endswitch
          </ul>

        </div>
      </nav>
  
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
          @switch(true)
              @case($view == 'home')
              @section('title', 'Dashboard')
              <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Welcome {{Auth::user()->name}}</h1>
              </div>
              <div class="chart-container mb-4" style="width:100%">
                <canvas id="myChart" height="130"></canvas>
            </div>
              <script src="{{asset('js/chart.min.js')}}"></script>
              <script>
              var ctx = document.getElementById('myChart').getContext('2d');
              var myChart = new Chart(ctx, {
                  type: 'line',
                  data: {
                      labels: @php echo json_encode($data['ordersPerMonth']['month']) @endphp,
                      datasets: [{
                          label: false,
                          data: @php echo json_encode($data['ordersPerMonth']['count']) @endphp,
                          fill: false,
                          borderColor: 'rgb(75, 192, 192)',
                          tension: 0.1
                      }]
                  },
                  options: {
                      scales: {
                          y: {
                            beginAtZero: true,
                            steps: 10,
                            stepValue: 5,
                            max: 20
                          }
                      },
                      plugins: {
                          title: {
                              display: true,
                              text: 'Total orders per month'
                          }
                      }
                  }
              });
              </script>
                  @break
            @case($view == 'orders')
            @section('title', 'Orders')
            @if ($editModal == 'order')
            <div class="modal fade show" id="editOrder" tabindex="-1" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                  <form class="modal-content" wire:submit.prevent="saveOrderEdit({{$editData['orderNumber']}})" enctype="multipart/form-data">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalCenterTitle">Edit product</h5>
                      <button onclick="document.getElementById('editOrder').remove()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                          
                      <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select wire:model.defer="editData.status" id="editStatus" class="form-select" required>
                          <option selected value="{{$editData['status']}}">{{str_replace('_', ' ', ucfirst($editData['status']))}}</option>
                          <option value="pending">Pending</option>
                          <option value="shipped">Shipped</option>
                          <option value="delivered">Delivered</option>
                          <option value="canceled">Canceled</option>
                          <option value="pending_delete">Pending delete</option>
                        </select>
                    </div>  

                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                </div>
              </div>

            @endif
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Orders</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                  @if (Auth::user()->is_admin == 1)
                    <form class="btn-group me-2" name="deleteOrders" wire:submit.prevent="deleteOrders">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Delete</button>
                    </form>
                  @endif
                    <form wire:submit.prevent="search"><input type="search" wire:model.defer="searchQuery" class="form-control form-control-sm" placeholder="Search" aria-controls="dataTable"></form>
                </div>
                </div>

                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                      <div class="row">
                        <div class="col-sm-12 col-md-6">
                          <div class="dataTables_length" id="dataTable_length">
                            <label class="d-flex mb-3 align-items-center">
                              <span class="me-2">Show</span>
                              <select wire:model="paginateLimit" aria-controls="dataTable" class="me-2 paginateLimit custom-select custom-select-sm form-control form-control-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                              </select>
                              <span>entries</span>
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6">


                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                              <tr role="row">
                                @if (Auth::user()->is_admin == 1)
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 2%;">
                                    @php
                                        foreach($data['orders'] as $order){
                                          $allItems[] = $order->number;
                                        }
                                    @endphp
                                    <input class="form-check-input" type="checkbox" id="select-all" wire:model.defer="selectAll" value="{{ json_encode($allItems) }}">
                                </th>
                                @endif
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 8%;">Number</th>
                                @if (Auth::user()->is_admin == 1)
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 20%;">User</th>                                   
                                @endif
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 30%;">Product</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 7%;">Quantity</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 10%;">Status</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 15%;">Ordered at</th>
                                @if (Auth::user()->is_admin == 1)
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"style="width: 15%;">Updated at</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"style="width: 5%;">Edit</th>
                                @else
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"style="width: 5%;">Cancel</th>
                                @endif
                              </tr>
                            </thead>
                            <tbody>
                              @php $orderI = 0; @endphp
                              @foreach ($data['orders'] as $order)
                                @if ($orderI == $order->number)
                                  <tr>
                                    @if (Auth::user()->is_admin == 1)<td></td>@endif
                                    <td></td>
                                    @if (Auth::user()->is_admin == 1)<td></td>@endif
                                    <td><a href="{{route('product').'?id='.$order->productId}}">{{$order->title}}</a></td>
                                    <td>{{$order->quantity}}</td>
                                    <td></td>
                                    @if (Auth::user()->is_admin == 1)<td></td>@endif
                                    <td></td>
                                  </tr>
                                @else
                                <tr>
                                  @if (Auth::user()->is_admin == 1)
                                  <td><input form="deleteOrders" class="form-check-input" type="checkbox" name="item" wire:model.defer="selectedItems" value="{{$order->number}}"></td>
                                  @endif
                                  <td>{{$order->number}}</td>
                                  @if (Auth::user()->is_admin == 1)<td>{{$order->email}}</td>@endif
                                  <td><a href="{{route('product').'?id='.$order->productId}}">{{$order->title}}</a></td>
                                  <td>{{$order->quantity}}</td>
                                  <td>{{str_replace('_', ' ', ucfirst($order->status))}}</td>
                                  <td>{{date('Y/m/d  h:m',strtotime($order->created_at))}}</td>
                                  @if (Auth::user()->is_admin == 1)
                                  <td>{{date('Y/m/d  h:m', strtotime($order->updated_at))}}</td>
                                  <td><a href="#" wire:click="editOrder({{$order->number}})">Edit</a></td>
                                  @else
                                  <td>
                                    <a href="#" wire:click="cancelOrder({{$order->number}})">Cancel</a>
                                  </td>
                                  @endif
                                </tr>
                                @endif
                                @php $orderI = $order->number; @endphp
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="row mb-4">
                        <div class="col-sm-12 col-md-5">
                          <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                              Showing {{($data['orders']->currentpage()-1)*$data['orders']->perpage()+1}} to {{$data['orders']->currentpage()*$data['orders']->perpage()}} of {{$data['orders']->total()}} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                          <div class="d-flex justify-content-center justify-content-md-start justify-content-lg-start" id="dataTable_paginate">
                            {{$data['orders']->links('pagination')}}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>   
                  @break
            @case(Auth::user()->is_admin == 1 && $view == 'products')
            @section('title', 'Products')
            @if ($editModal == 'product')
                
            <div class="modal fade show" id="editProduct" tabindex="-1" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                  <form class="modal-content" wire:submit.prevent="saveProductChanges({{$editData['id']}})" enctype="multipart/form-data">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalCenterTitle">{{$modalTitle}}</h5>
                      <button onclick="document.getElementById('editProduct').remove()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Title</label>
                            <input wire:model.defer="editData.title" type="text" class="form-control" id="exampleFormControlInput1" required>
                          </div>

                          <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                            <textarea wire:model.defer="editData.description" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                          </div>

                          <div class="mb-3 row">
                          
                              <div class="col-6">

                                <label for="exampleFormControlInput2" class="form-label">Price</label>
                                <div class="input-group">
                                  <input wire:model.defer="editData.price" step="0.01" type="number" min="0" class="form-control" id="exampleFormControlInput2">
                                  <span class="input-group-text">$</span>
                                </div>
                          
                              </div>
                                  
                              @if (count($categories) > 0)
                              <div class="col-6">
                                  <label for="exampleFormControlInput3" class="form-label">Category</label>
                                  <select wire:model.defer="editData.categoryId" class="form-select" aria-label="Default select example" id="exampleFormControlInput3" required>
                                      @foreach ($categories as $category)
                                      <option value="{{$category->id}}">{{$category->name}}</option>
                                      @endforeach
                                  </select>
                              </div>
                              @endif
                          </div>

                          <div class="mb-3 row">

                            <div class="col-6">
                              <label for="exampleFormControlInput5" class="form-label">Photos</label>
                              <input wire:model="editData.photos" type="file" class="form-control" id="exampleFormControlInput5" multiple accept=".jpg,.jpeg,.png,.webp">
                            </div>

                            <div class="col-6">
                              <label for="editCondition" class="form-label">Condition</label>
                              <select wire:model.defer="editData.condition" class="form-select" id="editCondition" required>
                                <option value="new">New</option>
                                <option value="used">Used</option>
                                <option value="refurbished">Refurbished</option>
                            </select>
                            </div>

                          </div>
                          

                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                </div>
              </div>
            @endif
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Products</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                  <div class="btn-group">
                    <form wire:submit.prevent="addProduct" class="me-2">
                      <button type="submit" class="btn btn-sm btn-outline-secondary">Add</button>
                      </form>
                      <form wire:submit.prevent="deleteProducts" class="me-2">
                      <button type="submit" class="btn btn-sm btn-outline-secondary">Delete</button>
                      </form>
                  </div>
                    <form wire:submit.prevent="search"><input type="search" wire:model.defer="searchQuery" class="form-control form-control-sm" placeholder="Search" aria-controls="dataTable"></form>
                </div>
                </div>

                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                      <div class="row">
                        <div class="col-sm-12 col-md-6">
                          <div class="dataTables_length" id="dataTable_length">
                            <label class="d-flex mb-3 align-items-center">
                              <span class="me-2">Show</span>
                              <select wire:model="paginateLimit" aria-controls="dataTable" class="me-2 paginateLimit custom-select custom-select-sm form-control form-control-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                              </select>
                              <span>entries</span>
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6">


                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                              <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 3%;">
                                    @php
                                    foreach($data['products'] as $product){
                                      $allItems[] = $product->productId;
                                    }
                                    @endphp
                                    <input class="form-check-input" type="checkbox" id="select-all" wire:model.defer="selectAll" value="{{ json_encode($allItems) }}">
                                </th>
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 25%;">Title</th>
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 25%;">Description</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 10%;">Price</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 10%;">Category</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 12%;">Added at</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"style="width: 12%;">Updated at</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"style="width: 3%;">Edit</th>
                              </tr>
                            </thead>
                            <tbody>

                                @foreach ($data['products'] as $product)
                                <tr >
                                    <td><input form="deleteProducts" class="form-check-input" type="checkbox" name="item" wire:model.defer="selectedItems" value="{{$product->productId}}"></td>
                                    <td><a href="{{route('product').'?id='.$product->productId}}">{{substr_replace($product->title, "...",50)}}</a></td>
                                    <td>{{substr_replace($product->description, "...",50)}}</td>
                                    <td>${{$product->price}}</td>
                                    <td><a href="{{route('category', $product->name)}}">{{ucfirst($product->name)}}</a></td>
                                    <td>{{date('Y/m/d  h:m',strtotime($product->created_at))}}</td>
                                    <td>{{date('Y/m/d  h:m', strtotime($product->updated_at))}}</td>
                                    <td><a href="#" wire:click="editProduct({{$product->productId}})">Edit</a></td>
                                  </tr>
                                @endforeach

                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="row mb-4">
                        <div class="col-sm-12 col-md-5">
                          <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                              Showing {{($data['products']->currentpage()-1)*$data['products']->perpage()+1}} to {{$data['products']->currentpage()*$data['products']->perpage()}} of {{$data['products']->total()}} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                          <div class="d-flex justify-content-center justify-content-md-start justify-content-lg-start" id="dataTable_paginate">
                            {{$data['products']->links('pagination')}}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                 @break
                 @case($view == 'addresses')
                 @section('title', 'Addresses')
                 @if ($editModal == 'address')
                     
                 <div class="modal fade show" id="editAddress" tabindex="-1" aria-modal="true" role="dialog">
                     <div class="modal-dialog modal-dialog-centered">
                       <form class="modal-content" wire:submit.prevent="saveAddressChanges({{$editData['id']}})" enctype="multipart/form-data">
                         <div class="modal-header">
                           <h5 class="modal-title" id="exampleModalCenterTitle">Edit address</h5>
                           <button onclick="document.getElementById('editAddress').remove()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body">
     
                             <div class="mb-3">
                                 <label for="address1" class="form-label">Address 1</label>
                                 <input wire:model.defer="editData.address1" type="text" class="form-control" id="address1" required>
                              </div>

                              <div class="mb-3">
                                <label for="address2" class="form-label">Address 2</label>
                                <input wire:model.defer="editData.address2" type="text" class="form-control" id="address2">
                              </div>
                              
                               <div class="mb-3 row">
                               
                                   <div class="col-6">
     
                                    <label for="zipCode" class="form-label">Zip code</label>
                                    <input wire:model.defer="editData.zipCode" class="form-control" id="zipCode" required>
                               
                                   </div>
                                       
                                   <div class="col-6">
                                       <label for="country" class="form-label">Country</label>
                                       <select wire:model.defer="editData.country" class="form-select" id="country" required>
                                          @foreach (Countries::getList('en') as $key=> $value)
                                          <option value="{{$key}}">{{$value}}</option>
                                          @endforeach
                                       </select>
                                   </div>
                                   
                               </div>                          
     
                         </div>
                         <div class="modal-footer">
                           <button type="submit" class="btn btn-primary">Save changes</button>
                         </div>
                     </form>
                     </div>
                   </div>
                 @endif
                 <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                     <h1 class="h2">My addresses</h1>
                     <div class="btn-toolbar mb-2 mb-md-0">
                       <div class="btn-group">
                         @if (count($data['addresses']) < 3)
                         <form wire:submit.prevent="addAddress" class="me-2">
                          <button type="submit" class="btn btn-sm btn-outline-secondary">Add</button>
                          </form>
                         @endif
                           <form wire:submit.prevent="deleteAddresses" class="me-2">
                           <button type="submit" class="btn btn-sm btn-outline-secondary">Delete</button>
                           </form>
                       </div>
                     </div>
                     </div>
     
                     <div class="table-responsive">
                         <div>
                           <div class="row">
                             <div class="col-sm-12">
                               <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                                 <thead>
                                   <tr role="row">
                                     <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 3%;">
                                         @php
                                         foreach($data['addresses'] as $address){
                                           $allItems[] = $address->id;
                                         }
                                         @endphp
                                         <input class="form-check-input" type="checkbox" id="select-all" wire:model.defer="selectAll" value="{{ json_encode($allItems) }}">
                                     </th>
                                     <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 35%;">Address 1</th>
                                     <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 35%;">Address 2</th>
                                     <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 15%;">Zip code</th>
                                     <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 15%;">Country</th>
                                     <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"style="width: 3%;">Edit</th>
                                   </tr>
                                 </thead>
                                 <tbody>
     
                                     @foreach ($data['addresses'] as $address)
                                     <tr >
                                         <td><input form="deleteAddresses" class="form-check-input" type="checkbox" name="item" wire:model.defer="selectedItems" value="{{$address->id}}"></td>
                                         <td>{{$address->address1, "...",50}}</td>
                                         <td>{{$address->address2, "...",50}}</td>
                                         <td>{{$address->zip_code}}</td>
                                         <td>{{Countries::getOne($address->country, 'en');}}</td>
                                         <td><a href="#" wire:click="editAddress({{$address->id}})">Edit</a></td>
                                       </tr>
                                     @endforeach
     
                                 </tbody>
                               </table>
                             </div>
                           </div>
                         </div>
                       </div>
                      @break
            @case(Auth::user()->is_admin == 1 && $view == 'customers')
            @section('title', 'Customers')
            @if ($editModal == 'customer')
            <div class="modal fade show" id="editCustomer" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                  <form class="modal-content" wire:submit.prevent="saveCustomerEdit({{$editData['id']}})">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalCenterTitle">Edit customer</h5>
                      <button onclick="document.getElementById('editCustomer').remove()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                          
                      <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input wire:model.defer="editData.name" type="text" class="form-control" id="name" required>
                      </div>

                      <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input wire:model.defer="editData.email" type="email" class="form-control" id="email" placeholder="example@mail.com" required>
                      </div>

                      <div class="mb-3">
                        <label for="phone" class="form-label">Phone number</label>
                        <input wire:model.defer="editData.phone" type="tel" class="form-control" id="phone" placeholder="" required>
                      </div>

                      <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input wire:model.defer="editData.password" type="password" class="form-control" id="password" placeholder="Leave empty for unchanges">
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                </div>
              </div>

            @endif
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Customers</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <form class="me-2" name="deleteCustomers" wire:submit.prevent="deleteCustomers">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Delete</button>
                    </form>
                    <form wire:submit.prevent="search">
                      <input type="search" wire:model.defer="searchQuery" class="form-control form-control-sm" placeholder="Search" aria-controls="dataTable">
                    </form>
                </div>
                </div>

                <div class="table-responsive">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                      <div class="row">
                        <div class="col-sm-12 col-md-6">
                          <div class="dataTables_length" id="dataTable_length">
                            <label class="d-flex mb-3 align-items-center">
                              <span class="me-2">Show</span>
                              <select wire:model="paginateLimit" aria-controls="dataTable" class="me-2 paginateLimit custom-select custom-select-sm form-control form-control-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                              </select>
                              <span>entries</span>
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6">


                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                              <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 2%;">
                                    @php
                                        foreach($data['customers'] as $customer){
                                          $allItems[] = $customer->id;
                                        }
                                    @endphp
                                    <input class="form-check-input" type="checkbox" id="select-all" wire:model.defer="selectAll" value="{{ json_encode($allItems) }}">
                                </th>
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 15%;">Name</th>
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 20%;">Email</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 5%;">Verified</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 15%;">Phone</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 15%;">Registered at</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"style="width: 15%;">Modified at</th>
                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"style="width: 5%;">Edit</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($data['customers'] as $customer)
                                <tr>
                                  <td><input form="deleteCustomers" class="form-check-input" type="checkbox" name="item" wire:model.defer="selectedItems" value="{{$customer->id}}"></td>
                                  <td>{{$customer->name}}</td>
                                  <td>{{$customer->email}}</td>
                                  <td>
                                      @if($customer->email_verified_at == null)
                                        No
                                      @else Yes
                                      @endif
                                  </td>
                                  <td>
                                    @if(empty($customer->phone))
                                      N/A
                                    @else
                                      {{$customer->phone}}
                                    @endif
                                </td>
                                  <td>{{date('Y/m/d  h:m',strtotime($customer->created_at))}}</td>
                                  <td>{{date('Y/m/d  h:m', strtotime($customer->updated_at))}}</td>
                                  <td><a href="#" wire:click="editCustomer({{$customer->id}})">Edit</a></td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="row mb-4">
                        <div class="col-sm-12 col-md-5">
                          <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                              Showing {{($data['customers']->currentpage()-1)*$data['customers']->perpage()+1}} to {{$data['customers']->currentpage()*$data['customers']->perpage()}} of {{$data['customers']->total()}} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                          <div class="d-flex justify-content-center justify-content-md-start justify-content-lg-start" id="dataTable_paginate">
                            {{$data['customers']->links('pagination')}}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>        
            
                @break
                
        @endswitch
      </main>
    </div>
  </div>