function setActive(){
	$(document).ready(function (){
		$(function() {
			var nav = document.getElementById('#navBar'),
			anchor = nav.getElementsByTagName('a'),
			current = window.location.pathname.split('/')[2];
			for (var i = 0; i < anchor.length; i++) {
				if(anchor[i].href == current) {
					anchor[i].className = "active";
				}
			}
		});
	})
}

$(function(){
	$('.dt').DataTable({
		"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
		dom: 'lfrtip',
		language: {
			oPaginate: {
				sNext: '<i class="fas fa-forward"></i>',
				sPrevious: '<i class="fas fa-backward"></i>',
				sFirst: '<i class="fas fa-step-backward"></i>',
				sLast: '<i class="fas fa-step-forward"></i>'
			}
		},
		pagingType: 'full_numbers'
	});
});

// function showChartToday(){
// 	$.ajax({
// 		url: "/prq/csadmin/dataNow.php",
// 		method: "GET",
// 		success: function(data) {
// 			var datajson = JSON.parse(data);
// 			console.log(data);
// 			var reason = [];
// 			var count = [];

// 			for(var i in datajson) {
// 				reason.push(datajson[i].requestType);
// 				count.push(datajson[i].count);
// 			}
// 			console.log(reason, count);

// 			var chartdata = {

// 				labels: reason,
// 				datasets : [
// 				{
// 					label: 'Count',
// 					backgroundColor: ['rgba(153, 102, 255, 0.8)', 'rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)', 'rgba(255, 206, 86, 0.8)','rgba(75, 192, 192, 0.8)'],
// 					borderColor: 'rgba(200, 200, 200, 0.75)',
// 					hoverBackgroundColor: ['rgba(153, 102, 255, 1)', 'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)','rgba(75, 192, 192, 1)'],
// 					hoverBorderColor: 'rgba(200, 200, 200, 1)',
// 					data: count
// 				}
// 				]
// 			};

// 			var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

// 			var ctx = $("#myChart");
// 			var today = new Date();
// 			var day = today.getDate();
// 			var month = today.getMonth();
// 			var monthName = months[month];
// 			var year = today.getFullYear();
// 			var thisDay = monthName + ' ' + day + ' ' + year;

// 			var barGraph = new Chart(ctx, {
// 				type: 'doughnut',
// 				data: chartdata,
// 				options: {
// 					title: {
// 						display: true,
// 						text: "Today's Requests"
// 					}
// 				}
// 			});
// 		},
// 		error: function(data) {
// 			console.log(data);
// 		}
// 	});
// }

	// function showChartAll(){
	// 	$(document).ready(function(){
	// 		$.ajax({
	// 			url: "dataOverview.php",
	// 			method: "GET",
	// 			success: function(data) {
	// 				var datajson = JSON.parse(data);
	// 				console.log(data);
	// 				var reason = [];
	// 				var count = [];

	// 				for(var i in datajson) {
	// 					reason.push(datajson[i].requestType);
	// 					count.push(datajson[i].count);
	// 				}
	// 				console.log(reason, count);

	// 				var chartdata = {

	// 					labels: reason,
	// 					datasets : 
	// 					[
	// 					{
	// 						label: 'Count',
	// 						backgroundColor: 'rgba(139, 58, 58, 0.8)',
	// 						borderColor: 'rgba(200, 200, 200, 0.75)',
	// 						hoverBackgroundColor: 'rgba(139, 58, 58, 1)',
	// 						hoverBorderColor: 'rgba(200, 200, 200, 1)',
	// 						data: count
	// 					}
	// 					]
	// 				};

	// 				var ctx = $("#overallChart");

	// 				var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	// 				var date = new Date();
	// 				var x = date.getMonth();
	// 				var month = months[x];
	// 				var day = date.getDate();
	// 				var year = date.getFullYear();

	// 				var asof = `${month} ${day}, ${year}`;
	// 				console.log("AS OF", asof);

	// 				var barGraph = new Chart(ctx, {
	// 					type: 'horizontalBar',
	// 					data: chartdata,
	// 					options: {
	// 						title: {
	// 							display: true,
	// 							text: `Overall Total Count as of ${asof}`
	// 						},
	// 						scales: {
	// 							yAxes: [{
	// 								scaleLabel: {
	// 									display: true,
	// 									labelString: 'REASON'
	// 								}
	// 							}],
	// 							xAxes: [{
	// 								scaleLabel: {
	// 									display: true,
	// 									labelString: 'COUNT'
	// 								},
	// 								ticks: {
	// 									beginAtZero: true
	// 								}
	// 							}]
	// 						}
	// 					}
	// 				});
	// 			},
	// 			error: function(data) {
	// 				console.log(data);
	// 			}
	// 		});
	// 	});
	// }
