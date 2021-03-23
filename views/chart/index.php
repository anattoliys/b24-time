<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/layouts/header.php';

$data = [];
$userObj = new User;
$users = $userObj->getAll();
$dateFormat = 'YYYY-MM-DD';
$timeFormat = 'HH:mm:ss';

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
        $users[$key]['monthTime'][$k]['hours'] = explode(':', $time['dayTime'])[0];
        $users[$key]['monthTime'][$k]['minutes'] = explode(':', $time['dayTime'])[1];
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
                            borderColor: 'rgba(<?php echo $user['color'] ?> .6)',
                            label: '<?php echo $user["name"] ?>',
                            data: [
                                <?php foreach ($user['monthTime'] as $time): ?>
                                    {
                                        x: moment('<?php echo $time["date"] ?>', '<?php echo $dateFormat ?>'),
                                        y: '<?php echo $time['hours'] ?>',
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
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        }
					}]
				},

                tooltips: {
                    callbacks: {
                        label: (tooltipItem, chart) => {
                            const name = ' ' + chart.datasets[tooltipItem.datasetIndex].label + ': ';
                            const hours = tooltipItem.value + ':';
                            const minutes = chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].minutes;

                            return name + hours + minutes;
                        }
                    }
                }
            }
        });
    </script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/layouts/footer.php';
