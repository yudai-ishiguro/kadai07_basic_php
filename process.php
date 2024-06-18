<?php
// process.php

// 受け取ったデータを表示
echo "<pre>";
var_dump($_POST);
echo "</pre>";

// 送信されたデータの中身を確認
if (isset($_POST['location']) && isset($_POST['wind-direction'])) {
    $location = $_POST['location'];
    $windDirection = $_POST['wind-direction'];

    echo "場所: " . htmlspecialchars($location, ENT_QUOTES, 'UTF-8') . "<br>";
    echo "風向き: " . htmlspecialchars($windDirection, ENT_QUOTES, 'UTF-8') . "<br>";

    // CSVファイルを読み込む
    $csvFile = 'wind_data.csv';

    if (file_exists($csvFile)) {
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            $rating = "該当する評価が見つかりません。";

            // CSVファイルの各行を読み込む
            while (($data = fgetcsv($handle)) !== FALSE) {
                // CSVファイルの各行の形式は: 場所, 風向き, 評価
                if (count($data) >= 3) {
                    $csvLocation = trim($data[0]);
                    $csvWindDirection = trim($data[1]);
                    $csvRating = trim($data[2]);

                    // 場所と風向きが一致する行を見つける
                    if ($csvLocation === $location && $csvWindDirection === $windDirection) {
                        $rating = $csvRating;
                        break;
                    }
                }
            }
            fclose($handle);

            // 評価を表示する
            echo "評価: " . htmlspecialchars($rating, ENT_QUOTES, 'UTF-8') . "<br>";
        } else {
            echo "CSVファイルを開けませんでした。";
        }
    } else {
        echo "CSVファイルが見つかりません。";
    }
} else {
    echo "必要なデータが送信されていません。";
}
?>