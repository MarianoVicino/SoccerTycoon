<script src="libs/jquery.number.min.js"></script>
<script>
    $(document).ready(function(){
        $('.es').hide();
        $('.show_en').click(function(){
            $('.es').hide();
            $('.en').show(500);
        });
        $('.show_es').click(function(){
            $('.en').hide();
            $('.es').show(500);
        });
        $('input[name=coins]').on('keyup',function(){
            var number=$('input[name=coins]').val()/43000;
            var tax=number*0.10;
            var total=number-tax;
            $('.equals').html($.number(number,2));
            $('.tax').html($.number(tax,2));
            $('.total').html($.number(total,2));
        });
        $('form').submit(function(e){
            e.preventDefault();
            var info = new FormData($(this)[0]);
            $('#button_submit').attr('disabled','disabled');
            $.ajax({
                beforeSend: function()
                {
                    $('#msj').html('<p class="alert alert-info">Validating...</p>');
                },
                url: 'core/modules/ajax/withdrawalAjax.php',
                type: 'POST',
                data: info,
                async: true,
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
<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <h3 class="module-title text-center">WITHDRAWAL FOUNDS</h3>
        <div class="lang2">
            <div class="col-xs-6">
                <img class="img-responsive show_en" src="libs/images/en.png" style="float:right;">
            </div>
            <div class="col-xs-6">
                <img class="img-responsive show_es" src="libs/images/es.png">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="len50 center-block">
            <p class="alert alert-info en">
                <span class="glyphicon glyphicon-info-sign"></span> The minimum amount to withdraw is u$s20, at the time of withdrawal will be charged a fee of 10% per commission and will go directly to a found to collect more money for the division prizes.<br><br>
                <span class="glyphicon glyphicon-chevron-right"></span> u$s1,00 = 43.000 Coins<br>
             
            </p>
            <p class="alert alert-info es">
                <span class="glyphicon glyphicon-info-sign"></span> El importe minimo a retirar es de u$s20, al momento de retirar se cobrara un impuesto de 10% por comision, esto ira directo a un fondo para juntar mas dinero para repartir en la division.<br><br>
                <span class="glyphicon glyphicon-chevron-right"></span> u$s1,00 = 43.000 Monedas<br>
                
            </p>
            <div id="msj"></div>
            <?php 
                $builder->GetWithdrawalAccounts($_SESSION['user_fmo']);
            ?>
        </div>
    </div>
</div>  


