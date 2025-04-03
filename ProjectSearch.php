<?php
// ProjectSearch.php
header('Content-Type: application/json');

// Check if MongoDB extension is loaded
if (!extension_loaded('mongodb')) {
    echo json_encode([
        'status' => 'error',
        'message' => 'MongoDB extension not loaded. Please install and enable it.'
    ]);
    exit;
}

try {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    $searchTitle = $input['title'] ?? '';
    
    if (empty($searchTitle)) {
        throw new Exception("Project title is required");
    }

    // Connect to MongoDB
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    
    // Verify connection
    try {
        $command = new MongoDB\Driver\Command(['ping' => 1]);
        $manager->executeCommand('admin', $command);
    } catch (Exception $e) {
        throw new Exception("Failed to connect to MongoDB: " . $e->getMessage());
    }

    // Get all projects
    $query = new MongoDB\Driver\Query([]);
    $cursor = $manager->executeQuery('projectDB.projects', $query);
    $projects = iterator_to_array($cursor);

    // Process search term
    $searchTerms = array_unique(array_filter(
        preg_split('/\s+/', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $searchTitle)))
    ));
    
    // Common words to ignore
    $commonWords = ['the', 'and', 'of', 'in', 'to', 'a', 'is', 'for', 'on', 'with'];
    $searchTerms = array_diff($searchTerms, $commonWords);
    
    if (empty($searchTerms)) {
        throw new Exception("No meaningful search terms after filtering");
    }

    $results = [];
    foreach ($projects as $project) {
        $titleTerms = array_unique(array_filter(
            preg_split('/\s+/', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $project->title)))
        ));
        $titleTerms = array_diff($titleTerms, $commonWords);
        
        $matching = array_intersect($searchTerms, $titleTerms);
        $totalTerms = array_unique(array_merge($searchTerms, $titleTerms));
        
        if (!empty($totalTerms)) {
            $similarity = round(count($matching) / count($totalTerms) * 100, 2);
            if ($similarity > 0) {
                $results[] = [
                    'title' => $project->title,
                    'similarity' => $similarity,
                    'id' => (string)$project->_id
                ];
            }
        }
    }
    
    // Sort by similarity (highest first)
    usort($results, function($a, $b) {
        return $b['similarity'] <=> $a['similarity'];
    });

    echo json_encode([
        'status' => 'success',
        'results' => array_slice($results, 0, 5),
        'search_title' => $searchTitle
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>