<?php

class GameManager {
    private $sessionManager;
    private $sceneManager;
    private $currentSession = null;
    
    public function __construct() {
        $this->sessionManager = new SessionManager();
        $this->sceneManager = new SceneManager();
        $this->loadCurrentSession();
    }
    
    private function loadCurrentSession() {
        $sessionId = $_SESSION['current_session_id'] ?? null;
        if ($sessionId) {
            $this->currentSession = $this->sessionManager->getSession($sessionId);
        }
    }
    
    public function getCurrentSession(): ?array {
        return $this->currentSession;
    }
    
    public function getCurrentScene(): ?array {
        if (!$this->currentSession) {
            return null;
        }
        
        $sceneId = $this->currentSession['state']['current_scene_id'] ?? '1-1';
        return $this->sceneManager->getScene($sceneId);
    }
    
    public function goToScene(?string $sceneId, ?string $choiceId = null): array {
        if (!$this->currentSession || !$sceneId) {
            return ['success' => false, 'error' => 'No session or scene ID'];
        }
        
        // Update session state
        $this->currentSession['state']['current_scene_id'] = $sceneId;
        
        if (!in_array($sceneId, $this->currentSession['state']['visited_scenes'])) {
            $this->currentSession['state']['visited_scenes'][] = $sceneId;
        }
        
        if (!in_array($sceneId, $this->currentSession['state']['scene_history'])) {
            $this->currentSession['state']['scene_history'][] = $sceneId;
        }
        
        if ($choiceId) {
            $previousSceneId = end($this->currentSession['state']['scene_history']);
            $this->currentSession['state']['character_choices'][$previousSceneId] = $choiceId;
        }
        
        $this->sessionManager->saveSession($this->currentSession);
        $this->loadCurrentSession();
        
        return ['success' => true, 'scene' => $this->getCurrentScene()];
    }
    
    public function goBack(): array {
        if (!$this->currentSession) {
            return ['success' => false, 'error' => 'No session'];
        }
        
        $history = $this->currentSession['state']['scene_history'];
        if (count($history) <= 1) {
            return ['success' => false, 'error' => 'Cannot go back'];
        }
        
        // Remove current scene from history
        array_pop($history);
        $previousSceneId = end($history);
        
        $this->currentSession['state']['current_scene_id'] = $previousSceneId;
        $this->currentSession['state']['scene_history'] = $history;
        
        $this->sessionManager->saveSession($this->currentSession);
        $this->loadCurrentSession();
        
        return ['success' => true, 'scene' => $this->getCurrentScene()];
    }
    
    public function completeScene(?string $sceneId): array {
        if (!$this->currentSession || !$sceneId) {
            return ['success' => false, 'error' => 'No session or scene ID'];
        }
        
        if (!in_array($sceneId, $this->currentSession['state']['completed_scenes'])) {
            $this->currentSession['state']['completed_scenes'][] = $sceneId;
            $this->sessionManager->saveSession($this->currentSession);
            $this->loadCurrentSession();
        }
        
        return ['success' => true];
    }
    
    public function uncompleteScene(?string $sceneId): array {
        if (!$this->currentSession || !$sceneId) {
            return ['success' => false, 'error' => 'No session or scene ID'];
        }
        
        $this->currentSession['state']['completed_scenes'] = array_filter(
            $this->currentSession['state']['completed_scenes'],
            function($id) use ($sceneId) {
                return $id !== $sceneId;
            }
        );
        
        $this->sessionManager->saveSession($this->currentSession);
        $this->loadCurrentSession();
        
        return ['success' => true];
    }
    
    public function isSceneAccessible(string $sceneId): bool {
        if (!$this->currentSession) {
            return $sceneId === '1-1';
        }
        
        // First scene is always accessible
        if ($sceneId === '1-1') {
            return true;
        }
        
        // Check if scene has been visited
        if (in_array($sceneId, $this->currentSession['state']['visited_scenes'])) {
            return true;
        }
        
        // Check if any visited scene has a choice leading to this scene
        $allScenes = $this->sceneManager->getAllScenes();
        foreach ($this->currentSession['state']['visited_scenes'] as $visitedSceneId) {
            $visitedScene = $this->sceneManager->getScene($visitedSceneId);
            if ($visitedScene && isset($visitedScene['choices'])) {
                foreach ($visitedScene['choices'] as $choice) {
                    if (isset($choice['target_scene_id']) && $choice['target_scene_id'] === $sceneId) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}

?>

