function refresh()
{
	$.get("map.php", function(data, status) {
		if (status == "success")
			$("#gameObjects").html(data);
	});
	$.get("readyShips.php", function(data, status) {
		if (status == "success")
		{
			$("#readyShips").html(data);
			$("#active-submit").click(function () {
				var shipToActivate = $("input[name='active-select']:checked").val();
				$.post("active.php", {name: shipToActivate}, function(data, status) {
					if (status == "success")
						refresh();
				});
			});
		}
	});
	$.get("log.php", function(data, status) {
		if (status == "success")
			$("#log").html(data);
	});
}

function sendGet(url)
{
	$.get(url, function(data, status) {
		if (status == "success")
			refresh();
	});
}

$(document).ready(function() {
	refresh();
});
