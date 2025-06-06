<?php
require_once 'config.php';
require_once 'generate_pdf.php';

// Set response header
header('Content-Type: application/json');

// Initialize response array
$response = ['success' => false, 'message' => ''];

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $attendance = filter_input(INPUT_POST, 'attendance', FILTER_SANITIZE_STRING);

    // Validate inputs
    if (empty($lastName) || empty($firstName) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || $attendance !== 'yes') {
        $response['message'] = 'Veuillez remplir tous les champs correctement et confirmer votre présence.';
        echo json_encode($response);
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM registrations WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $response['message'] = 'Cet email est déjà enregistré.';
        echo json_encode($response);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO registrations (last_name, first_name, email, attendance) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $lastName, $firstName, $email, $attendance);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Inscription confirmée ! Votre carte d\'accès est en cours de téléchargement...';
        
        
    } else {
        $response['message'] = 'Erreur lors de l\'enregistrement : ' . $conn->error;
    }
    
    $stmt->close();
} else {
    $response['message'] = 'Méthode de requête non autorisée.';
}

$conn->close();
echo json_encode($response);
?>