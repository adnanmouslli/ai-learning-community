@extends('layouts.app')

@section('title', 'إدارة المنافسات')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">إدارة المنافسات</h1>
        <a href="{{ route('admin.competitions.create') }}" class="bg-green-600 py-2 px-4 rounded-lg text-white text-sm hover:bg-green-700">
            منافسة جديدة
        </a>
    </div>
    
    <!-- تصفية المنافسات -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-4">
        <form action="{{ route('admin.competitions.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="w-full md:w-64">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                <input type="text" name="search" id="search" placeholder="ابحث عن منافسة..." value="{{ request('search') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            
            <div class="w-full md:w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">الكل</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>قادمة</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>جارية</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>منتهية</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 py-2 px-4 rounded-lg text-white text-sm hover:bg-blue-700">
                    تصفية
                </button>
                
                <a href="{{ route('admin.competitions.index') }}" class="mr-2 bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
                    إعادة ضبط
                </a>
            </div>
        </form>
    </div>
    
    <!-- قائمة المنافسات -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            العنوان
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            الحالة
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            تاريخ البدء
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            تاريخ الانتهاء
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            المشاركات
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            مميزة
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($competitions as $competition)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($competition->title, 40) }}</div>
                                    <div class="text-sm text-gray-500">{{ $competition->slug }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($competition->status == 'active')
                                        bg-green-100 text-green-800
                                    @elseif($competition->status == 'upcoming')
                                        bg-blue-100 text-blue-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ __('competitions.status.' . $competition->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                {{ $competition->start_date->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                {{ $competition->end_date->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $competition->submissions_count ?? $competition->submissions->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($competition->is_featured)
                                    <span class="text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.competitions.show', $competition->id) }}" class="text-blue-600 hover:text-blue-900 ml-4">عرض</a>
                                <a href="{{ route('admin.competitions.edit', $competition->id) }}" class="text-yellow-600 hover:text-yellow-900 ml-4">تعديل</a>
                                <button type="button" onclick="confirmDelete('{{ $competition->id }}')" class="text-red-600 hover:text-red-900">حذف</button>
                                
                                <form id="delete-form-{{ $competition->id }}" action="{{ route('admin.competitions.destroy', $competition->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                لا توجد منافسات متاحة.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $competitions->withQueryString()->links() }}
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('هل أنت متأكد من رغبتك في حذف هذه المنافسة؟ هذا الإجراء لا يمكن التراجع عنه.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection