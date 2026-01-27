@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Categories</h2>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Add Category
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50 {{ $category->parent_id ? 'bg-gray-50' : '' }}">
                <td class="px-6 py-4">
                    <div class="font-medium {{ $category->parent_id ? 'pl-8' : '' }}">
                        {{ $category->parent_id ? '↳ ' : '' }}{{ $category->name }}
                    </div>
                    @if($category->name_ar)
                    <div class="text-sm text-gray-500" dir="rtl">{{ $category->name_ar }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $category->parent ? $category->parent->name : '-' }}
                </td>
                <td class="px-6 py-4 text-sm">{{ $category->products_count }}</td>
                <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No categories</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
