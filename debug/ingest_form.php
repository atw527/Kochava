<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			var itemPair = $('#data-items').html();
			
			$('#addDataRow').click(function() {
				$('#data-items').append(itemPair);
			});
			
			$('#delDataRow').click(function() {
				$('.data-item').last().remove();
			});
			
			var form = $('#ingest');

			form.submit(function (e) {

				e.preventDefault();
				
				var fd = {};
				fd.endpoint = { 'method': $('#endpoint-method').val(), 'url': $('#endpoint-url').val() };
				
				fd.data = {};
				
				$('.data-item').each( function() {
					fd.data[$(this).find('.data-key').val()] = $(this).find('.data-val').val();
				});
				
				$.ajax({
					type: form.attr('method'),
					url: form.attr('action'),
					data: fd,
					success: function (data) {
						console.log(data);
					},
					error: function (data) {
						console.log(data.responseText);
					},
				});
		});
		});
	</script>
</head>
<body>
	<form method="post" action="/ingest.php" id="ingest">
		<p>
			<label for="endpoint-method">Method (GET/POST):</label><br />
			<input id="endpoint-method" name="endpoint[method]" size="20" value="GET" />
		</p>

		<p>
			<label for="endpoint-url">URL:</label><br />
			<input id="endpoint-url" name="endpoint[url]" size="100" value="http://www.example.com/?something={yay}" />
		</p>
		
		<p>
			Key/Val Pairs: (<a href="javascript:void(0);" id="addDataRow">+</a> / <a href="javascript:void(0);" id="delDataRow">-</a>)
		</p>
		
		<div id="data-items">
			<p class="data-item">
				<input class="data-key" size="20" /> : <input class="data-val" size="20" />
			</p>
		</div>
		
		<p>
			<input type="submit" />
		</p>

	</form>
	
	<p>Responses will be logged in the browser's debug console.</p>
	
</body>
</html>
