
<style>
#chartdiv {
  width: 100%;
  height: 500px;
  max-width: 100%;
}

</style>
<!-- Resources -->

<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://www.amcharts.com/lib/4/lang/es_ES.js"></script>
<div class="col-md-9 col-sm-7 push-sm col-xs-12">
    <div class="dashboard-item-box">
        <div class="row container-fluid ranking_content market_master">
            <hgroup>
                <h3 class="nom-nop"><span class="market_title">Exchange</span> <button class="help_box btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-container=".market_master" data-title="Help" data-content="Texto"><span class="glyphicon glyphicon-question-sign"></span></button></h3>
            </hgroup>
            <?php $vvalor_gold = mysqli_query($db, "SELECT precio FROM `Gold`");
            $re_gold = mysqli_fetch_array($vvalor_gold);
            ?>
            <div class="col-xs-12 market_player_box">
                <div id="msj"></div>   
                <div class="coin_content">
                    El valor de 1 GOLD esta cotizando en <?php echo $re_gold['precio']; ?> ST.
                    <hr />
                    <div>
                       Gold: <input type="text" name="gold" id="gold" onKeyPress="return soloNumeros(event)"> pasa a ST<span id="slres"></span><br>
                       <button type="button" onclick="gold('restar');">Cambiar</button><br>
                    </div>
                    <br>
                    <div>
                        SL: <input type="text" name="sl" id="sl" onKeyPress="return soloNumeros(event)"> pasa a Gold<span id="goldres"></span><br>
                        <button type="button" onclick="gold('sumar');">Cambiar</button><br>
                    </div>
                    
                    
                </div>
                <br>
                <hr />
                <div id="chartdiv"></div>
                <?php
                $accio = mysqli_query($db, "SELECT * FROM `Gold_acciones` ORDER BY id DESC LIMIT 20");
                while ($re_accio = mysqli_fetch_array($accio)) {?>
                    <div class="col-xs-9">
                       <?php echo $re_accio['texto'] ; ?>
                    </div>
                    <div class="col-xs-3">
                      <?php echo $re_accio['fecha'] ; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>   
<?php
    $ssql = mysqli_query($db, "SELECT gold,oro FROM `Equipos` WHERE usuario='".$_SESSION['user_fmo']."'");
    $ree_ssql = mysqli_fetch_array($ssql);
?>
<script>
    $(document).ready(function(){
        miFormulario = document.getElementById('#gold');
        miFormulario2 = document.getElementById('#sl');
        miFormulario.addEventListener('keypress', function (e){
            if (!soloNumeros(event)){
            e.preventDefault();
          }
        })
        miFormulario2.addEventListener('keypress', function (e){
            if (!soloNumeros(event)){
            e.preventDefault();
          }
        })
       
    });
    function gold(accion) {
        if(accion == "sumar"){
            var nuevo = <?php echo $ree_ssql['oro']; ?>;
            var precio = document.getElementById("sl").value;
        }else{
            var nuevo = <?php echo $ree_ssql['gold']; ?>;
            var precio = document.getElementById("gold").value;
        }
        if(precio == 0){
            $('#msj').html('<p class="alert alert-info">El cambio tiene que ser diferente a 0</p>');
        }else{
            if(nuevo >= precio){
                var uuser = '<?php echo $_SESSION['user_fmo']; ?>';
                var info = new FormData();
                    info.append('accion',accion);
                    info.append('precio',precio);
                    info.append('usuario',uuser);
                $.ajax({
                    beforeSend: function(){
                        $('#msj').html('<p class="alert alert-info">Loading ...</p>');
                    },
                    url: '<?= $HOME; ?>core/modules/ajax/cambiar.php',
                    type: 'POST',
                    data: info,
                    async: true,
                    success: function(resp){
                        //$('#msj').html('<p class="alert alert-info">Listo</p>');
                        location.reload();
                    },
                    error: function(jqXRH,estado,error){
                        $('#msj').html(error);
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });  
            }else{
                $('#msj').html('<p class="alert alert-info">Error en el intercambio.</p>');
            }
        }
        
    }
    function soloNumeros(e){
        var key = e.charCode;
        return key >= 48 && key <= 57;
    }
     $("#gold").keyup(function () {
          var value = $(this).val();
          if(value > 0 || value != ""){
            coonsultar("restar",value);
          }else{
            $("#slres").html("");
          }
          
        }).keyup();

        $("#sl").keyup(function () {
          var value = $(this).val();
          if(value > 0 && value != ""){
            coonsultar("sumar",value);
          }else{
            $("#goldres").html("");
          }
          
        }).keyup();

function coonsultar(accion2,value2) {
    var inme = new FormData();
        inme.append('accion',accion2);
        inme.append('valor',value2);

    $.ajax({
        url: '<?= $HOME; ?>core/modules/ajax/consultar.php',
        async: true,
        type: 'POST',
        data: inme,
        success: function(resp){
            if(accion2 == "restar"){
                $("#slres").html(resp);
            }else{
                $("#goldres").html(resp);
            }
            
        },
        error: function(jqXRH,estado,error){
            $('#msj').html(error);
        },
        cache: false,
        contentType: false,
        processData: false
    });
}
/*am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);
 chart.language.locale = am4lang_es_ES;
 chart.dateFormatter.language = new am4core.Language();
 chart.dateFormatter.language.locale = am4lang_es_ES;
// Add data
chart.data = [{
  "date": "2012-07-27",
  "value": 13
}, {
  "date": "2012-07-28",
  "value": 11
}, {
  "date": "2012-07-29",
  "value": 15
}, {
  "date": "2012-07-30",
  "value": 16
}, {
  "date": "2012-07-31",
  "value": 18
}, {
  "date": "2012-08-01",
  "value": 13
}, {
  "date": "2012-08-02",
  "value": 22
}, {
  "date": "2012-08-03",
  "value": 23
}, {
  "date": "2012-08-04",
  "value": 20
}, {
  "date": "2012-08-05",
  "value": 17
}];

// Set input format for the dates
chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.valueY = "value";
series.dataFields.dateX = "date";
series.tooltipText = "{value}"
series.strokeWidth = 2;
series.minBulletDistance = 15;

// Drop-shaped tooltips
series.tooltip.background.cornerRadius = 20;
series.tooltip.background.strokeOpacity = 0;
series.tooltip.pointerOrientation = "vertical";
series.tooltip.label.minWidth = 40;
series.tooltip.label.minHeight = 40;
series.tooltip.label.textAlign = "middle";
series.tooltip.label.textValign = "middle";

// Make bullets grow on hover
var bullet = series.bullets.push(new am4charts.CircleBullet());
bullet.circle.strokeWidth = 2;
bullet.circle.radius = 4;
bullet.circle.fill = am4core.color("#fff");

var bullethover = bullet.states.create("hover");
bullethover.properties.scale = 1.3;

// Make a panning cursor
chart.cursor = new am4charts.XYCursor();
chart.cursor.behavior = "panXY";
chart.cursor.xAxis = dateAxis;
chart.cursor.snapToSeries = series;

// Create vertical scrollbar and place it before the value axis
/*chart.scrollbarY = new am4core.Scrollbar();
chart.scrollbarY.parent = chart.leftAxesContainer;
chart.scrollbarY.toBack();*/

// Create a horizontal scrollbar with previe and place it underneath the date axis
/*chart.scrollbarX = new am4charts.XYChartScrollbar();
chart.scrollbarX.series.push(series);
chart.scrollbarX.parent = chart.bottomAxesContainer;

dateAxis.start = 0.79;
dateAxis.keepSelection = true;


}); */// end am4core.ready()
</script>