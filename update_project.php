<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Include the database connection file
include 'config.php';

// Decode the incoming JSON request
$inputData = json_decode(file_get_contents("php://input"), true);

// Check if 'id' is set in the decoded JSON data
if (isset($inputData['id']) && !empty($inputData['id'])) {
    $id = $inputData['id'];
    $user_id = $inputData['user_id'] ?? '';
$name = $inputData['name'] ?? ''; 

    // Collect other form data from the JSON input
    $project_name = isset($inputData['project_name']) ? $inputData['project_name'] : null;
    $primary_team_lead = isset($inputData['primary_team_lead']) ? $inputData['primary_team_lead'] : null;
    $secondary_team_lead = isset($inputData['secondary_team_lead']) ? $inputData['secondary_team_lead'] : null;
    $tester_name = isset($inputData['tester_name']) ? $inputData['tester_name'] : null;
    $technical_skill_stack = isset($inputData['technical_skill_stack']) ? $inputData['technical_skill_stack'] : null;
    $project_type = isset($inputData['project_type']) ? $inputData['project_type'] : null;
    $application_type = isset($inputData['application_type']) ? $inputData['application_type'] : null;
    $start_date = isset($inputData['start_date']) ? $inputData['start_date'] : null;
    $internal_end_date = isset($inputData['internal_end_date']) ? $inputData['internal_end_date'] : null;
    $client_end_date = isset($inputData['client_end_date']) ? $inputData['client_end_date'] : null;

    // Validate the id (ensure it's numeric)
    if (is_numeric($id)) {
        // Prepare the SQL query to update the project data
        $query = "UPDATE project_table SET 
                    project_name = ?, 
                    primary_team_lead = ?, 
                    secondary_team_lead = ?, 
                    tester_name = ?, 
                    technical_skill_stack = ?, 
                    project_type = ?, 
                    application_type = ?, 
                    start_date = ?, 
                    internal_end_date = ?, 
                    client_end_date = ? 
                  WHERE id = ?";

        if ($stmt = $conn->prepare($query)) {
            // Bind parameters, using "s" for string values and "i" for integer id
            $stmt->bind_param(
                "ssssssssssi",  // Corrected parameter types for binding
                $project_name,
                $primary_team_lead,
                $secondary_team_lead,
                $tester_name,
                $technical_skill_stack,
                $project_type,
                $application_type,
                $start_date,
                $internal_end_date,
                $client_end_date,
                $id // Bind the id parameter for updating the correct record
            );

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Project updated successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update project or no changes made']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to execute the SQL statement']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the SQL statement']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID not set']);
}
