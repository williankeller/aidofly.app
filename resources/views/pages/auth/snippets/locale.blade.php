<div class="mt-auto p-3" style="max-width: 230px;">
    <form class="d-flex align-items-center" action="{{ route('locale.switch') }}" method="post">
        <i class="fs-4 text-white ti ti-language me-1"></i>
        <select name="locale" class="form-select form-select-sm" onchange="this.form.submit()">
            @foreach ($locales as $code => $locale)
                <option value="{{ $code }}" @selected(old('locale', app()->getLocale()) == $code)>{{ $locale }}</option>
            @endforeach
        </select>
        @csrf
    </form>
</div>
