<link rel="stylesheet" href="libs/field.css">

<script src="libs/js/field.js"></script>

<script src="libs/js/jquery-1.12.4.js"></script>

<script src="libs/js/jquery-ui.js"></script>



<script>

	$(document).ready(function(){
        GetScore();
        GetFormation();
        GetTitulars();
        DrawField();
        GetTitularsList();
        GetSubstitutesList();
        $('.formation').change(function(e){
            ChangeFormation($(this).val());
        });
        $('.titulars').change(function(e){
            GetOtherPlayers($(this).val());
        });
        $('#change_player').submit(function(e){
            e.preventDefault();
            $('#button_submit').attr('disabled','disabled');
            var info = new FormData($(this)[0]);
            $.ajax({
                beforeSend: function()
                {
                    $('#msj').html('<p class="alert alert-info">Validating ...</p>');
                },
                url: 'core/modules/ajax/change_playerAjax.php',
                type: 'POST',
                data: info,
                async: true,
                success: function(resp)
                {
                    $('#msj').html(resp);
                    $('#rest-form').css({'display':'none'});
                    $('.changefor').html("");
                    GetScore();
                    DrawField();
                    GetTitulars();
                    GetTitularsList();
                    GetSubstitutesList();
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
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
        });
    });

</script>
        <div class="col-xs-12">
            <div class="dashboard-item-box container-fluid">
                <div class="row center-block">
                    <div class="col-sm-6 col-xs-12">
                        <div id="sup_field" class="center-block">
                            <div class="row">
                                <div class="col-sm-6 col-xs-5">
                                    <div class="form-group">
                                        <select name="formation" class="form-control formation">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-7">
                                    <hgroup>
                                        <h4 class="help_box"><button class="btn btn-link btn-xs" data-toggle="popover" data-placement="left" data-container="#sup_field" data-title="Help" data-content="Here you can set the tactics of your team for the next match.
Remember that the score is the most important thing, the team that wins the game will be the one who has the greatest.
Position the players well, because if they are not in their correct position, their level will drop to half.
On the right side you will find a selector, you must choose which player you want to change and once you have selected the player and the substitute, press Change Player to make the change come true.."><span class="glyphicon glyphicon-question-sign"></span></button></h4>
                                        <h4 class="score_box">SCORE: <span class="score_number"></span></h4>
                                    </hgroup>
                                </div>
                            </div>    
                        </div> 
                        <div id="field" class="center-block"></div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        
                        <div class="players_box">
                            <div id="msj"></div>
                        </div>
                        <hgroup>
                            <h3 class="text-center">My Substitutes</h3>
                        </hgroup>
                        <div class="players_box substitutes_list">
                        </div>
                    </div>
                </div>  
            </div>
        </div>