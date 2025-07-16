@props([
    'label' => 'Add',
    'color' => 'btn-primary',
    'target' => '#model'
])

<button {{ $attributes->merge([
    'class'=> "btn $color btn-round",
    'type'=> "button"
]) }}  data-bs-target="{{ $target }}" data-bs-toggle="modal" >{{ $label }}</button>
