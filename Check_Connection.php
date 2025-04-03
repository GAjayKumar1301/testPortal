<?php
// Check_Connection.php
header('Content-Type: text/html; charset=UTF-8');

// Check if MongoDB extension is loaded
if (!extension_loaded('mongodb')) {
    die("<h2>Error: MongoDB extension not loaded. Please install and enable it.</h2>");
}

try {
    // Connect to MongoDB
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    
    // Verify connection
    try {
        $command = new MongoDB\Driver\Command(['ping' => 1]);
        $manager->executeCommand('admin', $command);
        echo "<h2>MongoDB Connection Successful!</h2>";
    } catch (Exception $e) {
        throw new Exception("Failed to connect to MongoDB: " . $e->getMessage());
    }

    // Check if database exists
    $command = new MongoDB\Driver\Command(['listDatabases' => 1]);
    $databases = $manager->executeCommand('admin', $command)->toArray()[0]->databases;
    
    $dbExists = false;
    foreach ($databases as $db) {
        if ($db->name == 'projectDB') {
            $dbExists = true;
            break;
        }
    }
    
    if (!$dbExists) {
        throw new Exception("Database 'projectDB' not found. Please create it first.");
    }
    
    // Get sample projects
    $filter = [];
    $options = ['limit' => 5];
    $query = new MongoDB\Driver\Query($filter, $options);
    $projects = $manager->executeQuery('projectDB.projects', $query)->toArray();
    
    // Display results
    echo "<h3>Sample Projects:</h3>";
    echo "<ul>";
    foreach ($projects as $project) {
        echo "<li><strong>" . htmlspecialchars($project->title) . "</strong>";
        echo " (ID: " . htmlspecialchars($project->_id) . ")";
        echo "</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    die("<h2>Error: " . htmlspecialchars($e->getMessage()) . "</h2>");
}
?>