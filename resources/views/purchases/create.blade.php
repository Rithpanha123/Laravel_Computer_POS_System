@extends('layouts.app')

@section('title', 'New Purchase Order - POS System')
@section('page_heading', 'New Stock Purchase')

@section('content')
<div x-data="purchaseSystem()" class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Create Purchase Order</h2>
            <p class="text-xs sm:text-sm text-gray-500">Record stock intake from suppliers and adjust inventory cost.</p>
        </div>
        <a href="{{ route('purchases.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <form action="{{ route('purchases.store') }}" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <!-- General Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-100">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Supplier <span class="text-rose-500">*</span></label>
                <select name="supplier_id" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
    <option value="">Select Supplier</option>
    @foreach($suppliers as $supplier)
        <option value="{{ $supplier->supplier_id ?? $supplier->id }}">
            {{ $supplier->supplier_name ?? $supplier->name }} ({{ $supplier->phone ?? 'N/A' }})
        </option>
    @endforeach
</select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Purchase Date <span class="text-rose-500">*</span></label>
                <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <!-- Add Items Section -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Order Items</h3>
                <button type="button" @click="addItem()" class="px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-xl hover:bg-blue-100 transition">
                    + Add Item
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-3 items-center bg-gray-50 p-3 rounded-2xl border border-gray-100">
                        <div class="col-span-5">
                            <select :name="'items['+index+'][id]'" x-model="item.id" @change="onProductChange(index)" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none">
                                <option value="">Select Product</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->product_id }}" data-cost="{{ $prod->cost_price }}">{{ $prod->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="number" min="1" :name="'items['+index+'][qty]'" x-model.number="item.qty" placeholder="Qty" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs text-center outline-none">
                        </div>
                        <div class="col-span-2">
                            <input type="number" step="0.01" min="0" :name="'items['+index+'][cost]'" x-model.number="item.cost" placeholder="Cost Price" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs text-right outline-none">
                        </div>
                        <div class="col-span-2 text-right">
                            <span class="text-xs font-bold text-gray-800" x-text="'$' + (item.qty * item.cost).toFixed(2)"></span>
                        </div>
                        <div class="col-span-1 text-center">
                            <button type="button" @click="removeItem(index)" class="text-rose-400 hover:text-rose-600 text-xs">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Financial Breakdown & Notes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Notes / Remarks</label>
                <textarea name="notes" rows="4" placeholder="Purchase notes, invoice reference, etc..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none"></textarea>
            </div>
            <div class="space-y-3 bg-gray-50 p-4 rounded-2xl border border-gray-100 text-xs">
                <div class="flex justify-between font-medium text-gray-600">
                    <span>Subtotal:</span>
                    <span class="font-bold text-gray-800" x-text="'$' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Discount ($):</span>
                    <input type="number" step="0.01" name="discount" x-model.number="discount" class="w-24 px-2 py-1 bg-white border border-gray-200 rounded-lg text-right text-xs">
                </div>
                <div class="flex justify-between text-sm font-black text-gray-800 pt-2 border-t border-gray-200">
                    <span>Total Amount:</span>
                    <span class="text-blue-600" x-text="'$' + total.toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="font-semibold text-gray-700">Paid Amount ($):</span>
                    <input type="number" step="0.01" name="paid_amount" x-model.number="paidAmount" required class="w-28 px-3 py-1.5 bg-white border border-blue-400 font-bold rounded-lg text-right text-xs">
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
            <a href="{{ route('purchases.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">Cancel</a>
            <button type="submit" :disabled="items.length === 0" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                Save Purchase Order
            </button>
        </div>
    </form>

</div>

<script>
function purchaseSystem() {
    return {
        items: [{ id: '', qty: 1, cost: 0 }],
        discount: 0,
        paidAmount: 0,

        addItem() {
            this.items.push({ id: '', qty: 1, cost: 0 });
        },
        removeItem(index) {
            this.items.splice(index, 1);
            this.syncPaid();
        },
        onProductChange(index) {
            let select = event.target;
            let cost = select.options[select.selectedIndex].getAttribute('data-cost');
            this.items[index].cost = cost ? parseFloat(cost) : 0;
            this.syncPaid();
        },
        get subtotal() {
            return this.items.reduce((sum, item) => sum + ((item.qty || 0) * (item.cost || 0)), 0);
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