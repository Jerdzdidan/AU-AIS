<label class="form-label" for="{{ $id }}">{{ $label }}</label>
<select id="{{ $id }}" name="{{ $id }}" class="form-control" data-placeholder="Select a department">
    <option value=""></option>
    <!-- Options will be loaded via AJAX or Customized -->
    {{ $slot }}
</select>