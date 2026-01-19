<?php

class SceneManager {
    private $scenes = [];
    
    public function __construct() {
        global $scenesData;
        $this->scenes = $scenesData ?? [];
    }
    
    public function getAllScenes(): array {
        return $this->scenes;
    }
    
    public function getScene(string $sceneId): ?array {
        foreach ($this->scenes as $scene) {
            if ($scene['id'] === $sceneId) {
                // Ensure backward compatibility: if 'contents' exists but not 'character_contents', convert it
                if (isset($scene['contents']) && !isset($scene['character_contents'])) {
                    $scene['character_contents'] = $scene['contents'];
                    unset($scene['contents']);
                }
                return $scene;
            }
        }
        return null;
    }
    
    public function getScenesByActe(int $acte): array {
        return array_filter($this->scenes, function($scene) use ($acte) {
            return $scene['acte'] === $acte;
        });
    }
    
    public function getAllActes(): array {
        $actes = [];
        foreach ($this->scenes as $scene) {
            if (!in_array($scene['acte'], $actes)) {
                $actes[] = $scene['acte'];
            }
        }
        sort($actes);
        return $actes;
    }
}

?>

