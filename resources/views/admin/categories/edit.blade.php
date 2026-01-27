@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Categories
        </a>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Category Information</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name (English) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name (Arabic)
                    </label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $category->name_ar) }}" dir="rtl"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Parent Category
                    </label>
                    <select name="parent_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">None (Main Category)</option>
                        @foreach($parentCategories as $parentCategory)
                        <option value="{{ $parentCategory->id }}"
                                {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                        {{ $parentCategory->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description (English)
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $category->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description (Arabic)
                    </label>
                    <textarea name="description_ar" rows="4" dir="rtl"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description_ar', $category->description_ar) }}</textarea>
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                        class="rounded text-blue-600">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex gap-4 justify-between">
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this category?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete Category
                </button>
            </form>

            <div class="flex gap-4">
                <a href="{{ route('admin.categories.index') }}"
                   class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update Category
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
