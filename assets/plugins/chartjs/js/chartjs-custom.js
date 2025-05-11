$(function () {
	"use strict";
	$(document).ready(function() {
		$('#monitoring_chart_container').hide();
		$('#monitoring_daunbatang_chart_container').hide();
	});
	function randomColor() {
		var r = Math.floor(Math.random() * 256);
		var g = Math.floor(Math.random() * 256);
		var b = Math.floor(Math.random() * 256);
		return "rgb(" + r + "," + g + "," + b + ")";
	}

	var monitoringColors = [];
	monitoringColors.push(randomColor());
	monitoringColors.push(randomColor());
	monitoringColors.push(randomColor());
	var monitoringChart = new Chart(document.getElementById("monitoring_kebun_chart"), {
		type: 'pie',
		data: {
			labels: ['Mentah', 'Matang', 'Setengah Matang'],
			datasets: [{
				label: "Grafik Monitoring",
				backgroundColor: monitoringColors,
				data: [0,0,0]
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Jumlah Buah'
			},
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var value = data.datasets[0].data[tooltipItem.index];
						return value.toLocaleString();
					}
				}
			},
		}
	});

	var colors = [];
	nama_kebun.forEach(e => colors.push(randomColor()));
	var luas_kebun_float = [];
	luas_kebun.forEach(e => luas_kebun_float.push(parseFloat(e)));
	var myChart = new Chart(document.getElementById("luas_kebun_chart"), {
  		type: 'pie',
		data: {
			labels: nama_kebun,
			datasets: [{
				label: "Grafik Luas Blok (HA)",
				backgroundColor: colors,
				data: luas_kebun_float
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Luas Kebun'
			},
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var value = data.datasets[0].data[tooltipItem.index];
						return value.toLocaleString();
					}
				}
			},
			onClick: function(event, elements) {
				if (elements.length > 0) {
					var clickedElementIndex = elements[0]._index;
					var label = myChart.data.labels[clickedElementIndex];
					if(dataKebun.hasOwnProperty(label)){
						var data = dataKebun[label];
						var values = [data.buah_tandan_mentah, data.buah_tandan_matang, data.buah_tandan_segera_matang];
						monitoringChart.data.datasets[0].data = values;
						monitoringChart.data.datasets[0].label = 'Grafik Monitoring Kebun ' + label;
						monitoringChart.update();
						$("#monitoring_title").text("Grafik Monitoring Kebun " + label);
						$('#monitoring_chart_container').css("display", "flex");
						$('#monitoring_not_found').hide();
					}else{
						$("#monitoring_title").text("Grafik Monitoring Kebun " + label);
						$('#monitoring_not_found').show();
						$('#monitoring_chart_container').hide();
					}
				}
			}
		}
	});

	var total_panen_float = [];
	total_panen.forEach(e => total_panen_float.push(parseFloat(e)));
	new Chart(document.getElementById("panen_tahunan_chart").getContext('2d'), {
		type: 'bar',
		data: {
			labels: tahun_panen,
			datasets: [{
				label: 'Tahun Panen',
				data: total_panen_float,
				barPercentage: .5,
				backgroundColor: "#a9e3eb"
			}]
		},
		options: {
			maintainAspectRatio: false,
			title: {
				display: true,
				text: 'Hasil Panen Tahunan'
			},
			legend: {
				display: true,
				labels: {
					fontColor: '#585757',
					boxWidth: 40
				}
			},
			tooltips: {
				enabled: true
			},
		}
	});
});
