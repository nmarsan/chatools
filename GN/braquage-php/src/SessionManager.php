<?php

class SessionManager {
    private $sessionsFile;
    
    public function __construct() {
        $this->sessionsFile = SESSIONS_PATH . '/sessions.json';
        $this->ensureSessionsFile();
    }
    
    private function ensureSessionsFile() {
        if (!file_exists($this->sessionsFile)) {
            file_put_contents($this->sessionsFile, json_encode([]));
        }
    }
    
    private function loadSessions(): array {
        $content = file_get_contents($this->sessionsFile);
        return json_decode($content, true) ?? [];
    }
    
    private function saveSessions(array $sessions): bool {
        return file_put_contents($this->sessionsFile, json_encode($sessions, JSON_PRETTY_PRINT)) !== false;
    }
    
    public function createSession(string $name): array {
        $sessions = $this->loadSessions();
        
        $newSession = [
            'id' => uniqid('session_', true),
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
            'state' => [
                'current_scene_id' => '1-1',
                'visited_scenes' => ['1-1'],
                'completed_scenes' => [],
                'scene_history' => ['1-1'],
                'character_choices' => []
            ]
        ];
        
        $sessions[] = $newSession;
        $this->saveSessions($sessions);
        
        $_SESSION['current_session_id'] = $newSession['id'];
        
        return ['success' => true, 'session' => $newSession];
    }
    
    public function loadSession(?string $sessionId): array {
        if (!$sessionId) {
            return ['success' => false, 'error' => 'No session ID provided'];
        }
        
        $sessions = $this->loadSessions();
        foreach ($sessions as $session) {
            if ($session['id'] === $sessionId) {
                $_SESSION['current_session_id'] = $sessionId;
                return ['success' => true, 'session' => $session];
            }
        }
        
        return ['success' => false, 'error' => 'Session not found'];
    }
    
    public function deleteSession(?string $sessionId): array {
        if (!$sessionId) {
            return ['success' => false, 'error' => 'No session ID provided'];
        }
        
        $sessions = $this->loadSessions();
        $sessions = array_filter($sessions, function($session) use ($sessionId) {
            return $session['id'] !== $sessionId;
        });
        
        $this->saveSessions(array_values($sessions));
        
        if (isset($_SESSION['current_session_id']) && $_SESSION['current_session_id'] === $sessionId) {
            unset($_SESSION['current_session_id']);
        }
        
        return ['success' => true];
    }
    
    public function getAllSessions(): array {
        return $this->loadSessions();
    }
    
    public function getSession(?string $sessionId): ?array {
        if (!$sessionId) {
            return null;
        }
        
        $sessions = $this->loadSessions();
        foreach ($sessions as $session) {
            if ($session['id'] === $sessionId) {
                return $session;
            }
        }
        
        return null;
    }
    
    public function saveSession(array $session): bool {
        $sessions = $this->loadSessions();
        $found = false;
        
        foreach ($sessions as $key => $s) {
            if ($s['id'] === $session['id']) {
                $sessions[$key] = $session;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $sessions[] = $session;
        }
        
        return $this->saveSessions($sessions);
    }
}

?>

