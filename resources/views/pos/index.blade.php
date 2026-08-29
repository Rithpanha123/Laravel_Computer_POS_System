@extends('layouts.app')

@section('title', 'POS Terminal - Computer Store')
@section('page_heading', 'Point of Sale (POS)')

@section('content')
<!-- ប្រើប្រាស់ Default TomSelect Theme ដែលមាន Background ពណ៌សពេញលេញ -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Styling TomSelect ឱ្យស្អាត និងមានផ្ទៃពណ៌ស មិនថ្លាឆ្លុះ */
    .ts-wrapper {
        width: 100% !important;
        position: relative !important;
    }
    .ts-control {
        border-radius: 0.75rem !important;
        border: 1px solid #d1d5db !important;
        background-color: #ffffff !important;
        font-size: 0.75rem !important;
        padding: 0.45rem 0.75rem !important;
        min-height: 38px !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    .ts-control input {
        font-size: 0.75rem !important;
    }
    .ts-dropdown {
        background-color: #ffffff !important; /* ដាក់ពណ៌សកុំឱ្យថ្លា */
        border-radius: 0.75rem !important;
        font-size: 0.75rem !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        z-index: 99999 !important; /* អណ្តែតពីលើគេបង្អស់ */
        margin-top: 4px !important;
    }
    .ts-dropdown .ts-dropdown-content {
        max-height: 160px !important; /* កម្រិតកម្ពស់ត្រឹម ៥ ជួរ */
        background-color: #ffffff !important;
    }
    .ts-dropdown .option {
        padding: 0.5rem 0.75rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155 !important;
    }
    .ts-dropdown .option:last-child {
        border-bottom: none !important;
    }
    .ts-dropdown .option.active, .ts-dropdown .option:hover {
        background-color: #eff6ff !important;
        color: #2563eb !important;
        font-weight: 600 !important;
    }
</style>

<div x-data="posSystem()" class="grid grid-cols-1 lg:grid-cols-12 gap-5 h-[calc(100vh-130px)]">

    <!-- ផ្នែកខាងឆ្វេង៖ បញ្ជីទំនិញ (8 Columns) -->
    <div class="lg:col-span-7 xl:col-span-8 flex flex-col space-y-3 h-full min-h-0">
        <!-- របារស្វែងរក -->
        <div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    placeholder="ស្វែងរកតាមឈ្មោះ, SKU ឬ Barcode..." 
                    class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition"
                >
            </div>
            <button 
                type="button" 
                x-show="searchQuery" 
                @click="searchQuery = ''" 
                class="text-xs text-gray-400 hover:text-gray-600 px-2 py-1"
            >
                សម្អាត
            </button>
        </div>

        <!-- Grid បង្ហាញទំនិញ -->
        <div class="flex-1 overflow-y-auto pr-1">
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3.5">
                <template x-for="product in filteredProducts" :key="product.product_id">
                    <div 
                        @click="addToCart(product)"
                        class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-400 cursor-pointer transition flex flex-col justify-between group"
                    >
                        <div>
                            <div class="w-full h-24 bg-gray-50 rounded-xl overflow-hidden mb-2 flex items-center justify-center border border-gray-100 relative">
                                <template x-if="product.image_url || product.photo || product.image">
                                    <img :src="'/storage/' + (product.image_url || product.photo || product.image)" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </template>
                                <template x-if="!product.image_url && !product.photo && !product.image">
                                    <i class="fa-solid fa-box text-2xl text-gray-300"></i>
                                </template>
                                <span class="absolute top-1.5 right-1.5 bg-slate-900/80 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">
                                    ស្តុក: <span x-text="product.stock_quantity"></span>
                                </span>
                            </div>
                            <h4 class="text-xs font-bold text-gray-800 line-clamp-1" x-text="product.product_name || product.name"></h4>
                            <span class="text-[10px] text-gray-400 font-mono" x-text="'SKU: ' + (product.sku || product.barcode || 'N/A')"></span>
                        </div>

                        <div class="mt-2.5 flex items-center justify-between pt-2 border-t border-gray-50">
                            <span class="text-xs sm:text-sm font-black text-blue-600 font-mono" x-text="'$' + parseFloat(product.selling_price || product.unit_price || product.price || 0).toFixed(2)"></span>
                            <span class="w-6 h-6 rounded-lg bg-blue-50 group-hover:bg-blue-600 text-blue-600 group-hover:text-white flex items-center justify-center text-xs transition">
                                <i class="fa-solid fa-plus"></i>
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- ផ្នែកខាងស្តាំ៖ កន្ត្រកទំនិញ និង Checkout (4 Columns) -->
    <div class="lg:col-span-5 xl:col-span-4 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col h-full min-h-0 relative">
        
        <div class="p-3.5 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
            <h3 class="font-bold text-gray-800 text-xs sm:text-sm flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping text-blue-600"></i> កន្ត្រកទំនិញ
            </h3>
            <button @click="clearCart()" class="text-xs text-rose-500 hover:underline font-semibold" x-show="cart.length > 0">
                សម្អាត
            </button>
        </div>

        <!-- បញ្ជីទំនិញក្នុង Cart -->
        <div class="flex-1 overflow-y-auto p-3 space-y-2 min-h-0">
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                    <i class="fa-solid fa-basket-shopping text-4xl mb-2 text-gray-200"></i>
                    <p class="text-xs font-medium">មិនទាន់មានទំនិញក្នុងកន្ត្រកនៅឡើយ</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex items-center justify-between p-2 bg-gray-50/70 rounded-xl border border-gray-100">
                    <div class="flex-1 pr-2 min-w-0">
                        <p class="text-xs font-bold text-gray-800 truncate" x-text="item.name"></p>
                        <p class="text-[11px] text-blue-600 font-semibold font-mono" x-text="'$' + item.price.toFixed(2) + ' x ' + item.qty + ' = $' + (item.price * item.qty).toFixed(2)"></p>
                    </div>

                    <div class="flex items-center space-x-1">
                        <button type="button" @click="updateQty(index, -1)" class="w-5 h-5 rounded bg-white border border-gray-200 flex items-center justify-center text-xs text-gray-600 hover:bg-gray-100">-</button>
                        <span class="text-xs font-bold w-4 text-center font-mono" x-text="item.qty"></span>
                        <button type="button" @click="updateQty(index, 1)" class="w-5 h-5 rounded bg-white border border-gray-200 flex items-center justify-center text-xs text-gray-600 hover:bg-gray-100">+</button>
                        <button type="button" @click="removeItem(index)" class="text-gray-300 hover:text-rose-600 ml-1 text-xs">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- ផ្ទាំងគណនាលុយ & Checkout Form -->
        <form action="{{ Route::has('sales.store') ? route('sales.store') : (Route::has('pos.store') ? route('pos.store') : url('/sales')) }}" method="POST" class="p-3.5 border-t border-gray-100 bg-gray-50/75 space-y-2.5 flex-shrink-0">
            @csrf

            <!-- Hidden Inputs សម្រាប់ Submit Items -->
            <template x-for="(item, index) in cart" :key="item.id">
                <div>
                    <input type="hidden" :name="'items['+index+'][id]'" :value="item.id">
                    <input type="hidden" :name="'items['+index+'][qty]'" :value="item.qty">
                    <input type="hidden" :name="'items['+index+'][price]'" :value="item.price">
                </div>
            </template>

            <!-- Customer Select -->
            <div>
                <label class="block text-[11px] font-medium text-gray-500 mb-1">អតិថិជន (Customer)</label>
                <select id="customerSelectInput" name="customer_id" placeholder="ស្វែងរកអតិថិជន..." autocomplete="off">
                    <option value="">-- អតិថិជនទូទៅ (Walk-in Customer) --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->customer_id }}">
                            {{ $customer->customer_name ?? $customer->name }} ({{ $customer->phone ?? 'គ្មានលេខ' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block text-[11px] font-medium text-gray-500 mb-1">វិធីសាស្ត្រទូទាត់</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center justify-center gap-1.5 p-1.5 bg-white border border-gray-200 rounded-xl cursor-pointer text-xs font-semibold text-gray-700 hover:border-blue-500">
                        <input type="radio" name="payment_method" value="Cash" checked>
                        <span>សាច់ប្រាក់ (Cash)</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-1.5 bg-white border border-gray-200 rounded-xl cursor-pointer text-xs font-semibold text-gray-700 hover:border-blue-500">
                        <input type="radio" name="payment_method" value="KHQR">
                        <span>ABA KHQR</span>
                    </label>
                </div>
            </div>

            <!-- គណនាប្រាក់ -->
            <div class="space-y-1 text-xs text-gray-600">
                <div class="flex justify-between">
                    <span>សរុប (Subtotal):</span>
                    <span class="font-bold text-gray-800 font-mono" x-text="'$' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>បញ្ចុះតម្លៃ ($):</span>
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0"
                        name="discount_amount" 
                        x-model.number="discount" 
                        @input="syncPaid()"
                        class="w-20 px-2 py-0.5 bg-white border border-gray-200 rounded-lg text-right font-mono text-xs outline-none focus:ring-1 focus:ring-blue-500"
                    >
                </div>
                <div class="flex justify-between text-sm font-black text-gray-800 pt-1.5 border-t border-gray-200">
                    <span>សរុបត្រូវបង់ (Total):</span>
                    <span class="text-blue-600 font-mono text-base" x-text="'$' + total.toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <span class="font-semibold text-gray-700">ប្រាក់ទទួល ($):</span>
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0"
                        name="paid_amount" 
                        x-model.number="paidAmount" 
                        required 
                        class="w-24 px-2 py-1 bg-white border border-blue-400 font-bold font-mono rounded-lg text-right text-xs focus:ring-2 focus:ring-blue-500 outline-none"
                    >
                </div>
            </div>

            <button 
                type="submit" 
                :disabled="cart.length === 0"
                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2"
            >
                <i class="fa-solid fa-circle-check"></i> គិតលុយ (Checkout)
            </button>
        </form>
    </div>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('customerSelectInput')) {
        new TomSelect('#customerSelectInput', {
            create: false,
            maxOptions: 5,
            placeholder: 'ស្វែងរកអតិថិជន...',
            allowEmptyOption: true,
            dropdownParent: 'body', // ដោះស្រាយបញ្ហាជាន់ Layout ដោយឱ្យ Dropdown អណ្តែតលើ Body
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    }
});

function posSystem() {
    return {
        products: @json($products),
        searchQuery: '',
        cart: [],
        discount: 0,
        paidAmount: 0,

        get filteredProducts() {
            if (!this.searchQuery) return this.products;
            let q = this.searchQuery.toLowerCase().trim();
            return this.products.filter(p => {
                let name = (p.product_name || p.name || '').toLowerCase();
                let sku = (p.sku || '').toLowerCase();
                let barcode = (p.barcode || '').toLowerCase();
                return name.includes(q) || sku.includes(q) || barcode.includes(q);
            });
        },

        addToCart(product) {
            let pId = product.product_id;
            let pName = product.product_name || product.name;
            let pPrice = parseFloat(product.selling_price || product.unit_price || product.price || 0);
            let pStock = parseInt(product.stock_quantity || 0);

            let existing = this.cart.find(item => item.id === pId);
            if (existing) {
                if (existing.qty < pStock) {
                    existing.qty++;
                } else {
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'អស់ស្តុក', 
                        text: `ទំនិញ "${pName}" មានក្នុងស្តុកត្រឹម ${pStock} ប៉ុណ្ណោះ!`,
                        confirmButtonColor: '#2563eb'
                    });
                }
            } else {
                if (pStock <= 0) {
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'អស់ស្តុក', 
                        text: `ទំនិញ "${pName}" អស់ពីស្តុកហើយ!`,
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }
                this.cart.push({
                    id: pId,
                    name: pName,
                    price: pPrice,
                    qty: 1,
                    maxStock: pStock
                });
            }
            this.syncPaid();
        },

        updateQty(index, change) {
            let item = this.cart[index];
            let newQty = item.qty + change;
            if (newQty > item.maxStock) {
                Swal.fire({ 
                    icon: 'warning', 
                    title: 'លើសចំនួនស្តុក', 
                    text: `ទំនិញ "${item.name}" មានក្នុងស្តុកត្រឹម ${item.maxStock} ប៉ុណ្ណោះ!`,
                    confirmButtonColor: '#2563eb'
                });
                return;
            }
            if (newQty <= 0) {
                this.removeItem(index);
            } else {
                item.qty = newQty;
            }
            this.syncPaid();
        },

        removeItem(index) {
            this.cart.splice(index, 1);
            this.syncPaid();
        },

        clearCart() {
            this.cart = [];
            this.discount = 0;
            this.paidAmount = 0;
        },

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        get total() {
            let t = this.subtotal - (parseFloat(this.discount) || 0);
            return t > 0 ? t : 0;
        },

        syncPaid() {
            this.paidAmount = parseFloat(this.total.toFixed(2));
        }
    }
}
</script>
@endsection