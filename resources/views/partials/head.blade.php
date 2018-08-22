<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			.table{

			}
			.column1{
				display: table-cell;
            	vertical-align: middle;    
				width: 7cm;
				height: 3cm;
			}
			.column2{
				display: table-cell;
            	vertical-align: middle;    
				width: 15cm;
				height: 3cm;
				text-align: right;
				font: italic bold 13pt arial, sans-serif;
			}
			.caja{
				background: #D6E3BC;
				margin: 0 0 0 1.5cm;
				padding: 2px 3px 2px 0;
			}
		</style>
	</head>
    <body>
    	<div class="table">
	        <div class="column1">
	            <img src="{{ public_path("/img/logo.png") }}" width="200px">
	        </div>
	        <div class="column2">
	        	<div class="caja">
	        		MUTUAL DE SERVICIOS AL POLICIA "MUSERPOL"
	        	</div>
	        </div>
	        <div style="clear: both;"></div>
	    </div>
    </body>
</html>