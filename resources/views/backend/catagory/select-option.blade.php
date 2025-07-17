<option value="{{ $category->id }}">{{ $prefix }}{{ $category->title }}</option>
@if($category->children && $category->children->count())
    @foreach($category->children as $child)
        @include('backend.catagory.select-option', ['category' => $child, 'prefix' => $prefix . '— '])
    @endforeach
@endif
