<!DOCTYPE html>
<html lang="en">
<head>  
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Welcome to Frejies!</title>
  <link href="{{ asset('assets/css/front-style.css') }}" rel="stylesheet">
</head>
<body>
  <header>
    <input type="text" id="searchInput" placeholder="Search menu items..." oninput="filterMenuItems()" style="width: 100%; padding: 10px; margin-bottom: 15px;" />
   <nav>
    <span id="sessName"></span>
  @foreach($categories as $category)
    <button
      id="activateCategory{{ $category->id }}"
      onclick="viewCategory('{{ $category->id }}')"
      class="removeUnderline {{ $loop->first ? 'active' : '' }}">
      {{ $category->name }}
    </button>
  @endforeach


</nav>

  </header>

  <main id="menuContainer">
  @foreach($categories as $index => $category)
    <section 
      id="category-{{ $category->id }}" 
      class="menu-section" 
      data-category="{{ $category->name }}"
    >
      <h2>{{ $category->name }}</h2>

      @if($category->menuItems->count())
        @foreach($category->menuItems as $item)
          <div class="menu-item"
            data-name="{{ strtolower($item->name . ' ' . $item->description . ' ' . $category->name) }}"
            onclick="openPopup(`{{ $item->name }}`, {{ $item->price }}, '{{ asset('storage/' . $item->image) }}')"
          >
            <div>
              <h3>{{ $item->name }}</h3>
              <p>₱{{ number_format($item->price, 2) }}</p>
              <p>{{ $item->description }}</p>
            </div>
            @if($item->image)
              <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" />
            @endif
            <button class="add-btn">+</button>
          </div>
        @endforeach
      @else
        <p>No menu items available in this category.</p>
      @endif
    </section>
  @endforeach
</main>

<p id="noResultsMsg" style="display: none; text-align: center; color: red; font-weight: bold; margin-top: 10px;">
  🚫 No matching menu items found.
</p>

<div class="chat-launcher"  style="margin-bottom:50px !important;" onclick="toggleChat()">💬</div>
<!-- 🌫️ Overlay to disable outer page -->
<div id="chatOverlay" class="chat-overlay hidden"></div>

<div class="chatbox" id="chatbox" style="margin-bottom:60px !important;" >
    <div class="chatbox-header">Frejie's Cafe Bot</div>
    <div class="chatbox-body" id="chat-messages">
        <div class="chat-msg bot">☕ Hello dear guests, welcome to Frejie's cafe. How may I help you?</div>
    </div>
    <div class="chatbox-footer">
        <input type="text" id="chat-input" placeholder="Type your question..." onkeydown="if(event.key==='Enter') sendChat()">
        <div class="suggestions" id="suggestion-buttons">
            <button onclick="handleQuestion('How to order?')">How to order?</button>
            <button onclick="handleQuestion('How to pay?')">How to pay?</button>
            <button onclick="handleQuestion('For how pax are the Sharing food item')">For how pax are the "Sharing" food item</button>
            <button onclick="handleQuestion('Have any vegan food?')">Have any vegan food?</button>
            <button onclick="handleQuestion('Do you have any webpage?')">Do you have any webpage?</button>
        </div>
    </div>
</div>


<footer id="cart" class="cart hidden" onclick="viewCart()" style="cursor: pointer;">
  <span id="cart-count">0</span>
  <span style="margin: 0 10px;">🛒 View your cart</span>
  <span style="margin-right:50px;" id="cart-total">₱0.00</span>
</footer>

  <div id="popups" class="popups hidden">
    <div class="popup-content">
    <h3 id="popup-title"></h3>
    <p id="popup-price"></p>
    <img  class="addToCartImage" id="MenuImage" src="" alt="" />
    <div class="container">
    <button style="font-size:20px; background-color:black !important;" onclick="decreaseQuantity()">−</button>
    <span class="quantity-number" id="quantity">1</span>
    <button style="font-size:20px" onclick="increaseQuantity()">+</button>
  </div>
    
      <button style="font-size:20px" onclick="addToCart()">Add to Cart</button>
      <button style="font-size:20px" onclick="closePopup()">Cancel</button>
    </div>
  </div>


<div id="popupCart" class="popups hidden">
  <div class="popup-content">
    <div class="popup-scrollable">
      <h3 id="popupCart-title"></h3>
      <p id="popupCart-price"></p>
      <p id="cartMessage"></p>

      <div id="itemListContainer" class="container"></div>
    </div>

    <div class="popup-actions" style="margin-top: 10px;">
      <span id="subtotal" style="margin-right: 50px; font-size:18px"><b>SUBTOTAL: </b>₱0.00</span>
      <button style="font-size:25px" onclick="placeOrder()">Place Order</button>
      <button style="font-size:25px" onclick="closeCart()">Close</button>
    </div>
  </div>
</div>

<form id="orderForm" method="POST" action="{{ route('place.order') }}">
    @csrf
    <input type="hidden" name="customer_name" id="formCustomerName">
    <input type="hidden" name="cart_items" id="formItems">
    <input type="hidden" name="cart_total" id="formTotal">
    <input type="hidden" name="table_id" id="formTableId" value="{{ $table->id }}">
    <button id="placeOrderbtn" type="submit" onclick="placeOrder(event)">Place Order</button>
</form>





<div id="receipt" class="popups hidden" >
  <h3>🧾 Order Receipt</h3>
  <div id="receiptItems"></div>
  <p><strong>Subtotal:</strong> ₱<span id="receiptTotal">0.00</span></p>
  <button onclick="closeReceipt()">Close Receipt</button>
</div>

<!-- Name Input Modal -->
<div id="nameModal" class="popups">
  <div class="popup-content">
    <h3>👋 Welcome to Frejie's Cafe!</h3>
    <p>Please enter your name to continue:</p>
    <input style="font-size:20px" type="text" id="userNameInput" placeholder="Your Name..." />
    <button style=" font-size:25px; background-color: green !important;" onclick="saveUserName()">Continue</button>
  </div>
</div>

<script src="{{ asset('/assets/js/front-script.js') }}"></script>


</body>
</html>