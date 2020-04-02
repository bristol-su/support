@if(config('app.analytics.enabled', false))
<script async src="https://www.googletagmanager.com/gtag/js?id={{config('app.analytics.UA')}}"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', '{{config('app.analytics.UA')}}');
</script>
@endif
