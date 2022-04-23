<div class="inline-block relative w-full {{ $attributes['class'] }}">
    <select class="{{ $attributes['select-class'] }} block w-full @if(!isset($attributes['default'])) custom-select @endif focus:outline-none focus:shadow-outline @if(!isset($attributes['no-filter'])) filter-field @endif" {{ $attributes }}>
        <option value="">Selecione um valor</option>
        @php
            $index = 0;
        @endphp
        @foreach ($options as $key => $item)
            <option  @if(isset($ids[$index])) data-id="{{ $ids[$index] }}" @endif @if($key == $value) {{ 'selected' }} @endif value="{{ $key }}">{{ __($item) }}</option>
            @php
                $index++;
            @endphp
        @endforeach
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
        <svg class="fill-current h-4 w-4 {{ $attributes['arrow-class'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
    </div>
</div>
