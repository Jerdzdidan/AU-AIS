<label class="form-label" for="{{ $id }}">{{ $label }}</label>
<select id="{{ $id }}" name="{{ $id }}" class="form-control">
    <option value=""></option>
    <!-- Options will be loaded via AJAX or Customized -->
    {{ $slot }}

    @if($options)
        @foreach($options as $option)
            <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
        @endforeach
    @endif
</select>