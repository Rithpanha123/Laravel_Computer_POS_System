@extends('layouts.app')

@section('title', 'New Purchase Order - POS System')
@section('page_heading', 'New Stock Purchase')

@section('content')
<!-- Custom CSS for specialized animations, animated gradients, and dark mode compatibility with Tom-Select -->
<style>
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animated-gradient-bg {
        background: linear-gradient(-45deg, rgba(59, 130, 246, 0.08), rgba(99, 102, 241, 0.08), rgba(139, 92, 246, 0.08), rgba(236, 72, 153, 0.08));
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }
    .dark .animated-gradient-bg {
        background: linear-gradient(-45deg, rgba(59, 130, 246, 0.04), rgba(99, 102, 241, 0.04), rgba(139, 92, 246, 0.04), rgba(236, 72, 153, 0.04));
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }
    @keyframes floating {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    .floating-particle {
        animation: floating 6s ease-in-out infinite;
    }
    .card-3d-hover {
        transition: transform 0.3s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.3s ease;
    }
    .card-3d-hover:hover {
        transform: translateY(-3px) rotateX(0.5deg) rotateY(-0.5deg);
        box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.15);
    }
    .glow-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glow-card:hover {
        box-shadow: 0 0 25px -5px rgba(59, 130, 246, 0.2);
    }
    .magnetic-btn {
        transition: transform 0.2s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.2s ease;
    }
    .magnetic-btn:hover {
        transform: translateY(-2px);
    }

    /* Tom-Select Dark Mode Overrides */
    .dark .ts-control {
        background-color: #0f172a !important;
        border: 1px solid #334155 !important;
        color: #f8fafc !important;
    }
    .dark .ts-dropdown {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #f8fafc !important;
    }
    .dark .ts-dropdown .option {
        color: #cbd5e1 !important;
    }
    .dark .ts-dropdown .active {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }
</style>

