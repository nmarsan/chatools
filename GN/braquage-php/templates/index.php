<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Braquage - Gestionnaire de Partie</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>Braquage - Gestionnaire de Partie</h1>
            <div class="session-controls">
                <button id="new-session-btn" class="btn btn-primary">Nouvelle Partie</button>
                <select id="session-select" class="session-select">
                    <option value="">Choisir une partie...</option>
                    <?php foreach ($sessions as $session): ?>
                        <option value="<?= htmlspecialchars($session['id']) ?>" <?= ($currentSession && $currentSession['id'] === $session['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($session['name']) ?> (<?= date('d/m/Y H:i', strtotime($session['created_at'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($currentSession): ?>
                    <button id="delete-session-btn" class="btn btn-danger" data-session-id="<?= htmlspecialchars($currentSession['id']) ?>">Supprimer</button>
                <?php endif; ?>
            </div>
        </header>

        <div class="main-content">
            <!-- Left Panel: Scene Content -->
            <div class="scene-panel">
                <?php if ($currentScene): ?>
                    <div class="scene-header">
                        <h2>Acte <?= $currentScene['acte'] ?>, Scène <?= $currentScene['scene'] ?></h2>
                        <div class="scene-id">ID: <?= htmlspecialchars($currentScene['id']) ?></div>
                    </div>

                    <!-- Character Tabs -->
                    <?php if (!empty($currentScene['contents'])): ?>
                        <div class="character-tabs">
                            <?php 
                            $characters = ['Alex', 'Charlie', 'Camille', 'Andréa'];
                            $sceneCharacters = array_unique(array_column($currentScene['contents'], 'character'));
                            ?>
                            <?php foreach ($characters as $char): ?>
                                <?php if (in_array($char, $sceneCharacters)): ?>
                                    <button class="tab-btn <?= $char === $sceneCharacters[0] ? 'active' : '' ?>" data-character="<?= htmlspecialchars($char) ?>">
                                        <?= htmlspecialchars($char) ?>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Character Content -->
                        <div class="character-content">
                            <?php foreach ($currentScene['contents'] as $index => $content): ?>
                                <div class="character-panel <?= $index === 0 ? 'active' : '' ?>" data-character="<?= htmlspecialchars($content['character']) ?>">
                                    <?php if (!empty($content['introduction'])): ?>
                                        <div class="introduction">
                                            <h3>Introduction</h3>
                                            <p><?= nl2br(htmlspecialchars($content['introduction'])) ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($content['information'])): ?>
                                        <div class="information">
                                            <h3>Information</h3>
                                            <p><?= nl2br(htmlspecialchars($content['information'])) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-content">Cette scène n'a pas de contenu.</div>
                    <?php endif; ?>

                    <!-- Choices -->
                    <?php if (!empty($currentScene['choices'])): ?>
                        <div class="choices-panel">
                            <h3>Choix possibles</h3>
                            <div class="choices-list">
                                <?php foreach ($currentScene['choices'] as $choice): ?>
                                    <button class="choice-btn" data-choice-id="<?= htmlspecialchars($choice['id']) ?>" data-target-scene="<?= htmlspecialchars($choice['target_scene_id']) ?>">
                                        <?= htmlspecialchars($choice['description']) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="no-choices">Aucun choix disponible pour cette scène.</div>
                    <?php endif; ?>

                    <!-- Navigation -->
                    <div class="navigation-panel">
                        <button id="go-back-btn" class="btn btn-secondary" <?= (!$currentSession || count($currentSession['state']['scene_history']) <= 1) ? 'disabled' : '' ?>>
                            ← Retour en arrière
                        </button>
                    </div>
                <?php else: ?>
                    <div class="no-session">
                        <p>Créez ou chargez une partie pour commencer.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Panel: Scene Tree -->
            <div class="tree-panel">
                <h2>Arbre des scènes</h2>
                <div class="scene-tree">
                    <?php
                    $actes = array_unique(array_column($allScenes, 'acte'));
                    sort($actes);
                    $visitedScenes = $currentSession ? $currentSession['state']['visited_scenes'] : [];
                    $completedScenes = $currentSession ? $currentSession['state']['completed_scenes'] : [];
                    $currentSceneId = $currentSession ? $currentSession['state']['current_scene_id'] : null;
                    ?>
                    <?php foreach ($actes as $acte): ?>
                        <div class="acte-group">
                            <h3>Acte <?= $acte ?></h3>
                            <?php
                            $scenesInActe = array_filter($allScenes, function($s) use ($acte) {
                                return $s['acte'] === $acte;
                            });
                            usort($scenesInActe, function($a, $b) {
                                return $a['scene'] <=> $b['scene'];
                            });
                            ?>
                            <?php foreach ($scenesInActe as $scene): ?>
                                <?php
                                $isVisited = in_array($scene['id'], $visitedScenes);
                                $isCompleted = in_array($scene['id'], $completedScenes);
                                $isCurrent = $scene['id'] === $currentSceneId;
                                $isAccessible = $currentSession ? $gameManager->isSceneAccessible($scene['id']) : ($scene['id'] === '1-1');
                                ?>
                                <div class="scene-node <?= $isCurrent ? 'current' : '' ?> <?= $isCompleted ? 'completed' : '' ?> <?= !$isVisited ? 'not-visited' : '' ?> <?= !$isAccessible ? 'not-accessible' : '' ?>"
                                     data-scene-id="<?= htmlspecialchars($scene['id']) ?>">
                                    <span class="scene-label">
                                        Scène <?= $scene['scene'] ?>
                                        <?php if ($isCurrent): ?>
                                            <span class="current-indicator">●</span>
                                        <?php endif; ?>
                                    </span>
                                    <?php if ($isCompleted): ?>
                                        <button class="uncomplete-btn" data-scene-id="<?= htmlspecialchars($scene['id']) ?>" title="Marquer comme non complétée">✓</button>
                                    <?php elseif ($isVisited): ?>
                                        <button class="complete-btn" data-scene-id="<?= htmlspecialchars($scene['id']) ?>" title="Marquer comme complétée">○</button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for new session -->
    <div id="new-session-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Nouvelle Partie</h2>
            <form id="new-session-form">
                <label for="session-name">Nom de la partie:</label>
                <input type="text" id="session-name" name="name" value="Nouvelle partie" required>
                <button type="submit" class="btn btn-primary">Créer</button>
            </form>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>

