<?php

// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow requests from frontend
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json");


header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header("Content-Type: application/json"); 


include "config.php";

// Decode JSON body
$inputData = json_decode(file_get_contents("php://input"), true);

// Extract and sanitize inputs
$user_id = $inputData['user_id'] ?? '';
$name = $inputData['name'] ?? ''; // ✅ Should match key from frontend
$project_name = $inputData['project_name'] ?? '';
$primary_team_lead = $inputData['primary_team_lead'] ?? '';
$secondary_team_lead = $inputData['secondary_team_lead'] ?? '';
$tester_name = $inputData['tester_name'] ?? '';
$start_date = $inputData['start_date'] ?? '';
$internal_end_date = $inputData['internal_end_date'] ?? '';
$client_end_date = $inputData['client_end_date'] ?? '';
$technical_skill_stack = $inputData['technical_skill_stack'] ?? '';
$project_type = $inputData['project_type'] ?? '';
$application_type = $inputData['application_type'] ?? '';


// Basic validation
if (empty($project_name) || empty($primary_team_lead) || empty($start_date)) {
    echo json_encode(["status" => "error", "message" => "Required fields are missing."]);
    exit;
}

// Insert into DB
$sql = "INSERT INTO project_table (
    user_id,
    name,
    project_name,
    primary_team_lead,
    secondary_team_lead,
    tester_name,
    start_date,
    internal_end_date,
    client_end_date,
    technical_skill_stack,
    project_type,
    application_type
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param(
    "isssssssssss",
    $user_id,
    $name,
    $project_name,
    $primary_team_lead,
    $secondary_team_lead,
    $tester_name,
    $start_date,
    $internal_end_date,
    $client_end_date,
    $technical_skill_stack,
    $project_type,
    $application_type
);

// if ($stmt->execute()) {
//     echo json_encode(["status" => "success", "message" => "Project added successfully."]);
// } else {
//     echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
// }


if ($stmt->execute()) {
    // Success
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Project added successfully."]);
} else {
    // Error
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
}


$stmt->close();
$conn->close();
?>