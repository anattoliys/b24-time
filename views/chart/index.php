<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/layouts/header.php';

$dateFormat = 'YYYY-MM-DD';
$timeFormat = 'HH:mm:ss';
$dbTime = Time::getCurrentMonthTime();
$firstTimeItemDate = $dbTime[0]['date'];
$currentMonthName = date('F', strtotime($firstTimeItemDate));
?>

    <canvas id="currentMonthTimeChart"></canvas>

    <link rel="stylesheet" href="/resources/css/Chart.min.css">
    <script src="/resources/js/moment.min.js"></script>
    <script src="/resources/js/Chart.min.js"></script>

    <script>
        let ctx = document.getElementById('currentMonthTimeChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    backgroundColor: 'rgba(59, 200, 245, .5)',
                    borderColor: 'rgba(59, 200, 245, 1)',
                    label: 'Time is',
                    
                    data: [
                        <?php foreach ($dbTime as $time): ?>
                            {
                                x: moment('<?php echo $time["date"] ?>', '<?php echo $dateFormat ?>'),
                                y: moment('<?php echo $time["dayTime"] ?>', '<?php echo $timeFormat ?>'),
                            },
                        <?php endforeach; ?>
                    ]
                }]    
            },
            options: {
                scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Day of <?php echo $currentMonthName ?>',
						},
                        type: 'time',
                        time: {
                            unit: 'day',
                            displayFormats: {
                                day: 'DD'
                            },
                            tooltipFormat: 'DD.MM.YYYY',
                        }
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Hours',
						},
                        type: 'time',
                        time: {
                            unit: 'hour',
                            displayFormats: {
                                hour: 'HH:mm'
                            },
                            tooltipFormat: 'HH:mm',
                        },
					}]
				}
            }
        });
    </script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/layouts/footer.php';
