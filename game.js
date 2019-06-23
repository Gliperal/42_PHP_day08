function getQuery(key)
{
	var queryString = window.location.search;
	if(queryString.startsWith("?"))
		queryString = queryString.substring(1);
	var query = queryString.split(";");
	for(var i in query)
	{
		var pair = query[i].split("=");
		if (pair[0] == key)
			return pair[1];
	}
}

function refresh()
{
	var lobbyID = getQuery("id");
	$.get("map.php?lobby=" + lobbyID, function(data, status) {
		if (status == "success")
			$("#gameObjects").html(data);
	});
	$.get("readyShips.php?lobby=" + lobbyID, function(data, status) {
		if (status == "success")
		{
			$("#readyShips").html(data);
			$("#active-submit").click(function () {
				var shipToActivate = $("input[name='active-select']:checked").val();
				$.post("active.php", {name: shipToActivate, lobby: lobbyID}, function(data, status) {
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
	$.get(url + "?lobby=" + getQuery("id"), function(data, status) {
		if (status == "success")
			refresh();
	});
}

$(document).ready(function() {
	refresh();
});
