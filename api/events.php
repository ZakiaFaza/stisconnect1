<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$search_term = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT id, name, description, event_date, recruitment_open FROM events";

if (!empty($search_term)) {
    // Menggunakan prepared statement untuk keamanan
    $sql .= " WHERE name LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_term = "%" . $search_term . "%";
    $stmt->bind_param("ss", $like_term, $like_term);
} else {
    $sql .= " ORDER BY event_date DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);

$stmt->close();
$conn->close();