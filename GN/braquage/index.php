<?php
session_start();

// ============================================================================
// 1. CONFIG ET DONNÉES
// ============================================================================
$state_file = 'gamestate.json';
$scenario_file = 'scenario.json';
$users_file = 'users.json'; 

// Création fichiers par défaut (si inexistants)
if (!file_exists($users_file)) {
    $default = ['nath'=>['pass'=>'chef','role'=>'orga'], 'alex'=>['pass'=>'joueur','role'=>'alex']];
    file_put_contents($users_file, json_encode($default), LOCK_EX);
}
if (!file_exists($state_file)) {
    file_put_contents($state_file, json_encode(["scene" => "1", "history" => [], "flags" => [], "genres" => ["alex"=>"M", "andrea"=>"M", "camille"=>"F", "charlie"=>"F"]]), LOCK_EX);
}

// Chargement des données
$users_db = json_decode(file_get_contents($users_file), true);
$state = json_decode(file_get_contents($state_file), true);
$scenarios = json_decode(file_get_contents($scenario_file), true);

// Sécurité genre
if (!isset($state['genres'])) {
    $state['genres'] = ["alex"=>"M", "andrea"=>"M", "camille"=>"F", "charlie"=>"F"];
}

// ============================================================================
// 2. GESTION DES THÈMES
// ============================================================================
if (isset($_GET['set_theme'])) {
    setcookie('app_theme', $_GET['set_theme'], time() + (86400 * 30), "/");
    header("Location: index.php");
    exit;
}
$current_theme = $_COOKIE['app_theme'] ?? 'theme-dark';

