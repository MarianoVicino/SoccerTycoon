<script src="../libs/ckeditor/ckeditor.js"></script>
<script>
$(document).ready(function(){
    $('form').submit(function(e){
        e.preventDefault();
        for (instance in CKEDITOR.instances)
        {
             CKEDITOR.instances[instance].updateElement();
        }
        $('html, body').animate({scrollTop: $(".well").offset().top}, 500);
        $('#button_submit').attr('disabled','disabled');
        var info = new FormData($(this)[0]);
        $.ajax({
            beforeSend: function()
            {
                $('#msj').html('<p class="alert alert-info">Validando...</p>');
            },
            url: 'modules/ajax/add_newsAjax.php',
            type: 'POST',
            data: info,
            async: false,
            success: function(resp)
            {
                $('#msj').html(resp);
                $('#button_submit').removeAttr('disabled');
            },
            error: function(jqXRH,estado,error)
            {
                $('#msj').html(error);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});
</script>
<div class="well">
    <div class="len50">
        <h4 class="text-center">AGREGAR NOTICIA</h4>
        <div id="msj"></div>
        <form method="POST" action="#" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="TITULO DE LA NOTICIA" required>
            </div>
             <div class="form-group">
                <textarea name="text" id="editor1" rows="10" cols="80"></textarea>
                <script>
                    CKEDITOR.replace('editor1');
                </script>
            </div>
            <button type="submit" class="btn btn-default center-block" id="button_submit">AGREGAR</button>
        </form>
    </div>
</div>