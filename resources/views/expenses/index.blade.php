@extends('layouts.app')

@section('title', 'Expense Tracking - POS System')
@section('page_heading', 'Store Expense Management')

@section('content')
<!-- Custom CSS for specialized animations, floating particles, and interactions -->
<style>
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animated-gradient-bg {
        background: linear-gradient(-45deg, rgba(59, 130, 246, 0.06), rgba(99, 102, 241, 0.06), rgba(139, 92, 246, 0.06), rgba(236, 72, 153, 0.06));
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }
    .dark .animated-gradient-bg {
        background: linear-gradient(-45deg, rgba(59, 130, 246, 0.03), rgba(99, 102, 241, 0.03), rgba(139, 92, 246, 0.03), rgba(236, 72, 153, 0.03));
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
        box-shadow: 0 20px 40px -15px rgba(225, 29, 72, 0.15);
    }
    .glow-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glow-card:hover {
        box-shadow: 0 0 25px -5px rgba(225, 29, 72, 0.2);
    }
    .magnetic-btn {
        transition: transform 0.2s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.2s ease;
    }
    .magnetic-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div x-data="expenseManager()" class="space-y-6 p-4 sm:p-6 rounded-3xl animated-gradient-bg relative overflow-hidden">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-rose-500/10 dark:bg-rose-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/2 -right-10 w-72 h-72 bg-amber-500/10 dark:bg-amber-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">ការគ្រប់គ្រងការចំណាយ (Expense Tracking)</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">កត់ត្រានិងតាមដានរាល់ការចំណាយប្រតិបត្តិការ ថ្លៃជួល ទឹកភ្លើង និងប្រាក់ខែ</p>
        </div>
        <button @click="openCreateModal()" class="magnetic-btn inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-lg shadow-rose-500/25 transition">
            <i class="fa-solid fa-plus mr-2"></i> កត់ត្រាការចំណាយថ្មី
        </button>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 relative z-10">
        
        <!-- Today Expense -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">ចំណាយថ្ងៃនេះ (Today)</p>
                <p class="text-2xl font-black text-gray-800 dark:text-white mt-1">${{ number_format($todayExpense ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center text-xl border border-rose-100 dark:border-rose-900 shadow-inner">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
        </div>

        <!-- This Month Expense -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">ចំណាយខែនេះ (This Month)</p>
                <p class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1">${{ number_format($monthExpense ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center text-xl border border-amber-100 dark:border-amber-900 shadow-inner">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>

        <!-- Total Expense -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">ចំណាយសរុប (Total)</p>
                <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1">${{ number_format($totalExpense ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center text-xl border border-indigo-100 dark:border-indigo-900 shadow-inner">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

    </div>

    <!-- Filter Bar -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm relative z-10">
        <form id="filterForm" method="GET" action="{{ route('expenses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Hidden per_page input -->
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">

            <!-- Search Text -->
            <div class="md:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="ស្វែងរកតាមការពិពណ៌នា..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">គ្រប់ប្រភេទចំណាយទាំងអស់</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- From Date -->
            <div>
                <input 
                    type="date" 
                    name="from_date" 
                    value="{{ request('from_date') }}" 
                    class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    title="ពីថ្ងៃ"
                >
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="magnetic-btn w-full py-2 bg-slate-900 hover:bg-slate-800 dark:bg-rose-600 dark:hover:bg-rose-700 text-white text-xs font-semibold rounded-xl transition shadow-md">
                    ស្វែងរក
                </button>
                @if(request()->hasAny(['search', 'category', 'from_date', 'to_date', 'per_page']))
                    <a href="{{ route('expenses.index') }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl text-xs flex items-center justify-center transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Expenses Table Card -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">កាលបរិច្ឆេទ</th>
                        <th class="py-3.5 px-6">ប្រភេទចំណាយ</th>
                        <th class="py-3.5 px-6">ការរៀបរាប់ / បរិយាយ</th>
                        <th class="py-3.5 px-6">អ្នកកត់ត្រា</th>
                        <th class="py-3.5 px-6">ចំនួនទឹកប្រាក់</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($expenses as $expense)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition">
                        <td class="py-3.5 px-6">
                            <span class="font-semibold text-gray-800 dark:text-white">{{ optional($expense->expense_date)->format('d M Y') }}</span>
                            <span class="block text-[11px] text-gray-400">{{ optional($expense->expense_date)->format('h:i A') }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-100 dark:border-rose-900">
                                {{ $expense->category }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 max-w-xs text-gray-600 dark:text-gray-400">
                            {{ $expense->description ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 dark:text-gray-400">
                            {{ $expense->user->full_name ?? $expense->user->username ?? 'Staff' }}
                        </td>
                        <td class="py-3.5 px-6 font-bold text-rose-600 dark:text-rose-400 text-sm">
                            ${{ number_format($expense->amount, 2) }}
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <button 
                                type="button"
                                @click="openEditModal({{ json_encode($expense) }})" 
                                class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-xl transition inline-flex items-center" 
                                title="កែប្រែ"
                            >
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="button" 
                                    onclick="confirmDeleteExpense(this, '{{ $expense->category }} - ${{ number_format($expense->amount, 2) }}')" 
                                    class="p-2 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl transition inline-flex items-center" 
                                    title="លុប"
                                >
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-receipt text-3xl text-gray-300 dark:text-slate-600 mb-2"></i>
                            <p class="text-sm font-medium">មិនទាន់មានទិន្នន័យចំណាយនៅឡើយ</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Styled Pagination Footer with Per Page Dropdown -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-800 dark:text-gray-200">{{ $expenses->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-800 dark:text-gray-200">{{ $expenses->lastItem() ?? 0 }}</span> នៃទិន្នន័យចំណាយសរុប <span class="font-bold text-gray-800 dark:text-gray-200">{{ $expenses->total() }}</span> ជួរ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Selector -->
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">បង្ហាញ៖</span>
                    <select 
                        id="perPageSelectDropdown" 
                        onchange="changePerPage(this.value)" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 dark:text-white outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 dark:text-gray-400 font-medium">ជួរ</span>
                </div>

                <!-- Page Links -->
                <div>
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Expense Modal -->
    <div 
        x-show="showModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
    >
        <div 
            @click.away="showModal = false" 
            class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-2xl max-w-lg w-full p-6 sm:p-8 space-y-6"
        >
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-4">
                <h3 class="text-base font-bold text-gray-800 dark:text-white" x-text="isEdit ? 'កែប្រែការចំណាយ' : 'កត់ត្រាការចំណាយថ្មី'"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="formUrl" method="POST" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ប្រភេទចំណាយ <span class="text-rose-500">*</span></label>
                    <select name="category" x-model="formData.category" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ចំនួនទឹកប្រាក់ ($) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="amount" x-model="formData.amount" placeholder="0.00" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កាលបរិច្ឆេទចំណាយ <span class="text-rose-500">*</span></label>
                    <input type="date" name="expense_date" x-model="formData.expense_date" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ការរៀបរាប់ / បរិយាយ</label>
                    <textarea name="description" x-model="formData.description" rows="3" placeholder="បញ្ជាក់ពីមូលហេតុចំណាយ ឬលេខវិក្កយបត្រចំណាយ..." class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end space-x-3">
                    <button type="button" @click="showModal = false" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">បោះបង់</button>
                    <button type="submit" class="magnetic-btn px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-rose-500/25 transition">
                        រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function changePerPage(value) {
    document.getElementById('formPerPageInput').value = value;
    document.getElementById('filterForm').submit();
}

function expenseManager() {
    return {
        showModal: false,
        isEdit: false,
        formUrl: '{{ route('expenses.store') }}',
        formData: {
            category: '',
            amount: '',
            expense_date: '{{ date('Y-m-d') }}',
            description: ''
        },
        openCreateModal() {
            this.isEdit = false;
            this.formUrl = '{{ route('expenses.store') }}';
            this.formData = {
                category: '',
                amount: '',
                expense_date: '{{ date('Y-m-d') }}',
                description: ''
            };
            this.showModal = true;
        },
        openEditModal(item) {
            this.isEdit = true;
            this.formUrl = `/expenses/${item.id}`;
            this.formData = {
                category: item.category,
                amount: item.amount,
                expense_date: item.expense_date ? item.expense_date.substring(0, 10) : '{{ date('Y-m-d') }}',
                description: item.description || ''
            };
            this.showModal = true;
        }
    }
}

function confirmDeleteExpense(button, expenseInfo) {
    Swal.fire({
        title: '<span class="text-xl font-bold text-gray-800 dark:text-white">តើអ្នកពិតជាចង់លុបការចំណាយនេះមែនទេ?</span>',
        html: `
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                អ្នកកំពុងស្នើសុំលុបកំណត់ត្រាចំណាយ: <strong class="text-rose-600 dark:text-rose-400 font-bold">${expenseInfo}</strong><br>
                <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">⚠️ ទិន្នន័យដែលលុបហើយមិនអាចយកមកវិញបានទេ!</span>
            </div>
        `,
        icon: 'warning',
        iconColor: '#e11d48',
        showCancelButton: true,
        confirmButtonText: 'បាទ/ចាស, លុបឥឡូវនេះ',
        cancelButtonText: 'ថយក្រោយ',
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white',
            confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-bold shadow-lg shadow-rose-500/30',
            cancelButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold hover:bg-slate-600 transition'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
@endsection