// ============================================================================
// 3. EXPORT PDF
// ============================================================================
if (isset($_GET['mode']) && $_GET['mode'] === 'export_view' && isset($_SESSION['role']) && $_SESSION['role'] === 'orga') {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Rapport - Braquage</title>
        <style>
            body { font-family: 'Segoe UI', Helvetica, Arial, sans-serif; padding: 40px; color: #1a1a1a; background: #fff; max-width: 800px; margin: 0 auto; line-height: 1.5; }
            .header { border-bottom: 3px solid #000; padding-bottom: 20px; margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end; }
            h1 { margin: 0; font-size: 2.5em; text-transform: uppercase; letter-spacing: 2px; }
            .meta { color: #666; font-size: 0.9em; text-align: right; }
            .timeline { position: relative; padding-left: 30px; border-left: 3px solid #e0e0e0; margin-left: 10px; }
            .event { position: relative; margin-bottom: 30px; padding-left: 20px; }
            .dot { position: absolute; left: -38px; top: 5px; width: 16px; height: 16px; background: #fff; border: 3px solid #e74c3c; border-radius: 50%; box-shadow: 0 0 0 3px #fff; }
            .time { font-weight: bold; color: #e74c3c; font-size: 0.85em; margin-bottom: 4px; }
            .movement { font-size: 1.1em; margin-bottom: 8px; font-weight: 600; color: #333; }
            .movement span { font-weight: 400; color: #777; }
            .action-box { background: #f8f9fa; border-left: 4px solid #333; padding: 10px 15px; font-style: italic; color: #555; border-radius: 0 4px 4px 0; }
            .btn-print { position: fixed; top: 20px; right: 20px; background: #333; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
            .btn-print:hover { background: #000; }
            @media print {
                .no-print { display: none; }
                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; padding: 0; }
                .timeline { border-color: #ccc; }
            }
        </style>
    </head>
    <body>
        <button onclick="window.print()" class="btn-print no-print">🖨️ IMPRIMER / PDF</button>
        <div class="header">
            <h1>BRAQUAGE</h1>
            <div class="meta">Rapport de session<br>Date : <?php echo date('d/m/Y'); ?></div>
        </div>
        <div class="timeline">
            <?php foreach($state['history'] as $h): 
                $titre_from = $scenarios[$h['from']]['titre'] ?? 'Scène '.$h['from'];
                $titre_to = $scenarios[$h['to']]['titre'] ?? 'Scène '.$h['to'];
            ?>
            <div class="event">
                <div class="dot"></div>
                <div class="time"><?php echo $h['time']; ?></div>
                <div class="movement"><span>De</span> <?php echo $titre_from; ?> <span>vers</span> <?php echo $titre_to; ?></div>
                <div class="action-box">"<?php echo htmlspecialchars($h['text']); ?>"</div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if(empty($state['history'])): ?><p style="text-align:center; color:#999; font-style:italic;">Aucun historique disponible.</p><?php endif; ?>
    </body>
    </html>
    <?php exit;
}

// ============================================================================
// 4. ACTIONS LOGIN & ADMIN
// ============================================================================
if (isset($_POST['login'])) {
    $u = $_POST['username'] ?? ''; $p = $_POST['password'] ?? '';
    if (isset($users_db[$u]) && $users_db[$u]['pass'] === $p) {
        $_SESSION['user'] = $u; $_SESSION['role'] = $users_db[$u]['role'];
        header("Location: index.php"); exit;
    }
}
if (isset($_GET['logout'])) { session_destroy(); header("Location: index.php"); exit; }

$user_id = $_SESSION['user'] ?? null;
$user_role = $_SESSION['role'] ?? null;
$is_admin = ($user_role === 'orga');

if ($is_admin && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // NAVIGATION STANDARD
    if (isset($_POST['target_scene'])) {
        $state['history'][] = ['from' => $state['scene'], 'to' => $_POST['target_scene'], 'text' => $_POST['choice_text'], 'time' => date('H:i')];
        $state['scene'] = $_POST['target_scene'];
        $new_flags = json_decode($_POST['set_flags'], true);
        if($new_flags) foreach ($new_flags as $f => $v) { $state['flags'][$f] = $v; }
        file_put_contents($state_file, json_encode($state), LOCK_EX);
    }

    // SAUT DE SCENE FORCÉ
    if (isset($_POST['force_scene'])) {
        $target = $_POST['scene_id'];
        $state['history'][] = ['from' => $state['scene'], 'to' => $target, 'text' => '⚠️ SAUT MANUEL (MJ)', 'time' => date('H:i')];
        $state['scene'] = $target;
        file_put_contents($state_file, json_encode($state), LOCK_EX);
    }

    // ANNULATION (UNDO)
    if (isset($_POST['undo_last'])) {
        if (!empty($state['history'])) {
            $last_move = array_pop($state['history']); 
            $state['scene'] = $last_move['from'];
            file_put_contents($state_file, json_encode($state), LOCK_EX);
        }
    }

    if (isset($_POST['reset_game'])) {
        $state = ["scene" => "1", "history" => [], "flags" => [], "genres" => ["alex"=>"M", "andrea"=>"M", "camille"=>"F", "charlie"=>"F"]];
        file_put_contents($state_file, json_encode($state), LOCK_EX);
    }
    if (isset($_POST['set_genre'])) {
        $state['genres'][$_POST['p_id']] = $_POST['set_genre'];
        file_put_contents($state_file, json_encode($state), LOCK_EX);
    }
    if (isset($_POST['change_pw'])) {
        $users_db[$_POST['u_target']]['pass'] = $_POST['new_pw'];
        file_put_contents($users_file, json_encode($users_db, JSON_PRETTY_PRINT), LOCK_EX);
        $msg_success = "Mot de passe modifié !";
    }
}

$current_scene = $scenarios[$state['scene']] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Braquage - App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* VARIABLES */
        :root {
            --bg: #111827; --surface: #1f2937; --primary: #ef4444; --secondary: #3b82f6; --text: #f3f4f6; --text-muted: #9ca3af;
            --btn-text: #ffffff; --border: #374151; --shadow: rgba(0,0,0,0.3);
            --font-main: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        /* THEMES */
        body.theme-light { --bg: #f8fafc; --surface: #ffffff; --primary: #be123c; --secondary: #2563eb; --text: #0f172a; --text-muted: #64748b; --border: #e2e8f0; --shadow: rgba(0,0,0,0.05); }
        body.theme-gold { --bg: #000000; --surface: #111111; --primary: #d4af37; --secondary: #c5a059; --text: #e5e5e5; --text-muted: #666; --border: #333; --btn-text: #000; }
        body.theme-neon { --bg: #0b0214; --surface: #160626; --primary: #d946ef; --secondary: #22d3ee; --text: #e0e0e0; --text-muted: #9385ad; --border: #4a1d96; --btn-text: #ffffff; }

        body { font-family: var(--font-main); background: var(--bg); color: var(--text); margin: 0; padding: 0; padding-bottom: 50px; }
        
        /* LOGIN */
        .login-wrap { height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px; text-align: center; }
        .login-input { width: 100%; max-width: 300px; padding: 15px; margin-bottom: 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: var(--text); font-size: 16px; }
        
        /* HEADER */
        header { background: var(--surface); border-bottom: 1px solid var(--border); height: 60px; display: flex; align-items: center; justify-content: space-between; padding: 0 15px; position: sticky; top: 0; z-index: 1000; }
        .header-title { font-weight: 800; font-size: 1.1em; letter-spacing: 0.5px; text-transform: uppercase; }
        .scene-badge { background: var(--primary); color: var(--btn-text); padding: 4px 10px; border-radius: 20px; font-size: 0.75em; font-weight: bold; margin-right: 10px; }

        /* TABS */
        .tabs { display: flex; background: var(--surface); padding: 4px; margin: 15px; border-radius: 8px; border: 1px solid var(--border); }
        .tab { flex: 1; text-align: center; padding: 10px; border-radius: 6px; font-size: 0.9em; font-weight: 600; cursor: pointer; color: var(--text-muted); }
        .tab.active { background: var(--bg); color: var(--primary); border: 1px solid var(--border); }

        /* CONTENT */
        .content { padding: 0 15px; max-width: 600px; margin: 0 auto; }
        .tab-content { display: none; } 
        .tab-content.active { display: block; }

        .card { background: var(--surface); border-radius: 12px; padding: 15px; margin-bottom: 15px; border: 1px solid var(--border); box-shadow: 0 2px 4px var(--shadow); }
        .card-label { text-transform: uppercase; font-size: 0.7em; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 8px; display: block; font-weight: bold; }
        .role-title { color: var(--secondary); font-size: 1.1em; font-weight: bold; margin-bottom: 10px; }
        .narrative-text { line-height: 1.5; font-size: 0.95em; color: var(--text); white-space: pre-line; }

        /* BUTTONS */
        .btn { width: 100%; padding: 14px; border-radius: 8px; border: none; font-size: 1em; font-weight: bold; cursor: pointer; display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .btn:active { opacity: 0.8; }
        .btn-action { background: var(--primary); color: var(--btn-text); }
        
        /* Bouton Undo Spécifique (Sécurisé) */
        .btn-undo { 
            background: transparent; border: 1px solid var(--primary); color: var(--primary); 
            font-size: 0.85em; padding: 10px; justify-content: center; width: 60%; margin: 0 auto 25px auto; opacity: 0.7; 
        }
        .btn-undo:hover { opacity: 1; background: rgba(239,68,68,0.1); }

        .btn-secondary { background: var(--surface); color: var(--text); border: 1px solid var(--border); }
        .btn-menu { background: var(--bg); color: var(--text); border: 1px solid var(--border); margin-bottom: 8px; padding: 12px; border-radius: 8px; text-align: left; display: flex; align-items: center; gap: 10px; font-size: 0.95em; }
        
        /* THEME SELECTOR DROPDOWN */
        .theme-select {
            width: 100%; padding: 12px; border-radius: 8px; font-size: 1em; margin-bottom: 20px; cursor: pointer;
            background: var(--bg); color: var(--text); border: 1px solid var(--border); 
            appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23999%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat; background-position: right 15px top 50%; background-size: 12px auto;
        }

        /* DRAWER */
        .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1100; display: none; }
        .drawer { position: fixed; top: 0; right: -100%; width: 85%; max-width: 320px; height: 100%; background: var(--surface); z-index: 1200; transition: right 0.2s ease; display: flex; flex-direction: column; box-shadow: -5px 0 15px var(--shadow); border-left: 1px solid var(--border); }
        .drawer.open { right: 0; }
        .drawer-header { padding: 15px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: var(--bg); }
        .drawer-content { flex: 1; padding: 15px; overflow-y: auto; }
        
        .submenu { display: none; }
        .submenu.active { display: block; }

        /* UI ELEMENTS */
        .timeline { position: relative; padding-left: 15px; margin-top: 10px; border-left: 2px solid var(--border); }
        .timeline-item { margin-bottom: 15px; position: relative; }
        .timeline-item::before { content: ''; position: absolute; left: -21px; top: 0; width: 8px; height: 8px; background: var(--primary); border-radius: 50%; border: 2px solid var(--bg); }
        .t-time { font-size: 0.75em; color: var(--text-muted); }
        .t-action { font-size: 0.9em; color: var(--text); }
        .t-scene { color: var(--secondary); font-weight: bold; font-size: 0.8em; }

        .gender-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid var(--border); }
        .g-switch { display: flex; gap: 5px; }
        .g-btn { border: 1px solid var(--border); background: transparent; color: var(--text-muted); padding: 4px 8px; border-radius: 4px; font-size: 0.8em; cursor: pointer; }
        .g-btn.active-M { background: #3b82f6; color: white; border-color: #3b82f6; }
        .g-btn.active-F { background: #d946ef; color: white; border-color: #d946ef; }
    </style>
</head>
<body class="<?php echo $current_theme; ?>">

<?php if (!$user_id): ?>
    <div class="login-wrap">
        <div style="font-size: 4em; margin-bottom: 20px; color:var(--primary);">
            <i class="fa-solid fa-fingerprint"></i>
        </div>
        <h2 style="margin-bottom: 30px; letter-spacing: 2px; color:var(--text); text-transform: uppercase;">BRAQUAGE</h2>
        <form method="POST">
            <input type="text" name="username" class="login-input" placeholder="Utilisateur" required>
            <input type="password" name="password" class="login-input" placeholder="Mot de passe" required>
            <button type="submit" name="login" class="btn btn-action" style="justify-content: center;">CONNEXION</button>
        </form>
    </div>
<?php else: ?>

    <header>
        <div style="display:flex; align-items:center;">
            <span class="scene-badge"><?php echo $state['scene']; ?></span>
            <div class="header-title"><?php echo mb_strimwidth($current_scene['titre'] ?? 'FIN', 0, 18, "..."); ?></div>
        </div>
        <div style="display:flex; align-items:center; gap: 15px;">
            <span style="font-size:0.8em; color:var(--text-muted);"><?php echo strtoupper($user_id); ?></span>
            <?php if($is_admin): ?>
                <button id="drawer-btn" style="background:none; border:none; color:var(--text); font-size:1.4em; cursor:pointer;"><i class="fa-solid fa-bars"></i></button>
            <?php else: ?>
                <a href="?logout=1" style="color:var(--primary); font-size:1.2em;"><i class="fa-solid fa-power-off"></i></a>
            <?php endif; ?>
        </div>
    </header>

    <?php if($is_admin): ?>
    <div class="tabs">
        <div class="tab active" data-target="tab-scene">SCÈNE</div>
        <div class="tab" data-target="tab-players">JOUEURS</div>
    </div>
    <?php endif; ?>

    <div class="content">
        <div id="tab-scene" class="tab-content active">
            
            <?php if($is_admin): ?>
                <div class="card">
                    <span class="card-label">Contexte</span>
                    <div style="display:flex; flex-direction:column; gap:8px; font-size:0.9em; color:var(--text-muted);">
                        <div><i class="fa-solid fa-location-dot" style="color:var(--primary); width:20px; text-align:center;"></i> <?php echo $current_scene['lieu']; ?></div>
                        <?php if (!empty($current_scene['musique'])): ?>
                            <div><i class="fa-solid fa-music" style="color:var(--secondary); width:20px; text-align:center;"></i> <?php echo $current_scene['musique']; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($current_scene['accessoires'])): ?>
                            <div><i class="fa-solid fa-box-open" style="color:orange; width:20px; text-align:center;"></i> <?php echo $current_scene['accessoires']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card" style="border-left: 4px solid var(--text-muted);">
                    <span class="card-label">Narration</span>
                    <div class="narrative-text" style="font-style:italic;">
                        <?php echo nl2br(htmlspecialchars($current_scene['intro'])); ?>
                    </div>
                </div>

                <div class="card" style="border-left: 4px solid #f59e0b;">
                    <span class="card-label" style="color:#f59e0b;">MJ - INFORMATIONS & MISE EN SCÈNE</span>

                    <?php if (!empty($current_scene['personnages'])): ?>
                        <div style="margin-bottom:15px;">
                            <strong style="color:var(--text); font-size:0.9em;"><i class="fa-solid fa-users" style="color:var(--secondary);"></i> Présents :</strong>
                            <span style="color:var(--text-muted); font-size:0.9em; margin-left:5px;"><?php echo $current_scene['personnages']; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($current_scene['mise_en_scene'])): ?>
                        <div style="margin-bottom:15px;">
                            <strong style="color:var(--text); font-size:0.9em;"><i class="fa-solid fa-clapperboard" style="color:var(--primary);"></i> Mise en scène :</strong>
                            <div class="narrative-text" style="color:var(--text); margin-top:5px; border-left:2px solid var(--border); padding-left:10px;">
                                <?php echo nl2br(htmlspecialchars($current_scene['mise_en_scene'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($current_scene['infos'])): ?>
                        <div>
                            <strong style="color:var(--text); font-size:0.9em;"><i class="fa-solid fa-circle-info" style="color:#10b981;"></i> Infos MJ :</strong>
                            <div class="narrative-text" style="color:var(--text-muted); margin-top:5px; font-style:italic;">
                                <?php echo nl2br(htmlspecialchars($current_scene['infos'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if(!$is_admin && isset($current_scene['joueurs'][$user_role])): ?>
                <div class="card" style="border-left: 4px solid var(--primary);">
                    <div class="role-title" style="color:var(--primary)">🎯 Votre Rôle</div>
                    <div class="narrative-text">
                        <?php 
                            $consignes = $current_scene['joueurs'][$user_role]['consignes'];
                            if (is_array($consignes)) {
                                $g = $state['genres'][$user_role] ?? 'M';
                                $txt = $consignes[$g] ?? $consignes['M'] ?? reset($consignes);
                            } else { $txt = $consignes; }
                            echo nl2br(htmlspecialchars($txt)); 
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($is_admin): ?>
                <div style="margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px;">
                    <span class="card-label" style="margin-bottom:15px; color:var(--primary);">👉 PILOTAGE</span>
                    
                    <?php if(!empty($state['history'])): ?>
                        <form method="POST">
                            <button type="submit" name="undo_last" class="btn btn-undo" onclick="return confirm('⚠️ Revenir à la scène précédente ?');">
                                <i class="fa-solid fa-rotate-left"></i> &nbsp; Annuler dernier choix
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if (!empty($current_scene['choix'])): ?>
                        <form method="POST">
                            <input type="hidden" name="set_flags" id="set_flags">
                            <input type="hidden" name="choice_text" id="choice_text">
                            <?php foreach($current_scene['choix'] as $c): 
                                $visible = empty($c['condition']) || ($state['flags'][$c['condition']] ?? false);
                            ?>
                                <button type="submit" name="target_scene" value="<?php echo $c['cible']; ?>" 
                                        class="btn btn-action" style="<?php echo $visible ? '' : 'opacity:0.5; background:var(--surface); color:var(--text-muted);'; ?>"
                                        onclick="document.getElementById('set_flags').value='<?php echo json_encode($c['set']); ?>'; document.getElementById('choice_text').value='<?php echo addslashes($c['texte']); ?>'">
                                    <span><?php echo $c['texte']; ?> <?php echo $visible ? '' : '(Bloqué)'; ?></span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            <?php endforeach; ?>
                        </form>
                    <?php else: ?>
                        <div class="card">🚫 Fin du scénario ou pas de choix configuré.</div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if($is_admin): ?>
        <div id="tab-players" class="tab-content">
            <?php foreach($current_scene['joueurs'] as $id => $j): ?>
                <div class="card">
                    <span class="card-label" style="color:var(--secondary); font-size:0.9em;"><?php echo strtoupper($id); ?></span>
                    <strong style="display:block; margin-bottom:5px; color:var(--text);"><?php echo $j['role']; ?></strong>
                    <div class="narrative-text" style="font-size:0.85em; color:var(--text-muted);">
                        <?php 
                            $consignes = $j['consignes'];
                            if (is_array($consignes)) {
                                $g = $state['genres'][$id] ?? 'M';
                                $txt = $consignes[$g] ?? $consignes['M'] ?? reset($consignes);
                            } else { $txt = $consignes; }
                            echo nl2br(htmlspecialchars($txt)); 
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php if($is_admin): ?>
    <div class="overlay" id="overlay"></div>
    <div class="drawer" id="drawer">
        <div class="drawer-header">
            <h2 style="margin:0; font-size:1.1em; color:var(--text);">MENU</h2>
            <button id="close-drawer" style="background:none; border:none; color:var(--text); font-size:1.2em; cursor:pointer;">✕</button>
        </div>
        
        <div class="drawer-content">
            <div id="menu-main" class="submenu active">
                <div style="margin-bottom:20px;">
                    <span class="card-label">Configuration</span>
                    <?php foreach(['alex','andrea','camille','charlie'] as $p): $cg = $state['genres'][$p] ?? 'M'; ?>
                        <div class="gender-row">
                            <span><?php echo ucfirst($p); ?></span>
                            <form method="POST" class="g-switch">
                                <input type="hidden" name="p_id" value="<?php echo $p; ?>">
                                <button name="set_genre" value="M" class="g-btn <?php echo $cg=='M'?'active-M':''; ?>">H</button>
                                <button name="set_genre" value="F" class="g-btn <?php echo $cg=='F'?'active-F':''; ?>">F</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-bottom:20px;">
                    <span class="card-label">🎨 Thème d'affichage</span>
                    <form method="GET">
                        <select name="set_theme" onchange="this.form.submit()" class="theme-select">
                            <option value="theme-dark" <?php if($current_theme == 'theme-dark') echo 'selected'; ?>>Sombre 🌑</option>
                            <option value="theme-light" <?php if($current_theme == 'theme-light') echo 'selected'; ?>>Clair ☀️</option>
                            <option value="theme-gold" <?php if($current_theme == 'theme-gold') echo 'selected'; ?>>Or & Noir 🏆</option>
                            <option value="theme-neon" <?php if($current_theme == 'theme-neon') echo 'selected'; ?>>Néon 🟣</option>
                        </select>
                    </form>
                </div>

                <button class="btn btn-menu" data-submenu="menu-jump">
                    <span><i class="fa-solid fa-plane-departure"></i> Saut de Scène</span> <i class="fa-solid fa-chevron-right"></i>
                </button>
                <button class="btn btn-menu" data-submenu="menu-history">
                    <span><i class="fa-solid fa-clock-rotate-left"></i> Historique</span> <i class="fa-solid fa-chevron-right"></i>
                </button>
                <button class="btn btn-menu" data-submenu="menu-pass">
                    <span><i class="fa-solid fa-key"></i> Mots de passe</span> <i class="fa-solid fa-chevron-right"></i>
                </button>
                
                <div style="margin-top:40px; border-top:1px solid var(--border); padding-top:20px;">
                    <form method="POST">
                        <button name="reset_game" class="btn" style="background:transparent; border:1px solid var(--primary); color:var(--primary); justify-content:center;" onclick="return confirm('⚠️ Tout effacer et recommencer ?')">
                            <i class="fa-solid fa-rotate-right"></i> &nbsp; RESET GLOBAL
                        </button>
                    </form>
                    <a href="?logout=1" class="btn btn-secondary" style="justify-content:center; text-decoration:none;">DÉCONNEXION</a>
                </div>
            </div>

            <div id="menu-jump" class="submenu">
                <button class="btn btn-secondary back-main" style="margin-bottom:20px; justify-content:flex-start;">
                    <i class="fa-solid fa-arrow-left"></i> &nbsp; Retour
                </button>
                <h3 style="color:var(--text-muted); text-transform:uppercase;">Navigation Forcée</h3>
                <p style="font-size:0.8em; color:var(--text-muted);">Attention, sauter une scène peut avoir des effets sur l'histoire.</p>
                
                <form method="POST">
                    <select name="scene_id" class="theme-select" style="margin-bottom:15px;">
                        <?php foreach($scenarios as $id => $sc): ?>
                            <option value="<?php echo $id; ?>" <?php if($state['scene'] == $id) echo 'selected'; ?>>
                                <?php echo $id . '. ' . ($sc['titre'] ?? 'Scène '.$id); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button name="force_scene" class="btn btn-action" style="justify-content:center;" onclick="return confirm('Sauter vers cette scène ?')">Y ALLER</button>
                </form>
            </div>

            <div id="menu-history" class="submenu">
                <button class="btn btn-secondary back-main" style="margin-bottom:20px; justify-content:flex-start;">
                    <i class="fa-solid fa-arrow-left"></i> &nbsp; Retour
                </button>
                
                <a href="?mode=export_view" target="_blank" class="btn btn-action" style="margin-bottom:20px; text-decoration:none; display:flex; justify-content:center;">
                    <i class="fa-solid fa-print"></i> &nbsp; VOIR RAPPORT COMPLET
                </a>

                <div style="font-size:0.8em; color:var(--text-muted); text-align:center;">
                    (Les 5 derniers mouvements)
                </div>
                <div style="margin-top:10px; padding-left:10px;">
                    <?php 
                    $history_rev = array_reverse($state['history']);
                    $preview = array_slice($history_rev, 0, 5);
                    foreach($preview as $h): ?>
                        <div style="margin-bottom:10px; border-bottom:1px solid var(--border); padding-bottom:5px;">
                            <strong style="color:var(--primary)"><?php echo $h['time']; ?></strong> Sc. <?php echo $h['to']; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="menu-pass" class="submenu">
                <button class="btn btn-secondary back-main" style="margin-bottom:20px; justify-content:flex-start;">
                    <i class="fa-solid fa-arrow-left"></i> &nbsp; Retour
                </button>
                <?php if(isset($msg_success)) echo "<div style='color:var(--secondary); margin-bottom:10px;'>$msg_success</div>"; ?>
                <form method="POST">
                    <label class="card-label">Joueur</label>
                    <select name="u_target" style="width:100%; padding:10px; border-radius:8px; background:var(--bg); color:var(--text); border:1px solid var(--border); margin-bottom:15px;">
                        <?php foreach($users_db as $u => $d) echo "<option value='$u'>$u</option>"; ?>
                    </select>
                    <label class="card-label">Nouveau mot de passe</label>
                    <input type="text" name="new_pw" style="width:100%; padding:10px; border-radius:8px; background:var(--bg); color:var(--text); border:1px solid var(--border); box-sizing:border-box; margin-bottom:20px;">
                    <button name="change_pw" class="btn btn-action" style="justify-content:center;">ENREGISTRER</button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // GESTION MENU
    const drawerBtn = document.getElementById('drawer-btn');
    const closeDrawerBtn = document.getElementById('close-drawer');
    const overlay = document.getElementById('overlay');
    const drawer = document.getElementById('drawer');

    function toggleMenu() {
        if(drawer.classList.contains('open')) {
            drawer.classList.remove('open');
            setTimeout(() => { if(overlay) overlay.style.display = 'none'; showSubMenu('menu-main'); }, 200);
        } else {
            if(overlay) overlay.style.display = 'block';
            setTimeout(() => drawer.classList.add('open'), 10);
        }
    }

    if(drawerBtn) drawerBtn.addEventListener('click', toggleMenu);
    if(closeDrawerBtn) closeDrawerBtn.addEventListener('click', toggleMenu);
    if(overlay) overlay.addEventListener('click', toggleMenu);

    // GESTION TABS
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const targetId = this.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // GESTION SOUS-MENUS
    function showSubMenu(id) {
        document.querySelectorAll('.submenu').forEach(s => s.classList.remove('active'));
        const target = document.getElementById(id);
        if(target) target.classList.add('active');
    }

    document.querySelectorAll('.btn-menu').forEach(btn => {
        btn.addEventListener('click', function() {
            showSubMenu(this.getAttribute('data-submenu'));
        });
    });

    document.querySelectorAll('.back-main').forEach(btn => {
        btn.addEventListener('click', function() {
            showSubMenu('menu-main');
        });
    });
});
</script>

</body>
</html>