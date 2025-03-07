@if ($errors->any())
    <div {{ $attributes }}>
        <ul class="mt-3 list-none list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
