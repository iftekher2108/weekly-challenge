<option value="{{ $category->id }}" @if ($category->id == $selected ) selected @endif  >{{ $prefix }}{{ $category->title }}</option>
@if($category->children && $category->children->count())
    @foreach($category->children as $child)
        @include('backend.catagory.select-option', ['category' => $child, 'selected' => $selected, 'prefix' => $prefix . '— '])
    @endforeach
@endif
