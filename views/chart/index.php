<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/layouts/header.php';

$dbTime = Time::getTime();

$hours = [];
$monthName = date('F');
$dbDayTime = array_column($dbTime, 'dayTime');
foreach ($dbDayTime as $item) {
    $hours[] = date('h', strtotime($item));
}
$hours = implode(', ', $hours);

$day = [];
$dbDate = array_column($dbTime, 'date');
foreach ($dbDate as $item) {
    $day[] = date('d', strtotime($item));
}
$day = implode(', ', $day);
?>
    <link rel="stylesheet" href="/resources/css/Chart.min.css">

    <canvas id="timeChart"></canvas>

    <script src="/resources/js/Chart.min.js"></script>

    <script>
        var ctx = document.getElementById('timeChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php echo $day; ?>],
                datasets: [{
                    label: 'Time is',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [<?php echo $hours; ?>]
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Day of <?php echo $monthName; ?>'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Hours'
                        },
                    }]
                }
            }
        });
    </script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/views/layouts/footer.php';
