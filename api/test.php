<?php
// echo htmlspecialchars("hello world");
header("Content-Type: application/json");

//$data = $result->fetch_assoc();
// $resultObject = 
//     "status" => "success",
//     "message" => "Hello, world!"
// ];

$products = array(
    array(
        "id" => 1,
        "product_name" => "Laptop",
        "cost" => 999
    ),
    array(
        "id" => 2,
        "product_name" => "Mouse",
        "cost" => 25
    ),
    array(
        "id" => 3,
        "product_name" => "Keyboard",
        "cost" => 49
    )
);

$resultObject = (object) array(
    'name' => 'John',
    'age' => 30,
    'city' => 'New York',
    'products' => $products
    );
//$resultObject->name = "Jane";
// $resultObject->age = 30;
// $resultObject->city = "New York";
$resultObject->student_id = "S1234567A";

echo json_encode($resultObject);
//echo json_encode(["message" => "User added successfully"]);

?>