@if(session()->has('mensaje'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Exitoso!</h4>
        {{ session()->get('mensaje') }}
    </div>
    <script>
        // Desaparecer el mensaje de éxito después de 3 segundos
        setTimeout(function() {
            $('#successMessage').fadeOut('slow');
        }, 3000); // 3000 ms = 3 segundos
    </script>
@endif
