<?php
session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/GameManager.php';
require_once __DIR__ . '/src/SceneManager.php';
require_once __DIR__ . '/src/SessionManager.php';

$gameManager = new GameManager();
$sceneManager = new SceneManager();
$sessionManager = new SessionManager();

// Handle AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'create_session':
            $name = $_POST['name'] ?? 'Nouvelle partie';
            echo json_encode($sessionManager->createSession($name));
            exit;
            
        case 'load_session':
            $sessionId = $_POST['session_id'] ?? null;
            echo json_encode($sessionManager->loadSession($sessionId));
            exit;
            
        case 'delete_session':
            $sessionId = $_POST['session_id'] ?? null;
            echo json_encode($sessionManager->deleteSession($sessionId));
            exit;
            
        case 'go_to_scene':
            $sceneId = $_POST['scene_id'] ?? null;
            $choiceId = $_POST['choice_id'] ?? null;
            echo json_encode($gameManager->goToScene($sceneId, $choiceId));
            exit;
            
        case 'go_back':
            echo json_encode($gameManager->goBack());
            exit;
            
        case 'complete_scene':
            $sceneId = $_POST['scene_id'] ?? null;
            echo json_encode($gameManager->completeScene($sceneId));
            exit;
            
        case 'uncomplete_scene':
            $sceneId = $_POST['scene_id'] ?? null;
            echo json_encode($gameManager->uncompleteScene($sceneId));
            exit;
            
        case 'get_current_scene':
            echo json_encode($gameManager->getCurrentScene());
            exit;
            
        case 'get_sessions':
            echo json_encode($sessionManager->getAllSessions());
            exit;
    }
}

// Get current session
$currentSession = $gameManager->getCurrentSession();
$currentScene = $currentSession ? $gameManager->getCurrentScene() : null;
$allScenes = $sceneManager->getAllScenes();
$sessions = $sessionManager->getAllSessions();

include __DIR__ . '/templates/index.php';
?>

