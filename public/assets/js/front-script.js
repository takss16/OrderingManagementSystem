

// ==================== Global Variables ====================
let cartCount = 0;
let cartTotal = 0;
let quantity = 1;
let itemContainer = [];
let currentItem = { name: "", price: 0, img: "", qty: 0 };
let isSearching = false;

// ==================== Quantity Functions ====================
function updateDisplay() {
  document.getElementById("quantity").textContent = quantity;
}

function increaseQuantity() {
  quantity++;
  updateDisplay();
}

function decreaseQuantity() {
  if (quantity > 1) {
    quantity--;
    updateDisplay();
  }
}

// ==================== Cart Utilities ====================
function updateCartTotal() {
  cartTotal = itemContainer.reduce((sum, item) => sum + item.price * item.qty, 0);
  const formatted = `₱${cartTotal.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
  document.getElementById("subtotal").textContent = `SUBTOTAL: ${formatted}`;
  document.getElementById("cart-total").textContent = `SUBTOTAL: ${formatted}`;
}

// ==================== Popup Handling ====================
function openPopup(name, price, img) {
  currentItem = { name, price, img, qty: 0 };
  quantity = 1;
  updateDisplay();

  document.getElementById("popup-title").textContent = name;
  document.getElementById("MenuImage").src = img;
  document.getElementById("popup-price").textContent = `₱${price.toFixed(2)}`;
  document.getElementById("popups").classList.remove("hidden");
}

function closePopup() {
  document.getElementById("popups").classList.add("hidden");
  updateCartTotal();
  renderItems();
}

// ==================== Cart Actions ====================
function addToCart() {
  const existing = itemContainer.find(i => i.name === currentItem.name);
  if (existing) {
    existing.qty += quantity;
  } else {
    itemContainer.push({ ...currentItem, qty: quantity });
  }

  cartCount++;
  updateCartTotal();
  document.getElementById("cart").classList.remove("hidden");
  document.getElementById("cart-count").textContent = cartCount;
  closePopup();
}

function changeQuantity(name, change) {
  const item = itemContainer.find(i => i.name === name);
  if (!item) return;

  item.qty += change;
  if (item.qty < 1) {
    if (confirm(`"${item.name}" is now 0. Remove from cart?`)) {
      itemContainer = itemContainer.filter(i => i.name !== name);
    } else {
      item.qty = 1;
    }
  }

  renderItems();
  updateCartTotal();
}

function viewCart() {
  document.getElementById("popupCart").classList.remove("hidden");
  updateCartTotal();
  renderItems();
}

function closeCart() {
  document.getElementById("popupCart").classList.add("hidden");
}

// ==================== Render Cart Items ====================
function renderItems() {
  const container = document.getElementById("itemListContainer");
  container.innerHTML = itemContainer.map(item => `
    <div class="item">
      <div class="item-row">
        <img src="${item.img}" alt="${item.name}" class="item-img" />
        <div class="item-details">
          <h3>${item.name}</h3>
          <p>Price: ₱${item.price.toFixed(2)}</p>
        </div>
      </div>
      <div class="item-controls">
        <button style="font-size:20px" onclick="changeQuantity('${item.name}', -1)">–</button>
        <span>${item.qty}</span>
        <button style="font-size:20px" onclick="changeQuantity('${item.name}', 1)">+</button>
      </div>
      <hr />
    </div>
  `).join('');
}

// ==================== Menu Navigation ====================
function viewCategory(id) {
  document.getElementById('searchInput').value = '';

  document.querySelectorAll('.menu-section').forEach(section => {
    section.style.display = 'none';
  });

  const selected = document.getElementById('category-' + id);
  if (selected) {
    selected.style.display = 'block';
    selected.querySelectorAll('.menu-item').forEach(item => {
      item.style.display = 'flex';
    });
  }

  document.querySelectorAll('.removeUnderline').forEach(tab => tab.classList.remove('active'));
  const activeTab = document.getElementById('activateCategory' + id);
  if (activeTab) activeTab.classList.add('active');

  document.getElementById('noResultsMsg').style.display = 'none';
}

// ==================== Place Order ====================
function placeOrder(event) {
  if (itemContainer.length === 0) {
    alert("🛒 Your cart is empty.");
    event.preventDefault();
    return;
  }

  const customerName = sessionStorage.getItem("userName") || "Guest";
  document.getElementById("formCustomerName").value = customerName;
  document.getElementById("formItems").value = JSON.stringify(itemContainer);
  document.getElementById("formTotal").value = cartTotal.toFixed(2);

  cartCount = 0;
  cartTotal = 0;
  quantity = 1;
  itemContainer = [];
  currentItem = { name: "", price: 0, img: "", qty: 0 };

  document.getElementById("cart").classList.add("hidden");
  document.getElementById("popupCart").classList.add("hidden");
  document.getElementById("cart-count").textContent = cartCount;
  document.getElementById("cart-total").textContent = "₱0.00";
  document.getElementById("itemListContainer").innerHTML = "";
  document.getElementById("quantity").innerText = "1";
  document.getElementById("searchInput").value = "";

  document.getElementById("orderForm").submit();
}

// ==================== Chatbot ====================
function toggleChat() {
  const box = document.getElementById('chatbox');
  const overlay = document.getElementById('chatOverlay');
  const isOpen = box.style.display === 'flex';

  box.style.display = isOpen ? 'none' : 'flex';
  overlay.classList.toggle('active', !isOpen);
  box.style.flexDirection = 'column';
  document.getElementById('chat-messages').scrollTop = 9999;
}

function sendChat() {
  const input = document.getElementById('chat-input');
  const text = input.value.trim();
  if (text) {
    appendMessage('user', text);
    setTimeout(() => appendMessage('bot', getBotReply(text)), 300);
    input.value = '';
  }
}

function handleQuestion(text) {
  appendMessage('user', text);
  setTimeout(() => appendMessage('bot', getBotReply(text)), 300);
}

function appendMessage(type, text) {
  const container = document.getElementById('chat-messages');
  const msg = document.createElement('div');
  msg.className = `chat-msg ${type}`;
  msg.innerText = text;
  container.appendChild(msg);
  container.scrollTop = container.scrollHeight;
}

function getBotReply(q) {
  const text = q.toLowerCase();
  if (text.includes("how to order")) return "To order, tap the + button on your desired items then checkout.";
  if (text.includes("how to pay")) return "Payment options: Cash or GCash. Request bill after ordering.";
  if (text.includes("pax") || text.includes("sharing")) return "Sharing items serve 2–4 people.";
  if (text.includes("vegan")) return "Check the Vegetable category. Some drinks contain dairy.";
  if (text.includes("facebook") || text.includes("google")) return "📌 Facebook: https://www.facebook.com/share/1JkcFrcZyi/\n📍 Google Maps: https://maps.app.goo.gl/xEzo59vCZsZ3tFM8A";
  return "I'm not sure. Try tapping one of the suggestions below 👇";
}

// ==================== Search Menu Items ====================
function filterMenuItems() {
   document.getElementById('placeOrderbtn')?.classList.add('hidden');
  const query = document.getElementById('searchInput').value.trim().toLowerCase();
  const allItems = document.querySelectorAll('.menu-item');
  const allSections = document.querySelectorAll('.menu-section');
  const noResultsMsg = document.getElementById('noResultsMsg');

  let matches = 0;
  isSearching = query.length > 0;

  allItems.forEach(item => {
    const keywords = item.getAttribute('data-name') || '';
    const match = keywords.includes(query);
    item.style.display = match ? 'flex' : 'none';
    if (match) matches++;
  });

  allSections.forEach(section => {
    const hasVisibleItems = Array.from(section.querySelectorAll('.menu-item')).some(item => item.style.display !== 'none');
    section.style.display = isSearching ? (hasVisibleItems ? 'block' : 'none') : 'block';
  });

  noResultsMsg.style.display = (isSearching && matches === 0) ? 'block' : 'none';
}

// ==================== Session Setup & Default Tab ====================
window.onload = function () {
  // Load default category
  document.getElementById('placeOrderbtn')?.classList.add('hidden');
   document.getElementById('placeOrderbtn')?.classList.add('hidden');
  const firstCategorySection = document.querySelector('.menu-section');
  if (firstCategorySection) {
    const categoryId = firstCategorySection.id.replace('category-', '');
    viewCategory(categoryId);
  }

  // Load user session
  const name = sessionStorage.getItem('userName');
  if (name?.trim()) {
    document.getElementById("sessName").textContent = 'Hi, ' + name;
    document.getElementById('placeOrderbtn')?.classList.add('hidden');
  } else {
    document.getElementById('nameModal').classList.remove('hidden');
  }
};

// ==================== Save Username ====================
function saveUserName() {
  const name = document.getElementById('userNameInput').value.trim();
  if (name) {
    sessionStorage.setItem('userName', name);
    document.getElementById('nameModal').classList.add('hidden');
    document.getElementById("sessName").textContent = 'Hi, ' + name;
  } else {
    alert("Please enter your name.");
  }
}
