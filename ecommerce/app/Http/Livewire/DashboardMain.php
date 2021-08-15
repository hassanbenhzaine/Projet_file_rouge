<?php

namespace App\Http\Livewire;

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;


class DashboardMain extends Component
{

    use WithFileUploads;

    // general variables
    public $view;
    public $paginateLimit = 10;
    public $selectAll = null;
    public $searchQuery;
    public $editModal;
    public $editData = [];
    public $selectedItems = [];
    public $modalTitle;
    public $categories;

    public $editCondition;

    // variables for orders page
    public $editStatus;
    public $editNumber;

    // variables for customers page
    public $editName;
    public $editEmail;
    public $editPhone;
    public $editPassword;


    public function editProduct($id){
        $productController = new ProductController;
        $product = $productController->productWithCategory($id);

        $this->editData['title'] = $product->title;
        $this->editData['description'] = $product->description;
        $this->editData['price'] = $product->price;
        $this->editData['category'] = $product->name;
        $this->editData['categoryId'] = $product->category_id;
        $this->editData['photos'] = null;
        $this->editData['id'] = $product->productId;
        $this->editData['condition'] = $product->condition;
    
        $this->modalTitle = 'Edit product';
        $this->editModal = 'product';
    }

    public function addProduct(){
        $this->reset(['editData']);
        $this->editData['photos'] = null;
        $this->editData['id'] = null;
        $this->modalTitle = 'Add product';
        $this->editModal = 'product';
    }

    public function addAddress(){

        $this->reset(['editData']);
        $this->editData['id'] = null;
        $this->editModal = 'address';
    }
    
    public function editOrder($number){
        $this->reset(['editData']);

        $orderController = new OrderController;
        $order = $orderController->show($number);
        $this->editData['status'] = $order[0]->status;
        $this->editData['orderNumber'] = $order[0]->number;

        $this->editModal = 'order';
    }

    public function cancelOrder($number){

        $orderController = new OrderController;
        $orderController->cancel($number);

    }
    
    public function undoOrderCancel($number){

        $orderController = new OrderController;
        $orderController->undoCancel($number);

    }

    public function editAddress($id){
        $this->reset(['editData']);

        $addressController = new AddressController;
        $address = $addressController->show($id);
        $this->editData['id'] = $address->id;
        $this->editData['address1'] = $address->address1;
        $this->editData['address2'] = $address->address2;
        $this->editData['zipCode'] = $address->zip_code;
        $this->editData['country'] = $address->country;

        $this->editModal = 'address';
    }

    public function editCustomer($id){
        $this->reset(['editData']);

        $userController = new UserController;
        $customers = $userController->showCustomer($id);

        $this->editData['name'] = $customers->name;
        $this->editData['email'] = $customers->email;
        $this->editData['phone'] = $customers->phone;
        $this->editData['id'] = $id;
        $this->editData['password'] = null;

        $this->editModal = 'customer';
    }

    public function search(){
        
    }

    public function deleteProducts(){

        $productController = new ProductController;

        if(!empty($this->selectedItems)){
            $productController->destroy($this->selectedItems);
            $this->selectedItems = [];

        } else{
            
            $selectedAll = json_decode($this->selectAll);
            $productController->destroy($selectedAll);
        }
        
     
    }

    public function deleteAddresses(){
        

        if(!empty($this->selectedItems)){
            $addressController = new AddressController;
            $addressController->destroy($this->selectedItems);
            $this->selectedItems = [];

        } else{
            $addressController = new AddressController;
            $selectedAll = json_decode($this->selectAll);
            $addressController->destroy($selectedAll);
        }
        
     
    }

    public function deleteOrders(){

        $orderController = new OrderController;

        if(!empty($this->selectedItems)){
            $orderController->destroy($this->selectedItems);
            $this->selectedItems = [];

        } else{
            $selectedAll = json_decode($this->selectAll);
            $orderController->destroy($selectedAll);
        }
        
    }
    

    public function deleteCustomers(){

        $userController = new UserController;

        if(!empty($this->selectedItems)){
            $userController->destroy($this->selectedItems);
            $this->customers = [];

        } else{
            $selectedAll = json_decode($this->selectAll);
            $userController->destroy($selectedAll);
        }
        
    }

    public function render()
    {
        $data = null;

        if($this->view == "products"){
            $productController = new ProductController;
            $data['products'] = $productController->productsWithCategories($this->paginateLimit, $this->searchQuery);

            $this->selectedItems = [];
        }

        if($this->view == "orders"){
            if(Auth::user()->is_admin == 1){
            $orderController = new OrderController;
            $data['orders'] = $orderController->ordersWithUserAndProducts($this->paginateLimit, $this->searchQuery);
            } else{
                $orderController = new OrderController;
                $data['orders'] = $orderController->customerOrdersWithProducts($this->paginateLimit, $this->searchQuery);
            }

            $this->selectedItems = [];
        }

        if($this->view == "customers"){
            $userController = new UserController;
            $data['customers'] = $userController->customers($this->paginateLimit, $this->searchQuery);

            $this->selectedItems = [];
        }

        if($this->view == "home"){
            $statistics = new StatisticController;
            $data['ordersPerMonth'] = $statistics->OrdersPerMonth();
        }
        
        if($this->view == "addresses"){
            $addressController = new AddressController;
            $data['addresses'] = $addressController->customerAddresses();

            $this->selectedItems = [];
        }
 
        if($this->editModal == 'product'){
            $categoryController = new CategoryController;
            $this->categories  = $categoryController->index(null);

        }

        return view('livewire.dashboard-main', ['data' => $data]);
    }

    public function orders(){
        $this->view = "orders";
    }

    public function home(){
        $this->view = "home";
    }

    public function products(){
        $this->view = "products";
    }

    public function customers(){
        $this->view = "customers";
    }

    public function saveProductChanges($productId = null){
        
        if($productId == null){
            $productController = new ProductController;
            $productController->add($this->editData['title'], $this->editData['description'], $this->editData['price'], $this->editData['categoryId'], $this->editData['photos'], $this->editData['condition']);
        }else{
            $productController = new ProductController;
            $productController->edit($productId, $this->editData['title'], $this->editData['description'], $this->editData['price'], $this->editData['categoryId'], $this->editData['photos'], $this->editData['condition']);
        }

        $this->editModal = null;
    }

    public function saveAddressChanges($addressId = null){
        
        if($addressId == null){
            $addressController = new AddressController;
            $addressController->add($this->editData['address1'], $this->editData['address2'], $this->editData['zipCode'], $this->editData['country']);
        }else{
            $addressController = new AddressController;
            $addressController->edit($addressId, $this->editData['address1'], $this->editData['address2'], $this->editData['zipCode'], $this->editData['country']);
        }

        $this->editModal = null;
    }

    public function saveOrderEdit($orderNumber){
        $orderController = new OrderController;
        $orderController->edit($orderNumber, $this->editData['status']);

        $this->editModal = null;
    }

    public function saveCustomerEdit($id){

        $userController = new UserController;
        $userController->edit($id, $this->editData['name'], $this->editData['email'], $this->editData['phone'], $this->editData['password']);

        $this->editModal = null;
    }
}
