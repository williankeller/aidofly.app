@if ($errors->any())
    <x-notification.alert :message="$errors->first()" :show="true" />
@endif
@if (session()->get('message'))
    <x-notification.alert :message="session()->get('message')['content']" :show="true" :icon="session()->get('message')['type'] === 'success' ? 'ti-square-rounded-check-filled' : null" />
@endif
