@extends('layouts.app')

@section('title', 'POS Terminal - Computer Store')
@section('page_heading', 'Point of Sale (POS)')

@section('content')
<div x-data="posSystem()" class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-[calc(100vh-140px)]">

    <!-- ផ្នែកខាងឆ្វេង៖ បញ្ជីទំនិញ (8 Columns) -->
    <div class="lg:col-span-7 xl:col-span-8 flex flex-col space-y-4 h-full">
        <!-- របារស្វែងរក -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    placeholder="ស្វែងរកតាមឈ្មោះ, SKU ឬ Barcode..." 
                    class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none"
                >
            </div>
        </div>

        <!-- Grid បង្ហាញទំនិញ -->
        <div class="flex-1 overflow-y-auto pr-1">
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                <template x-for="product in filteredProducts" :key="product.product_id">
                    <div 
                        @click="addToCart(product)"
                        class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-400 cursor-pointer transition flex flex-col justify-between group"
                    >
                        <div>
                            <div class="w-full h-28 bg-gray-50 rounded-xl overflow-hidden mb-2 flex items-center justify-center border border-gray-100">
                                <template x-if="product.photo">
                                    <img :src="'/storage/' + product.photo" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </template>
                                <template x-if="!product.photo">
                                    <i class="fa-solid fa-box text-2xl text-gray-300"></i>
                                </template>
                            </div>
                            <h4 class="text-xs font-bold text-gray-800 line-clamp-2" x-text="product.product_name"></h4>
                            <span class="text-[10px] text-gray-400 font-mono" x-text="'SKU: ' + (product.sku || 'N/A')"></span>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-sm font-black text-blue-600" x-text="'$' + parseFloat(product.selling_price).toFixed(2)"></span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700" x-text="'ស្តុក: ' + product.stock_quantity"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- ផ្នែកខាងស្តាំ៖ កន្ត្រកទំនិញ និងផ្ទាំង Checkout (4 Columns) -->
    <div class="lg:col-span-5 xl:col-span-4 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col h-full overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping text-blue-600"></i> កន្ត្រកទំនិញ
            </h3>
            <button @click="clearCart()" class="text-xs text-rose-500 hover:underline font-semibold" x-show="cart.length > 0">
                សម្អាត
            </button>
        </div>

        <!-- បញ្ជីទំនិញក្នុង Cart -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                    <i class="fa-solid fa-basket-shopping text-4xl mb-2 text-gray-200"></i>
                    <p class="text-xs font-medium">មិនទាន់មានទំនិញក្នុងកន្ត្រកនៅឡើយ</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex items-center justify-between p-2.5 bg-gray-50/70 rounded-xl border border-gray-100">
                    <div class="flex-1 pr-2">
                        <p class="text-xs font-bold text-gray-800 line-clamp-1" x-text="item.name"></p>
                        <p class="text-[11px] text-blue-600 font-semibold" x-text="'$' + item.price.toFixed(2)"></p>
                    </div>

                    <!-- ប៊ូតុង បន្ថែម/បន្ថយ ចំនួន -->
                    <div class="flex items-center space-x-2">
                        <button @click="updateQty(index, -1)" class="w-6 h-6 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-xs text-gray-600 hover:bg-gray-100">-</button>
                        <span class="text-xs font-bold w-4 text-center" x-text="item.qty"></span>
                        <button @click="updateQty(index, 1)" class="w-6 h-6 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-xs text-gray-600 hover:bg-gray-100">+</button>
                        <button @click="removeItem(index)" class="text-rose-400 hover:text-rose-600 ml-1 text-xs">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- ផ្ទាំងគណនាលុយ & Checkout Form -->
        <form action="{{ route('pos.store') }}" method="POST" class="p-4 border-t border-gray-100 bg-gray-50/50 space-y-3">
            @csrf

            <!-- Hidden Inputs សម្រាប់ Submit Form -->
            <template x-for="(item, index) in cart" :key="item.id">
                <div>
                    <input type="hidden" :name="'items['+index+'][id]'" :value="item.id">
                    <input type="hidden" :name="'items['+index+'][qty]'" :value="item.qty">
                    <input type="hidden" :name="'items['+index+'][price]'" :value="item.price">
                </div>
            </template>

            <!-- Customer -->
            <div>
                <select name="customer_id" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">អតិថិជនទូទៅ (Walk-in Customer)</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->customer_id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                    @endforeach
                </select>
            </div>

            <!-- សរុបលុយ -->
            <div class="space-y-1.5 text-xs text-gray-600">
                <div class="flex justify-between">
                    <span>សរុប (Subtotal):</span>
                    <span class="font-bold text-gray-800" x-text="'$' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>បញ្ចុះតម្លៃ ($):</span>
                    <input type="number" step="0.01" name="discount" x-model.number="discount" class="w-20 px-2 py-1 bg-white border border-gray-200 rounded-lg text-right text-xs">
                </div>
                <div class="flex justify-between text-base font-black text-gray-800 pt-2 border-t border-gray-200">
                    <span>សរុបត្រូវបង់:</span>
                    <span class="text-blue-600" x-text="'$' + total.toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <span class="font-semibold text-gray-700">ប្រាក់ទទួល ($):</span>
                    <input type="number" step="0.01" name="paid_amount" x-model.number="paidAmount" required class="w-24 px-2 py-1.5 bg-white border border-blue-400 font-bold rounded-lg text-right text-xs focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <button 
                type="submit" 
                :disabled="cart.length === 0"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-bold text-xs rounded-xl shadow-lg shadow-blue-500/25 transition"
            >
                គិតលុយ (Checkout)
            </button>
        </form>
    </div>

</div>

<!-- Alpine.js POS Logic -->
<script>
function posSystem() {
    return {
        products: @json($products),
        searchQuery: '',
        cart: [],
        discount: 0,
        paidAmount: 0,

        get filteredProducts() {
            if (!this.searchQuery) return this.products;
            return this.products.filter(p => 
                p.product_name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                (p.sku && p.sku.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (p.barcode && p.barcode.toLowerCase().includes(this.searchQuery.toLowerCase()))
            );
        },

        addToCart(product) {
            let existing = this.cart.find(item => item.id === product.product_id);
            if (existing) {
                if (existing.qty < product.stock_quantity) {
                    existing.qty++;
                } else {
                    Swal.fire({ icon: 'warning', title: 'អស់ស្តុក', text: 'ទំនិញនេះមានត្រឹម ' + product.stock_quantity + ' ប៉ុណ្ណោះ!' });
                }
            } else {
                this.cart.push({
                    id: product.product_id,
                    name: product.product_name,
                    price: parseFloat(product.selling_price),
                    qty: 1,
                    maxStock: product.stock_quantity
                });
            }
            this.syncPaid();
        },

        updateQty(index, change) {
            let item = this.cart[index];
            let newQty = item.qty + change;
            if (newQty > item.maxStock) {
                Swal.fire({ icon: 'warning', title: 'អស់ស្តុក', text: 'ទំនិញនេះមានត្រឹម ' + item.maxStock + ' ប៉ុណ្ណោះ!' });
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
            let t = this.subtotal - (this.discount || 0);
            return t > 0 ? t : 0;
        },

        syncPaid() {
            this.paidAmount = this.total;
        }
    }
}
</script>
@endsection