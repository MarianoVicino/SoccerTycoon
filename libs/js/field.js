function GetTitularsList()
{
    $.ajax({
        beforeSend: function()
        {
            //$('.titulars').html('');
        },
        url: 'core/modules/ajax/formation_gettitulars_listAjax.php',
        type: 'POST',
        async: false,
        success: function(resp)
        {
            $('.titulars_list').html(resp);
        },
        error: function(jqXRH,estado,error)
        {
           
        }
    });     
}
function GetSubstitutesList()
{
    $.ajax({
        beforeSend: function()
        {
            //$('.titulars').html('');
        },
        url: 'core/modules/ajax/formation_getsubstitutes_listAjax2.php',
        type: 'POST',
        async: false,
        success: function(resp)
        {
           $('.substitutes_list').html(resp);
			
			$(".dragAndDrop").draggable({
				revert: "invalid",
				addClasses: false
			});
			$(".dragAndDrop").droppable({
				drop: ChangePlayer,
				addClasses: false
			});
			$(".sellOnDrop").droppable({
				drop: ChangePlayer,
				addClasses: false
			});
        },
        error: function(jqXRH,estado,error)
        {
           
        }
    });     
}
function ChangeFormation(formation)
{
    $.ajax({
        beforeSend: function()
        {
            //$('.titulars').html('');
        },
        url: 'core/modules/ajax/formation_changeFormationAjax.php',
        type: 'POST',
        data:{formation:formation},
        async: false,
        success: function(resp)
        {
            GetTitulars();
            GetSalary();
            GetScore();
            GetFormation();
            DrawField();
        },
        error: function(jqXRH,estado,error)
        {
           
        }
    }); 
}
function GetFormation()
{
    $.ajax({
        beforeSend: function()
        {
            //$('.titulars').html('');
        },
        url: 'core/modules/ajax/formation_getFormationAjax.php',
        type: 'POST',
        async: false,
        success: function(resp)
        {
            $('.formation').html(resp);
        },
        error: function(jqXRH,estado,error)
        {
           
        }
    }); 
}
function GetScore()
{
    $.ajax({
        beforeSend: function()
        {
            //$('.titulars').html('');
        },
        url: 'core/modules/ajax/formation_getScoreAjax.php',
        type: 'POST',
        async: false,
        success: function(resp)
        {
            $('.score_number').html(resp);
        },
        error: function(jqXRH,estado,error)
        {
           
        }
    });
}
function GetSalary()
{
    $.ajax({
        beforeSend: function()
        {
            //$('.titulars').html('');
        },
        url: 'core/modules/ajax/formation_getSalaryAjax.php',
        type: 'POST',
        async: false,
        success: function(resp)
        {
            $('.salary_number').html(resp);
        },
        error: function(jqXRH,estado,error)
        {
           
        }
    });
}
function GetTitulars()
{
    $.ajax({
        beforeSend: function()
        {
            //$('.titulars').html('');
        },
        url: 'core/modules/ajax/formation_getTitularsAjax.php',
        type: 'POST',
        async: false,
        success: function(resp)
        {
            $('.titulars').html(resp);
        },
        error: function(jqXRH,estado,error)
        {
           
        }
    });
}
function DrawField()
{
        $.ajax({
        beforeSend: function()
        {
            $('#field').html('<img src="libs/images/loading.gif" class="loading center-block">');
        },
        url: 'core/modules/ajax/formation_getFieldAjax2.php',
        type: 'POST',
        async: true,
        success: function(resp)
        {
            $('#field').html(resp);

			$(".dragAndDrop").draggable({
			revert: "invalid",
			addClasses: false
					});
			$(".dragAndDrop").droppable({
					drop: ChangePlayer,
					addClasses: false
					});
        }
    });
}
function GetOtherPlayers(titular)
{
    $('#rest-form').css({'display':'none'});
    $.ajax({
        beforeSend: function()
        {
            //$('.titulars').html('');
        },
        url: 'core/modules/ajax/formation_getChangeablesAjax.php',
        type: 'POST',
        data: {titular:titular},
        async: false,
        success: function(resp)
        {
            $('.changefor').html(resp);
            $('#rest-form').css({'display':'block'});
        }
    });
}

function SellPlayer(id){
	var formData = new FormData();
	formData.append("id", id);
	$.ajax({
		beforeSend: function()
		{
			$('#msj').html('<p class="alert alert-info">Validating ...</p>');
		},
		url: 'core/modules/ajax/confirm_sell_playerAjax.php',
		type: 'POST',
		data: formData,
		async: true,
		success: function(resp)
		{
			if(resp == 0){
				$('#msj').html('<div class="alert alert-success alert-dismissible" role="alert">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                       'You can only sell players in your substitutes' +
                      '</div>');
			}else{
				if(confirm(resp)){
					var formData2 = new FormData();
					formData2.append("id", id);
					$.ajax({
						beforeSend: function()
						{
							$('#msj').html('<p class="alert alert-info">Validating ...</p>');
						},
						url: 'core/modules/ajax/automatic_sell_playerAjax.php',
						type: 'POST',
						data: formData2,
						async: true,
						success: function(resp)
						{
							$('#msj').html(resp);
							$('#rest-form').css({'display':'none'});
							$('.changefor').html("");
							GetSalary();
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
						complete: function()
						{
						},
						cache: false,
						contentType: false,
						processData: false
					}); 
				}
			}
			$('#rest-form').css({'display':'none'});
			$('.changefor').html("");
			GetSalary();
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
		complete: function()
		{
		},
		cache: false,
		contentType: false,
		processData: false
	}); 
	
}

function ChangePlayer(event, ui) 
		{
			
		  var draggableId = ui.draggable.attr("id");
		  var droppableId = $(this).attr("id");	
		  var droppableSituation = $(this).attr("situation");
		  var draggableSituation = ui.draggable.attr("situation");
		  		  
		  var titular = draggableId;
		  var substitute = droppableId;
		  
		  if(droppableSituation == "sell"){
			SellPlayer(titular);
			return;
		  }
		  
		  if(draggableSituation!="titular")
		  {
		  	titular = droppableId;
			substitute = draggableId;
		  }		  
		  	var formData = new FormData();
			formData.append("titular", titular);
			formData.append("changefor", substitute);
			$.ajax({
                beforeSend: function()
                {
                    $('#msj').html('<p class="alert alert-info">Validating ...</p>');
                },
                url: 'core/modules/ajax/change_playerAjax.php',
                type: 'POST',
                data: formData,
                async: true,
                success: function(resp)
                {
                    $('#msj').html(resp);
                    $('#rest-form').css({'display':'none'});
                    $('.changefor').html("");
                    GetSalary();
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
				complete: function()
				{
				},
                cache: false,
                contentType: false,
                processData: false
            });  
		}