<div x-data="purchaseSystem()" class="max-w-5xl mx-auto space-y-6 relative p-4 sm:p-6 rounded-3xl animated-gradient-bg">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/2 -right-10 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <div class="flex items-center justify-between relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">បង្កើតប័ណ្ណទិញទំនិញចូលស្តុក (Purchase Order)</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">កត់ត្រាទំនិញនាំចូលពីអ្នកផ្គត់ផ្គង់ និងធ្វើបច្ចុប្បន្នភាពតម្លៃដើមស្តុក</p>
        </div>
        <a href="{{ route('purchases.index') }}" class="magnetic-btn px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 rounded-2xl relative z-10">
            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold text-xs mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> សូមពិនិត្យមើលកំហុសខាងក្រោម៖
            </div>
            <ul class="list-disc list-inside text-xs text-rose-600 dark:text-rose-300 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('purchases.store') }}" method="POST" class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 sm:p-8 space-y-6 relative z-10">
        @csrf

        <!-- General Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-100 dark:border-slate-700">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                        អ្នកផ្គត់ផ្គង់ (Supplier) <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[11px] text-gray-400 dark:text-gray-500">វាយស្វែងរកឈ្មោះ/លេខ</span>
                </div>
                <select id="supplier_select" name="supplier_id" required>
                    <option value="">-- ស្វែងរក ឬជ្រើសរើសអ្នកផ្គត់ផ្គង់ --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->supplier_id ?? $supplier->id }}" {{ old('supplier_id') == ($supplier->supplier_id ?? $supplier->id) ? 'selected' : '' }}>
                            {{ $supplier->supplier_name ?? $supplier->name }} ({{ $supplier->phone ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កាលបរិច្ឆេទបញ្ជាទិញ <span class="text-rose-500">*</span></label>
                <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <!-- Add Items Section -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">មុខទំនិញបញ្ជាទិញ (Order Items)</h3>
                <button type="button" @click="addItem()" class="magnetic-btn px-3 py-1.5 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/50 transition inline-flex items-center gap-1 border border-blue-100 dark:border-blue-900">
                    <i class="fa-solid fa-plus"></i> បន្ថែមទំនិញ
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="item.uid">
                    <div class="grid grid-cols-12 gap-3 items-center bg-gray-50 dark:bg-slate-900/50 p-3 rounded-2xl border border-gray-100 dark:border-slate-700 transition">
                        <div class="col-span-12 sm:col-span-5">
                            <select 
                                :name="'items['+index+'][id]'" 
                                x-model="item.id" 
                                :id="'prod_select_' + item.uid"
                                required
                            >
                                <option value="">-- ស្វែងរក ឬជ្រើសរើសទំនិញ --</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->product_id }}" data-cost="{{ $prod->cost_price }}">
                                        {{ $prod->product_name }} ({{ $prod->sku ?? 'No SKU' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4 sm:col-span-2">
                            <label class="block sm:hidden text-[10px] text-gray-400 font-bold mb-1">ចំនួន</label>
                            <input type="number" min="1" :name="'items['+index+'][qty]'" x-model.number="item.qty" @input="syncPaid()" placeholder="Qty" required class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-center font-bold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="col-span-4 sm:col-span-2">
                            <label class="block sm:hidden text-[10px] text-gray-400 font-bold mb-1">តម្លៃដើម ($)</label>
                            <input type="number" step="0.01" min="0" :name="'items['+index+'][cost]'" x-model.number="item.cost" @input="syncPaid()" placeholder="Cost" required class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-right font-bold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="col-span-3 sm:col-span-2 text-right">
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400" x-text="'$' + ((item.qty || 0) * (item.cost || 0)).toFixed(2)"></span>
                        </div>
                        <div class="col-span-1 text-center">
                            <button type="button" @click="removeItem(index)" class="p-1.5 text-rose-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition" title="លុបជួរ">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Financial Breakdown & Notes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-slate-700">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កំណត់សម្គាល់ (Notes / Remarks)</label>
                <textarea name="notes" rows="4" placeholder="កត់ត្រាបន្ថែមលើប័ណ្ណទិញទំនិញ ឬលេខវិក្កយបត្រអ្នកផ្គត់ផ្គង់..." class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="space-y-3 bg-gray-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 text-xs">
                <div class="flex justify-between font-medium text-gray-600 dark:text-gray-400">
                    <span>សរុបបឋម (Subtotal):</span>
                    <span class="font-bold text-gray-800 dark:text-gray-200" x-text="'$' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">បញ្ចុះតម្លៃ ($ Discount):</span>
                    <input type="number" step="0.01" min="0" name="discount" x-model.number="discount" @input="syncPaid()" class="w-28 px-3 py-1.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-right text-xs font-bold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-400">ពន្ធ ($ Tax):</span>
                    <input type="number" step="0.01" min="0" name="tax" x-model.number="tax" @input="syncPaid()" class="w-28 px-3 py-1.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-right text-xs font-bold text-gray-800 dark:text-gray-200 outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-between text-sm font-black text-gray-800 dark:text-white pt-2 border-t border-gray-200 dark:border-slate-700">
                    <span>ទឹកប្រាក់សរុប (Total Amount):</span>
                    <span class="text-blue-600 dark:text-blue-400 text-base" x-text="'$' + total.toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="font-semibold text-gray-700 dark:text-gray-300">បានទូទាត់ ($ Paid Amount):</span>
                    <input type="number" step="0.01" min="0" name="paid_amount" x-model.number="paidAmount" required class="w-28 px-3 py-1.5 bg-white dark:bg-slate-900 border border-blue-400 dark:border-blue-600 font-bold text-emerald-600 dark:text-emerald-400 rounded-lg text-right text-xs outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end space-x-3">
            <a href="{{ route('purchases.index') }}" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">បោះបង់</a>
            <button type="submit" :disabled="items.length === 0" class="magnetic-btn px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-floppy-disk"></i> រក្សាទុកប័ណ្ណបញ្ជាទិញ
            </button>
        </div>
    </form>

</div>

<!-- Tom Select CDN -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<style>
    .ts-control {
        background-color: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.75rem !important;
        box-shadow: none !important;
    }
    .ts-control.focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        font-size: 0.75rem !important;
        z-index: 50 !important;
    }
    .ts-dropdown .ts-dropdown-content {
        max-height: 180px !important;
        overflow-y: auto !important;
    }
    .ts-dropdown .option {
        padding: 8px 12px !important;
    }
    .ts-dropdown .active {
        background-color: #eff6ff !important;
        color: #2563eb !important;
        font-weight: 600;
    }
</style>

<script>
function purchaseSystem() {
    return {
        items: [],
        discount: 0,
        tax: 0,
        paidAmount: 0,
        tomInstances: {},

        init() {
            // Setup Supplier Select
            new TomSelect('#supplier_select', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                maxOptions: 50,
                placeholder: "-- វាយស្វែងរក ឬជ្រើសរើសអ្នកផ្គត់ផ្គង់ --",
                allowEmptyOption: true,
            });

            // Add first item by default
            this.addItem();
        },

        addItem() {
            let uid = Date.now() + Math.random().toString(36).substring(2, 7);
            this.items.push({ uid: uid, id: '', qty: 1, cost: 0 });

            this.$nextTick(() => {
                let el = document.getElementById('prod_select_' + uid);
                if (el) {
                    let ts = new TomSelect(el, {
                        create: false,
                        sortField: { field: "text", direction: "asc" },
                        maxOptions: 50,
                        placeholder: "-- វាយស្វែងរកទំនិញ --",
                        allowEmptyOption: true,
                        onChange: (val) => {
                            let selectedOpt = el.querySelector(`option[value="${val}"]`);
                            let cost = selectedOpt ? parseFloat(selectedOpt.getAttribute('data-cost') || 0) : 0;
                            let target = this.items.find(i => i.uid === uid);
                            if (target) {
                                target.id = val;
                                target.cost = cost;
                                this.syncPaid();
                            }
                        }
                    });
                    this.tomInstances[uid] = ts;
                }
            });
        },

        removeItem(index) {
            let removed = this.items.splice(index, 1)[0];
            if (removed && this.tomInstances[removed.uid]) {
                this.tomInstances[removed.uid].destroy();
                delete this.tomInstances[removed.uid];
            }
            this.syncPaid();
        },

        get subtotal() {
            return this.items.reduce((sum, item) => sum + ((item.qty || 0) * (item.cost || 0)), 0);
        },

        get total() {
            let t = (this.subtotal - (this.discount || 0)) + (this.tax || 0);
            return t > 0 ? t : 0;
        },

        syncPaid() {
            this.paidAmount = parseFloat(this.total.toFixed(2));
        }
    }
}
</script>
@endsection