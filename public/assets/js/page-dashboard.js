google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

$(window).resize(function(){
  drawChart();
});
function drawChart(){

    
    
    var data = google.visualization.arrayToDataTable([
        ['Month', 'This Year', 'Last Year'],
        ['Jan',  10,      0],
        ['Feb',  50,      60],
        ['Mar',  75,       78],
        ['Apr',  100,      90],
        ['May',  30,      35],
        ['Jun',  85,      90],
        ['Jul',  3,      10],
        ['Aug',  19,      90],
        ['Sep',  25,      26],
        ['Oct',  49,      55],
        ['Nov',  66,      77],
        ['Dec',  45,      70],



      ]);

      var options = {
        vAxis: {
          format: '$#'
      },
        titleTextStyle: {
         
          fontSize:20, // 12, 18 whatever you want (don't specify px)
          bold:true,    // true or false
        
      },
        height:385,
        title: 'Maintenance',
        curveType: '',
        colors: ["#FF0000","#D6D6D6"],
        legend: {  position: 'top', alignment: 'end' }
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart'));

      chart.draw(data, options);
    
  

}
