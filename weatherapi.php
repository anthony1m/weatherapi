<?php

$weather = "";
$error = "";
function getWeatherDataForDay($city) {
    $apiKey = '312e11c0aedda0df0a87452297fc661e'; // Replace with your OpenWeatherMap API key
    $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . $apiKey;
    $response = file_get_contents($url);

    if ($response) {
        $weatherArray = json_decode($response, true);
        return $weatherArray;
    }
    
    return null;
}

if (isset($_GET['city'])) {
    $urlContents = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($_GET['city']) . "&appid=312e11c0aedda0df0a87452297fc661e");

    $weatherArray = json_decode($urlContents, true);

    if ($weatherArray['cod'] == 200) {
        $weather = "The weather in " . $_GET['city'] . " is currently '" . $weatherArray['weather'][0]['description'] . "'. ";
        $tempInCelcius = intval($weatherArray['main']['temp'] - 273.15);
        $weather .= " The temperature is " . $tempInCelcius . "&deg;C and the wind speed is " . $weatherArray['wind']['speed'] . "m/s.";
    } else {
        $error = "Could not find the city - please try again.";
    }
}

$days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
$dayWeatherData = [];

foreach ($days as $day) {
    $dayWeather = getWeatherDataForDay($day);
    if ($dayWeather) {
        $dayWeatherData[$day] = $dayWeather;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: url('phot1.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .first-card {
            opacity: 0.9;
        }
    </style>
    <title>Weather App</title>
</head>

<body>
    <section class="first-card">
        <div class="container">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Weather App</h5>
                    </div>
                    <div class="modal-body">
                        <form action="" method="GET">
                            <div class="mb-3">
                                <label for="city" class="form-label">Enter your city name</label>
                                <input type="text" class="form-control" name="city" id="city" placeholder="Eg. London, Tokyo">
                            </div>
                            <button type="submit" class="btn btn-secondary">Submit</button>
                        </form>
                        <div id="weather">
                            <?php
                            if ($weather) {
                                echo '<div class="p-2 m-2 alert alert-info" role="alert">' . $weather . '</div>';
                            } else if ($error) {
                                echo '<div class="p-2 m-2 alert alert-danger" role="alert">' . $error . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="first-card">
    <div class="container">
        <div class="row">
            <?php
            foreach ($days as $day) {
                $dayWeather = $dayWeatherData[$day];
            ?>
                <div class="col-md-2">
                    <div class="card mb-4">
                        <img src="weatherpic.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?= $day ?></h5>
                            <p class="card-text">
                                <p>Temperature: <?= $dayWeather['temp']['temp_min'] ?> &deg;C</p>
                                <p>Wind speed: <?= $dayWeather['wind']['speed'] ?> m/s</p>
                                <p>Humidity: <?= $dayWeather['main']['humidity'] ?>%</p>
                                <p>Sunrise: <?= date("h:i A", $dayWeather['sys']['sunrise']) ?></p>
                                <p>Sunset: <?= date("h:i A", $dayWeather['sys']['sunset']) ?></p>
                                <p>Wind: <?= $dayWeather['wind']['deg'] ?>&deg;</p>
                                <p>Speed: <?= $dayWeather['wind']['speed'] ?> m/s</p>
                            </p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>