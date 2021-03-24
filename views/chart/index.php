<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/layouts/header.php';

$dateFormat = 'YYYY-MM-DD';
$timeFormat = 'HH:mm:ss';
$userObj = new User;
$users = $userObj->getAll();

if (empty($users)) {
    die('no users');
}

foreach ($users as $key => $user) {
    $rgbColor = '';

    foreach(['r', 'g', 'b'] as $color) {
        $rgbColor .= mt_rand(0, 255) . ', ';
    }

    $users[$key]['color'] = $rgbColor;

    $users[$key]['monthTime'] = Time::getUserMonthTime($user['id']);
}

$firstTimeItemDate = $users[0]['monthTime'][0]['date'];
$currentMonthName = date('F', strtotime($firstTimeItemDate));

foreach ($users as $key => $user) {
    foreach ($user['monthTime'] as $k => $time) {
        sscanf($time['dayTime'], "%d:%d:%d", $hours, $minutes, $seconds);
        $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
        $users[$key]['monthTime'][$k]['seconds'] = $time_seconds;
    }
}
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
                datasets: [
                    <?php foreach ($users as $user): ?>
                            {
                            backgroundColor: 'rgba(<?php echo $user['color'] ?> .3)',
                            borderColor: 'rgba(<?php echo $user['color'] ?> .8)',
                            label: '<?php echo $user["name"] ?>',
                            data: [
                                <?php foreach ($user['monthTime'] as $time): ?>
                                    {
                                        x: moment('<?php echo $time["date"] ?>', '<?php echo $dateFormat ?>'),
                                        y: '<?php echo $time['seconds'] ?>',
                                        minutes: '<?php echo $time['minutes'] ?>',
                                    },
                                <?php endforeach; ?>
                            ]
                        },
                    <?php endforeach; ?>
                ]
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
                        ticks: {
                            callback: function(value, index, values) {
                                let hours = Math.floor(value / 60 / 60);
                                let minutes = Math.floor(value / 60)  - (hours * 60);
                                let roundMminutes = (Math.round(minutes/30) * 30) % 60;
                                let time = hours.toString().padStart(2, '0') + ':' + roundMminutes.toString().padStart(2, '0');

                                return time;
                            }
                        }
					}]
				},

                tooltips: {
                    callbacks: {
                        label: (tooltipItem, chart) => {
                            const name = ' ' + chart.datasets[tooltipItem.datasetIndex].label + ': ';
                            const hours = Math.floor(tooltipItem.value / 60 / 60);
                            const minutes = Math.floor(tooltipItem.value / 60)  - (hours * 60);
                            const time = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');

                            return name + time;
                        }
                    }
                }
            }
        });
    </script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/layouts/footer.php';
