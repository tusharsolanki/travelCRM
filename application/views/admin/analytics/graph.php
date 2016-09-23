<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<?php 
	$array1 = array();
	$array2 = array();
	if(!empty($graph_data)){
	foreach($graph_data as $row){
		
		$array1[] = "'".$row['created_date']."'";	
		$array2[] = $row['contact_count'];	
	}
}
$arr1=implode(",",$array1);
$arr2=implode(",",$array2);
?>
<script type="text/javascript">
$(function () {
	
        $('#container').highcharts({
            chart: {
                type: 'area',
                spacingBottom: 30
            },
            title: {
                text: 'Contact Graph *'
            },
            /*subtitle: {
                text: '* Date',
                floating: true,
                align: 'right',
                verticalAlign: 'bottom',
                y: 15
            },*/
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 150,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF'
				
            },
           /* xAxis: {
                categories: [<?=$arr1?>]
            },*/
			xAxis: {
                categories
					: [<?=$arr1?>]
            ,
			title: {
                    text: 'Date'
					
                },
			 labels: {
				align: 'right', 
			    rotation: -90,
				style: {
					//color: '#000000',
					//fontWeight: 'bold'
						}
                    },
					
			},
            yAxis: {
                title: {
                    text: 'No Of Contacts'
					
                },
				//angle:'-40',
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ this.y;
                }
            },
            plotOptions: {
                area: {
                    fillOpacity: 0.5
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Contact',
                data: [<?=$arr2?>]
            }]
        });
    });
    

		</script>