
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
            <div class="col-xs-12 market_player_box">
                <div id="msj"></div>   
                <div class="coin_content">
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
            </div>
        </div>
    </div>
</div>   

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
            var precio = document.getElementById("sl").value;
        }else{
            var precio = document.getElementById("gold").value;
        }

        var info = new FormData();
            info.append('accion',accion);
            info.append('precio',precio);
        $.ajax({
            beforeSend: function(){
                $('#msj').html('<p class="alert alert-info">Loading ...</p>');
            },
            url: '<?= $HOME; ?>core/modules/ajax/cambiar.php',
            type: 'POST',
            data: info,
            async: true,
            success: function(resp){
                $('#msj').html('<p class="alert alert-info">Listo</p>');
                //location.reload();
            },
            error: function(jqXRH,estado,error){
                $('#msj').html(error);
            },
            cache: false,
            contentType: false,
            processData: false
        }); 
    }
    function soloNumeros(e){
        var key = e.charCode;
        return key >= 48 && key <= 57;
    }
     $("#gold").keyup(function () {
          var value = $(this).val();
          if(value > 0 && value != ""){
            var inme = new FormData();
                inme.append('accion',"restar");
                inme.append('valor',value);

            $.ajax({
                url: '<?= $HOME; ?>core/modules/ajax/consultar.php',
                async: true,
                type: 'POST',
                data: inme,
                success: function(resp){
                    $("#slres").html(resp);
                },
                error: function(jqXRH,estado,error){
                    $('#msj').html(error);
                },
                cache: false,
                contentType: false,
                processData: false
            }); 
          }else{
            $("#slres").html("");
          }
          
        }).keyup();

        $("#sl").keyup(function () {
          var value = $(this).val();
          if(value > 0 && value != ""){
            var inme = new FormData();
                inme.append('accion',"sumar");
                inme.append('valor',value);

            $.ajax({
                url: '<?= $HOME; ?>core/modules/ajax/consultar.php',
                async: true,
                type: 'POST',
                data: inme,
                success: function(resp){
                    $("#goldres").html(resp);
                },
                error: function(jqXRH,estado,error){
                    $('#msj').html(error);
                },
                cache: false,
                contentType: false,
                processData: false
            }); 
          }else{
            $("#goldres").html("");
          }
          
        }).keyup();

am4core.ready(function() {

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
}, {
  "date": "2012-08-06",
  "value": 16
}, {
  "date": "2012-08-07",
  "value": 18
}, {
  "date": "2012-08-08",
  "value": 21
}, {
  "date": "2012-08-09",
  "value": 26
}, {
  "date": "2012-08-10",
  "value": 24
}, {
  "date": "2012-08-11",
  "value": 29
}, {
  "date": "2012-08-12",
  "value": 32
}, {
  "date": "2012-08-13",
  "value": 18
}, {
  "date": "2012-08-14",
  "value": 24
}, {
  "date": "2012-08-15",
  "value": 22
}, {
  "date": "2012-08-16",
  "value": 18
}, {
  "date": "2012-08-17",
  "value": 19
}, {
  "date": "2012-08-18",
  "value": 14
}, {
  "date": "2012-08-19",
  "value": 15
}, {
  "date": "2012-08-20",
  "value": 12
}, {
  "date": "2012-08-21",
  "value": 8
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
chart.scrollbarX = new am4charts.XYChartScrollbar();
chart.scrollbarX.series.push(series);
chart.scrollbarX.parent = chart.bottomAxesContainer;

dateAxis.start = 0.79;
dateAxis.keepSelection = true;


}); // end am4core.ready()
</script>