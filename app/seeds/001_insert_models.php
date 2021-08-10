<?php



require_once(__DIR__ . '/../bootstrap.php');

$db = SiteSpecific::getDb();

$model1 = ModelRecord::createNew("3.01", "The 3.02 model.");
$model1->save();

$model2 = ModelRecord::createNew("3.02", "The 3.02 model.");
$model2->save();

$user1 = UserRecord::createNew("Stuart", "Page", "sdpagent@gmail.com", "password");
$user1->save();

$user2 = UserRecord::createNew("James", "Bradford", "james.bradford@irap.org", "password");
$user2->save();


$roadAttributesDataPoint = DataPointService::createNewDataPoint(
    "Road Attributes", 
    "Holds all of the road attributes, e.g. the upload file.",
    $model2,
    Safe\json_decode(file_get_contents(__DIR__ . '/assets/road-attributes-schema.json'), true), 
    Safe\json_decode(file_get_contents(__DIR__ . '/assets/core-data-before.json'), true), 
    $user1
);

$sampleMaxFlowData = array(
    [
        'point_uuid' => "93da1f68-b53f-4406-9ec3-42b6db024a4f",
        "operating_speed" => 30,
    ],
    [
        'point_uuid' => "93da1f68-b63f-4ac4-8487-a12691ea8248",
        "operating_speed" => 20,
    ]
);

$maxFlowDataPoint = DataPointService::createNewDataPoint(
    "Combined Attribute - Max Flow", 
    "Holds the combined attribute max flow, which is the higher of operating speed mean, or the 85th percentile",
    $model2,
    Safe\json_decode(file_get_contents(__DIR__ . '/assets/operating-speed-schema.json'), true), 
    $sampleMaxFlowData, 
    $user1
);

$roadAttributesDataPoint->save();
$parameters[] = new Parameter("roadAttributes", $roadAttributesDataPoint, null);



$code = '
$output = array();
    
foreach ($roadAttributes as $roadAttributesRow)
{
    $maxValue = max($roadAttributesRow["operating_speed_mean"], $roadAttributesRow["operating_speed_85th_percentile"]);
    $output[$roadAttributesRow["point_uuid"]] = ["operating_speed" => $maxValue];
}

return $output;

';


ProcessService::createNewProcess(
    "calculateOperatingSpeed", 
    "Caclulates the operating speed for each of the road data points.", 
    $model2,
    $code, 
    $user1, 
    $maxFlowDataPoint,
    ...$parameters
);




