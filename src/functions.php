<?php
/**
 * @param $filePath
 */
function task1($filePath)
{
    $xml = simplexml_load_file($filePath);
    $xmlAttrib = [];
    foreach ($xml->attributes() as $key => $value) {
        $xmlAttrib[$key] = $value;
    }
    $productAttrib = [];
    foreach ($xml->Items->Item as $item) {
        foreach ($item->attributes() as $value) {
            $productAttrib[]=$value;
        }
    }
    echo "<h1>Заказ на поставку продукции № ".$xmlAttrib["PurchaseOrderNumber"]. " от ".$xmlAttrib["OrderDate"]."</h1>";
    echo "<h2>Примечание : ".$xml->DeliveryNotes." </h2>";
    $length = count($xml->Address);
    for ($i=0; $i < $length; $i++) {
        echo "<h3>Receiver of product: ".$xml->Address[$i]->Name."</h3>
        <h4>Product: #$productAttrib[$i]</h4>
        <ol>
            <li>ProductName: ".$xml->Items->Item[$i]->ProductName."</li>
            <li>Quantity: ".$xml->Items->Item[$i]->Quantity."</li>
            <li>USPrice: ".$xml->Items->Item[$i]->USPrice."</li>
            <li>ShipDate: ".$xml->Items->Item[$i]->ShipDate."</li>
            <li>Comment: ".$xml->Items->Item[$i]->Comment."</li>            
        </ol>
        <h4>Delivery address:</h4>
        <ol>
            <li>Country: ".$xml->Address[$i]->Country."</li>
            <li>City: ".$xml->Address[$i]->City."</li>
            <li>State: ".$xml->Address[$i]->State."</li>
            <li>Street: ".$xml->Address[$i]->Street."</li>
            <li>Zip: ".$xml->Address[$i]->Zip."</li>
        </ol>";
    }
}

function task2()
{
    $arr = ["one" => 1, "two" => 2, "three" => ["one" => 15, "two" => 44]];
    $json_str = json_encode($arr, JSON_PRETTY_PRINT);
    $file = fopen("output.json", "w");
    fwrite($file, $json_str);
    fclose($file);
    $json_cont = file_get_contents("output.json");
    $json_result = json_decode($json_cont, true);
    function changeArr($arr, $change)
    {
        if ($change) {
            $arr = array_map(function ($item) {
                if (!is_array($item)) {
                    return ($item * 2);
                } else {
                    return $item;
                }
            }, $arr);
        }
        return $arr;
    }
    $json_str = json_encode(changeArr($json_result, (bool)rand(0, 1)), JSON_PRETTY_PRINT);
    $file = fopen("output2.json", "w");
    fwrite($file, $json_str);
    fclose($file);
    $json_cont = file_get_contents("output.json");
    $reult1 = json_decode($json_cont, true);
    $json_cont = file_get_contents("output2.json");
    $reult2 = json_decode($json_cont, true);
    $key1 = array_keys($reult1);
    $key2 = array_keys($reult2);
    $countDeff = 0;
    for ($i=0; $i<count($reult1); $i++) {
        if ($reult1[$key1[$i]] !== $reult2[$key2[$i]]) {
            echo "Значение ключа $key1[$i] первого массива не равен значению ключа $key2[$i] второго массива!<br>";
            $countDeff++;
        }
    }
    if ($countDeff==0) {
        echo "Массивы равны";
    }
}




function task3()
{
    $arr = [];
    for ($i=0; $i<75; $i++) {
        $arr[] = rand(1, 100);
    }
    $file = fopen('file.csv', 'w');
    fputcsv($file, $arr);
    fclose($file);
    if (($handle = fopen("file.csv", "r")) !== false) {
        while (($data = fgetcsv($handle)) !== false) {
            $num = count($data);
            $sum = 0;
            for ($i=0; $i < $num; $i++) {
                if ($data[$i]%2 == 0) {
                    $sum+= $data[$i];
                }
            }
            echo "Сумма четных чисел в файле .csv = $sum";
        }
        fclose($handle);
    }
}

function task4()
{
    $url = 'https://en.wikipedia.org/w/api.php?action=query&titles=Main%20Page&prop=revisions&rvprop=content&format=json';
    $result = json_decode(file_get_contents($url), true);
    function find_node($dataSet, $id)
    {
        foreach ($dataSet as $key => $value) {
            if ($key === $id) {
                return $value;
            } else {
                if (is_array($value)) {
                    $result = find_node($value, $id);
                    if ($result) {
                        return $result; // выход из рекурсии
                    }
                }
            }
        }
    }
    $id = 'title';
    echo "Значение ключа $id = ".find_node($result, $id)."<br />";
    $id = 'pageid';
    echo "Значение ключа $id = ".find_node($result, $id);
}
