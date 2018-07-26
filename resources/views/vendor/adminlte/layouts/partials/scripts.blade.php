<script src="{{ url (mix('/js/app.js')) }}" type="text/javascript"></script>
<script src="{{ url (mix('/js/main.js')) }}" type="text/javascript"></script>
<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
</script>