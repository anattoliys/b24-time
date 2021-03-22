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

    $users[$key]['monthTime'] = Time::getUserMonthTime($user['id']);
    $users[$key]['color'] = $rgbColor;
}

$firstTimeItemDate = $users[0]['monthTime'][0]['date'];
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
                                        y: moment('<?php echo $time["dayTime"] ?>', '<?php echo $timeFormat ?>'),
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
