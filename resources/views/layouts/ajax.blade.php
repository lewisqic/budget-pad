@yield('content')

<script>config.ajax_path = '{{ Request::path() }}';</script>
@stack('scripts')