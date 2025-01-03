         <!-- User details -->
         <div class="user-profile text-center mt-3">
             <div class="">
     
             </div>
             <div class="mt-3">
                 <h4 class="font-size-16 mb-1">{{ Auth::user()->name }} </h4>
                 <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                     Online</span>
             </div>
         </div>

         <!--- Sidemenu -->
         <div id="sidebar-menu">
             <!-- Left Menu Start -->
             <ul class="metismenu list-unstyled" id="side-menu">
                 <li class="menu-title">Menu</li>

                 <li>
                     <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                         <i class="ri-dashboard-line"></i>
                         <span>Dashboard</span>
                     </a>
                 </li>

                 
                 <li>
                     <a href="{{ route('subcategories.index') }}" class=" waves-effect">
                         <i class="ri-checkbox-multiple-blank-fill"></i>
                         <span> Category </span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('product.index') }}" class=" waves-effect">
                        <i class="ri-product-hunt-fill"></i>
                         <span> Products </span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('new_arrivals.index') }}" class=" waves-effect">
                        <i class="ri-product-hunt-fill"></i>
                         <span> New Arrival Products </span>
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('featured_products.index') }}" class=" waves-effect">
                        <i class="ri-product-hunt-fill"></i>
                         <span> Featured Products </span>
                     </a>
                 </li>

                          
                 <li>
                    <a href="{{ route('exhibitions.index') }}" class=" waves-effect">
                        <i class="ri-checkbox-multiple-blank-fill"></i>
                        <span> Exhibtion </span>
                    </a>
                </li>

                 <li>
                     <a href="{{ route('orders.index') }}" class=" waves-effect">
                        <i class="ri-shopping-bag-fill"></i>
                         <span> Orders </span>
                     </a>
                 </li>

                 <li>
                     <a href="{{ route('shipping.create') }}" class=" waves-effect">
                        <i class="ri-truck-fill"></i>
                         <span> Shipping </span>
                     </a>
                 </li>
             </ul>
         </div>
         <!-- Sidebar -->
