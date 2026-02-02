<?php
session_start();

// ============================================================================
// 1. CONFIGURATION ET DONNÉES
// ============================================================================
$state_file = 'gamestate.json';
$scenario_file = 'scenario.json';
$users_file = 'users.json'; 

// --- A. Initialisation des UTILISATEURS ---
if (!file_exists($users_file)) {
    $default_users = [
        'nath'    => ['pass' => 'chef',   'role' => 'orga'],
        'alex'    => ['pass' => 'joueur', 'role' => 'alex'],
        'andrea'  => ['pass' => 'joueur', 'role' => 'andrea'],
        'camille' => ['pass' => 'joueur', 'role' => 'camille'],
        'charlie' => ['pass' => 'joueur', 'role' => 'charlie'],
    ];
    file_put_contents($users_file, json_encode($default_users, JSON_PRETTY_PRINT));
}
$users_db = json_decode(file_get_contents($users_file), true);

// --- B. Initialisation du JEU ---
if (!file_exists($state_file)) {
    file_put_contents($state_file, json_encode(["scene" => "1", "history" => [], "flags" => []]));
}
$state = json_decode(file_get_contents($state_file), true);

if (file_exists($scenario_file)) {
    $scenarios = json_decode(file_get_contents($scenario_file), true);
} else {
    die("Le fichier scenario.json est manquant !");
}

// --- C. API AJAX (Sync) ---
if (isset($_GET['check_sync'])) {
    header('Content-Type: application/json');
    echo json_encode(['current_scene' => $state['scene']]);
    exit;
}

// Config Visuelle des Rôles
$config = [
    'orga'    => ['name' => 'ORGA (NATH)', 'bg' => '#2c3e50', 'color' => '#fff'],
    'alex'    => ['name' => 'ALEX',       'bg' => '#f1c40f', 'color' => '#000'],
    'andrea'  => ['name' => 'ANDRÉA',     'bg' => '#2ecc71', 'color' => '#000'],
    'camille' => ['name' => 'CAMILLE',    'bg' => '#3498db', 'color' => '#fff'],
    'charlie' => ['name' => 'CHARLIE',    'bg' => '#9b59b6', 'color' => '#fff'],
];

// ============================================================================
// 2. ACTIONS ET LOGIQUE
// ============================================================================

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Login
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_user'])) {
    $u = strtolower(trim($_POST['login_user']));
    $p = $_POST['login_pass'];

    if (isset($users_db[$u]) && $users_db[$u]['pass'] === $p) {
        $_SESSION['user_role'] = $users_db[$u]['role'];
        header("Location: index.php");
        exit;
    } else {
        $login_error = "Identifiant ou mot de passe incorrect.";
    }
}

// VÉRIFICATION CONNEXION
if (!isset($_SESSION['user_role'])) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Braquage - Connexion</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body { background: #111; color: #eee; font-family: sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .login-box { background: #222; padding: 40px; border-radius: 8px; box-shadow: 0 0 20px rgba(0,0,0,0.8); text-align: center; width: 300px; border-top: 5px solid #e74c3c; }
            h1 { margin-bottom: 30px; letter-spacing: 2px; text-transform: uppercase; }
            input { width: 100%; padding: 12px; margin: 10px 0; background: #333; border: 1px solid #444; color: white; border-radius: 4px; box-sizing: border-box; font-size: 1em;}
            button { width: 100%; padding: 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 20px; transition: 0.3s; font-size: 1.1em;}
            button:hover { background: #c0392b; }
            .error { color: #e74c3c; margin-bottom: 15px; font-size: 0.9em; background: rgba(231, 76, 60, 0.1); padding: 10px; border-radius: 4px; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h1>Braquage</h1>
            <?php if($login_error): ?><div class="error"><?php echo $login_error; ?></div><?php endif; ?>
            <form method="POST">
                <input type="text" name="login_user" placeholder="Identifiant" required autofocus>
                <input type="password" name="login_pass" placeholder="Mot de passe" required>
                <button type="submit">ENTRER</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// --- LOGIQUE UTILISATEUR CONNECTÉ ---

$user_role = $_SESSION['user_role'];
$is_admin = ($user_role === 'orga');
$view = $user_role;

// Si Admin, peut changer de vue
if ($is_admin && isset($_GET['view']) && array_key_exists($_GET['view'], $config)) {
    $view = $_GET['view'];
}
$is_orga_view = ($view === 'orga');


// --- 3. GESTION DE L'EXPORT PDF (RÉTABLIE) ---
if (isset($_GET['export']) && $is_admin) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Rapport de Session - Braquage</title>
        <style>
            body { font-family: 'Georgia', serif; background: #fff; color: #000; padding: 40px; max-width: 800px; margin: 0 auto; }
            .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
            .title { font-size: 2.5em; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
            .meta { color: #555; font-style: italic; margin-top: 10px; }
            .timeline { border-left: 2px solid #ccc; margin-left: 20px; padding-left: 20px; }
            .step { margin-bottom: 25px; position: relative; page-break-inside: avoid; }
            .step::before { content: ''; position: absolute; left: -26px; top: 5px; width: 10px; height: 10px; background: #000; border-radius: 50%; }
            .step-header { display: flex; align-items: baseline; gap: 10px; }
            .step-id { font-weight: bold; color: #888; font-size: 0.8em; text-transform: uppercase; }
            .step-title { font-weight: bold; font-size: 1.2em; }
            .step-choice { background: #f0f0f0; padding: 10px 15px; border-radius: 4px; margin-top: 5px; border-left: 4px solid #555; font-family: sans-serif; font-size: 0.9em; }
            .act-break { margin: 40px 0; border-top: 1px dashed #aaa; text-align: center; font-weight: bold; padding-top: 10px; color: #555; }
            @media print { .no-print { display: none; } body { padding: 0; } }
            .btn-print { position: fixed; top: 20px; right: 20px; padding: 10px 20px; background: #e74c3c; color: white; text-decoration: none; border-radius: 5px; font-family: sans-serif; font-weight: bold; }
        </style>
    </head>
    <body>
        <a href="javascript:window.print()" class="btn-print no-print">🖨️ Imprimer / PDF</a>
        <div class="header">
            <div class="title">BRAQUAGE</div>
            <div class="meta">Rapport de la session du <?php echo date('d/m/Y à H:i'); ?></div>
        </div>
        <div class="timeline">
            <?php 
            $last_act = 0;
            if (!empty($state['history'])) {
                foreach ($state['history'] as $step): 
                    $sData = $scenarios[$step['id']] ?? [];
                    $current_act = 1;
                    if ($step['id'] >= 29 && $step['id'] <= 67) $current_act = 2.1;
                    if ($step['id'] >= 68) $current_act = 2.2;
                    if ($current_act != $last_act && $last_act != 0) { echo "<div class='act-break'>Passage à l'Acte $current_act</div>"; }
                    $last_act = $current_act;
                ?>
                    <div class="step">
                        <div class="step-header">
                            <span class="step-id">Scène <?php echo $step['id']; ?></span>
                            <span class="step-title"><?php echo $sData['titre'] ?? 'Scène Inconnue'; ?></span>
                        </div>
                        <div class="step-choice"><strong>CHOIX :</strong> <?php echo $step['action']; ?></div>
                    </div>
                <?php endforeach; 
            } else {
                echo "<p>Aucun historique pour le moment.</p>";
            }
            ?>
            <div class="step">
                <div class="step-header"><span class="step-id">FIN</span><span class="step-title">Situation Finale : Scène <?php echo $state['scene']; ?></span></div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}


// --- TRAITEMENT DES ACTIONS (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    
    // 1. Changement de Scène (Classique)
    if (isset($_POST['target_scene'])) {
        $action_text = $_POST['choice_text'] ?? 'Navigation';
        $prev_id = $state['scene'];
        $prev_title = $scenarios[$prev_id]['titre'] ?? 'Scène '.$prev_id;
        $state['history'][] = ['id' => $prev_id, 'titre' => $prev_title, 'action' => $action_text];
        
        if (isset($_POST['set_flags'])) {
            $new_flags = json_decode(htmlspecialchars_decode($_POST['set_flags']), true);
            if (is_array($new_flags)) { $state['flags'] = array_merge($state['flags'], $new_flags); }
        }
        $state['scene'] = $_POST['target_scene'];
        file_put_contents($state_file, json_encode($state));
        header("Location: index.php?view=$view");
        exit;
    }
    // 2. Retour arrière
    elseif (isset($_POST['action']) && $_POST['action'] === 'back') {
        if (!empty($state['history'])) {
            $last = array_pop($state['history']);
            $state['scene'] = $last['id'];
            file_put_contents($state_file, json_encode($state));
        }
        header("Location: index.php?view=$view");
        exit;
    }
    // 3. Saut rapide
    elseif (isset($_POST['jump_scene']) && !empty($_POST['jump_scene'])) {
        $prev_id = $state['scene'];
        $prev_title = $scenarios[$prev_id]['titre'] ?? 'Scène '.$prev_id;
        $state['history'][] = ['id' => $prev_id, 'titre' => $prev_title, 'action' => '🚀 Saut rapide'];
        $state['scene'] = $_POST['jump_scene'];
        file_put_contents($state_file, json_encode($state));
        header("Location: index.php?view=$view");
        exit;
    }
    // 4. Reset
    elseif (isset($_POST['action']) && $_POST['action'] === 'reset') {
        $state = ["scene" => "1", "history" => [], "flags" => []];
        file_put_contents($state_file, json_encode($state));
        header("Location: index.php?view=$view");
        exit;
    }
    // 5. MISE A JOUR DES MOTS DE PASSE (Nouveau)
    elseif (isset($_POST['action']) && $_POST['action'] === 'update_users') {
        foreach ($_POST['pass'] as $u => $new_pass) {
            if (isset($users_db[$u]) && !empty($new_pass)) {
                $users_db[$u]['pass'] = trim($new_pass);
            }
        }
        file_put_contents($users_file, json_encode($users_db, JSON_PRETTY_PRINT));
        header("Location: index.php?view=orga");
        exit;
    }
}

// --- PRÉPARATION AFFICHAGE ---
$current_id = $state['scene'];
$scene = $scenarios[$current_id] ?? null;

if (!$scene) die("Erreur critique : Scène introuvable.");

$role_txt = $scene['joueurs'][$view]['role'] ?? $config[$view]['name'];
$cons_txt = $scene['joueurs'][$view]['consignes'] ?? "Pas d'instructions spécifiques pour cette scène.";

$act_lbl = "ACTE INDÉFINI"; $act_col = "#777";
if ($current_id <= 28) { $act_lbl = "ACTE 1"; $act_col = "#3498db"; } 
elseif ($current_id >= 29 && $current_id <= 67) { $act_lbl = "ACTE 2.1"; $act_col = "#e67e22"; } 
elseif ($current_id >= 68) { $act_lbl = "ACTE 2.2"; $act_col = "#9b59b6"; }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Braquage - <?php echo $config[$view]['name']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #1a1a1a; color: #eee; font-family: sans-serif; margin: 0; padding-top: <?php echo $is_admin ? '50px' : '0'; ?>; padding-bottom: 50px; }
        
        .tabs { position: fixed; top: 0; left: 0; width: 100%; height: 50px; background: #000; display: flex; z-index: 1000; border-bottom: 2px solid #444; }
        .tab { flex: 1; text-align: center; line-height: 50px; color: #777; text-decoration: none; font-weight: bold; font-size: 0.9em; transition:0.2s; border-right: 1px solid #333; }
        .tab:hover { background: #222; color: #fff; }
        .tab.active { background: <?php echo $config[$view]['bg']; ?>; color: <?php echo $config[$view]['color']; ?>; border-bottom: 4px solid #fff; }

        .header-bar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #000; border-bottom: 3px solid <?php echo $config[$view]['bg']; ?>; }
        .user-info { font-weight: bold; color: <?php echo $config[$view]['bg']; ?>; font-size: 1.2em; text-transform: uppercase; }
        
        .header-right { display: flex; gap: 20px; align-items: center; }
        .logout-btn { color: #aaa; text-decoration: none; font-size: 0.8em; border: 1px solid #444; padding: 5px 10px; border-radius: 4px; transition:0.3s; }
        .logout-btn:hover { background: #c0392b; color: white; border-color:#c0392b; }
        
        .btn-mini { padding:10px 15px; cursor:pointer; border:none; border-radius:4px; font-weight:bold; }
        .btn-export { background: #3498db; color: white; text-decoration: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; font-size: 0.9em; display: inline-block; }

        .wrapper { display: flex; max-width: 1600px; margin: 20px auto; gap: 20px; padding: 0 10px; align-items: flex-start; flex-wrap: wrap; }
        .main-col { flex: 3; background: #2b2b2b; padding: 30px; border-radius: 8px; border-top: 5px solid <?php echo $config[$view]['bg']; ?>; box-shadow: 0 4px 15px rgba(0,0,0,0.5); min-width: 300px; }
        .side-col { flex: 1; background: #222; padding: 20px; border-radius: 8px; border-left: 1px solid #444; min-width: 250px; }

        .badge { display: inline-block; padding: 4px 10px; border-radius: 4px; background: <?php echo $act_col; ?>; font-weight: bold; font-size: 0.8em; margin-bottom: 10px; }
        h1 { margin: 5px 0 20px 0; font-size: 2em; line-height: 1.2; }
        
        .box { background: #333; padding: 15px; border-radius: 5px; margin-bottom: 15px; border-left: 5px solid #555; }
        .box-title { display: block; color: #aaa; font-size: 0.75em; text-transform: uppercase; font-weight: bold; margin-bottom: 8px; letter-spacing: 1px; }
        
        .role-display { text-align: center; padding: 20px; background: <?php echo $config[$view]['bg']; ?>; color: <?php echo $config[$view]['color']; ?>; font-size: 1.5em; font-weight: bold; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        
        /* Contrôles Orga */
        .controls-bar { background:#222; padding:10px; margin-bottom:20px; border-radius:5px; display:flex; gap:10px; align-items: center; flex-wrap: wrap; }
        
        .btn-choice { display: block; width: 100%; padding: 15px; margin: 10px 0; border: none; text-align: left; cursor: pointer; border-radius: 4px; font-weight: bold; font-size: 1.1em; transition:0.2s; color: white; background: #c0392b; }
        .btn-choice:hover { background: #e74c3c; transform: translateX(5px); }

        .hist-item { font-size: 0.85em; border-bottom: 1px solid #333; padding: 8px 0; color: #aaa; }
        .hist-act { color: #3498db; font-style: italic; margin-top:3px; }

        /* Menu Admin Dropdown */
        .admin-details { position: relative; }
        .admin-details summary { cursor: pointer; color: #bbb; font-weight: bold; list-style: none; font-size: 0.9em; border: 1px solid #444; padding: 5px 10px; border-radius: 4px; }
        .admin-details summary:hover { background: #333; color: white; }
        .admin-details summary::-webkit-details-marker { display: none; }
        .admin-popup { 
            position: absolute; top: 40px; right: 0; 
            background: #222; border: 1px solid #555; 
            padding: 15px; width: 320px; z-index: 2000; 
            border-radius: 5px; box-shadow: 0 10px 30px rgba(0,0,0,0.9); 
        }

        .user-table { width:100%; border-collapse: collapse; font-size:0.9em; }
        .user-table th { text-align:left; color:#888; border-bottom:1px solid #444; padding:5px; }
        .user-table td { padding: 8px 5px; border-bottom:1px solid #333; vertical-align: middle;}
        .user-table input { background:#111; border:1px solid #444; color:#fff; padding:5px; border-radius:3px; width:120px; }
    </style>
    
    <script>
        const mySceneId = "<?php echo $current_id; ?>";
        setInterval(() => {
            fetch('?check_sync=1').then(r => r.json()).then(d => {
                if (d.current_scene !== mySceneId) window.location.reload();
            });
        }, 2000);
    </script>
</head>
<body>

<?php if($is_admin): ?>
<div class="tabs">
    <?php foreach($config as $k=>$v): ?>
        <a href="?view=<?php echo $k; ?>" class="tab <?php echo ($view==$k)?'active':''; ?>"><?php echo $v['name']; ?></a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="header-bar">
    <div class="user-info">
        <?php if($is_admin && !$is_orga_view): ?>👁️ VUE : <?php echo $config[$view]['name']; ?><?php else: ?>👤 <?php echo $config[$user_role]['name']; ?><?php endif; ?>
    </div>
    
    <div class="header-right">
        <?php if($is_admin && $is_orga_view): ?>
            <details class="admin-details">
                <summary>⚙️ Gestion Joueurs</summary>
                <div class="admin-popup">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_users">
                        <table class="user-table">
                            <thead><tr><th>Joueur</th><th>Mot de passe</th></tr></thead>
                            <tbody>
                                <?php foreach($users_db as $u => $d): 
                                    $default_pass = ($u === 'nath') ? 'chef' : 'joueur';
                                    $is_default = ($d['pass'] === $default_pass); 
                                ?>
                                <tr>
                                    <td><?php echo ucfirst($u); ?></td>
                                    <td>
                                        <input type="text" name="pass[<?php echo $u; ?>]" value="<?php echo $d['pass']; ?>">
                                        <?php if($is_default): ?>
                                            <div style="color:#f39c12; font-size:0.8em; margin-top:2px;">(Par défaut)</div>
                                        <?php else: ?>
                                            <div style="color:#2ecc71; font-size:0.8em; margin-top:2px;">(Modifié)</div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn-mini" style="background:#3498db; color:white; width:100%; margin-top:10px;">💾 Enregistrer</button>
                    </form>
                    <div style="margin-top:15px; font-size:0.8em; color:#888; text-align:center; font-style:italic;">
                        ⚠️ En cas d'oubli du mot de passe admin, supprimez le fichier <strong>users.json</strong> sur le serveur pour réinitialiser.
                    </div>
                </div>
            </details>
        <?php endif; ?>

        <a href="?action=logout" class="logout-btn">Se Déconnecter</a>
    </div>
</div>

<div class="wrapper">
    <div class="main-col">
        <span class="badge"><?php echo $act_lbl; ?></span>
        <h1>Scène <?php echo $current_id; ?> : <?php echo $scene['titre']; ?></h1>
        
        <?php if (!$is_orga_view): ?>
            <div class="role-display"><?php echo $role_txt; ?></div>
            <div class="box" style="border-left-color: #f1c40f;">
                <span class="box-title">TES INSTRUCTIONS</span>
                <div style="font-size: 1.1em; line-height: 1.6;"><?php echo nl2br($cons_txt); ?></div>
            </div>
            <div class="box">
                <span class="box-title">CONTEXTE GÉNÉRAL</span>
                <em style="color:#ccc;"><?php echo nl2br($scene['intro']); ?></em>
            </div>
        <?php else: ?>
            <div class="controls-bar">
                <form method="POST" style="flex:1;">
                    <select name="jump_scene" onchange="this.form.submit()" style="width:100%; padding:10px; border-radius:4px; background:#fff; color:#000;">
                        <option value="">-- Saut Rapide vers... --</option>
                        <?php foreach($scenarios as $id=>$s) echo "<option value='$id'>$id. {$s['titre']}</option>"; ?>
                    </select>
                </form>
                <form method="POST"><input type="hidden" name="action" value="back"><button class="btn-mini" style="background:#555; color:#fff;" title="Retour Arrière">⬅ Précédent</button></form>
                
                <a href="?view=orga&export=1" target="_blank" class="btn-export" title="Générer PDF">📄 PDF</a>

                <form method="POST"><input type="hidden" name="action" value="reset"><button class="btn-mini" style="background:#444; color:#e74c3c;" onclick="return confirm('Tout effacer ?');">⚠️ Reset</button></form>
            </div>

            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <div class="box" style="flex: 1; border-left-color:#e74c3c; background:#4a2323; min-width: 200px;">
                    <span class="box-title">🎒 Accessoires & Matériel</span>
                    <strong style="color:#ffcccc;"><?php echo !empty($scene['accessoires']) ? nl2br($scene['accessoires']) : '-'; ?></strong>
                </div>
                <div class="box" style="flex: 1; border-left-color:#2ecc71; background:#1e3525; min-width: 200px;">
                    <span class="box-title">📍 Lieu / Décor</span>
                    <strong style="color:#abebc6;"><?php echo !empty($scene['lieu']) ? nl2br($scene['lieu']) : '-'; ?></strong>
                </div>
            </div>

            <?php if(!empty($scene['musique'])): ?>
                <div class="box" style="border-left-color:#9b59b6;"><span class="box-title">🎵 Musique</span><strong style="color:#e0b0ff; font-size:1.2em;"><?php echo $scene['musique']; ?></strong></div>
            <?php endif; ?>

            <div class="box"><span class="box-title">👥 Personnages Présents</span><?php echo $scene['personnages'] ?? '-'; ?></div>
            <div class="box"><span class="box-title">📖 Introduction</span><?php echo nl2br($scene['intro']); ?></div>
            <?php if(!empty($scene['mise_en_scene'])): ?><div class="box"><span class="box-title">🎬 Mise en Scène</span><?php echo nl2br($scene['mise_en_scene']); ?></div><?php endif; ?>
            <?php if(!empty($scene['infos'])): ?><div class="box" style="border-left-color:#e67e22; background:#3e332a;"><span class="box-title">ℹ️ Informations Orga</span><div style="color:#f39c12; font-weight:500; font-size:1.1em;"><?php echo nl2br($scene['infos']); ?></div></div><?php endif; ?>

            <h3 style="margin-top:30px; border-bottom:1px solid #444; padding-bottom:5px;">Aperçu des Rôles</h3>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:10px; margin-bottom:20px;">
                <?php foreach($config as $k=>$v): if($k=='orga') continue; 
                    $p = $scene['joueurs'][$k] ?? []; $r = $p['role'] ?? '-';
                    $style_box = (strpos($r, $v['name']) === false && $r !== '-') ? 'border:1px solid #e74c3c; background:rgba(231, 76, 60, 0.2);' : 'background:#333; border:1px solid #444;';
                ?>
                    <div style="padding:10px; font-size:0.9em; border-radius:4px; <?php echo $style_box; ?>">
                        <strong style="color:<?php echo $v['bg']; ?>; text-transform:uppercase;"><?php echo $v['name']; ?></strong><br>
                        <span style="color:#fff; font-weight:bold;"><?php echo $r; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:30px; background:#222; padding:20px; border-radius:8px;">
                <h3 style="margin-top:0;">⚡ Actions & Choix</h3>
                <form method="POST">
                    <input type="hidden" name="choice_text" value=""><input type="hidden" name="set_flags" value="">
                    <?php foreach($scene['choix'] as $choix): 
                        $condition_ok = true;
                        if(isset($choix['condition'])) {
                            if ($choix['condition'] === 'aucune_prison') {
                                if (!empty($state['flags']['camille_prison']) || !empty($state['flags']['charlie_prison'])) $condition_ok = false;
                            } elseif (empty($state['flags'][$choix['condition']])) { $condition_ok = false; }
                        }
                        $style_btn = "background: #c0392b;"; $text_add = "";
                        if (!$condition_ok) { $style_btn = "background: transparent; border: 2px dashed #777; color: #aaa;"; $text_add = " <span style='font-size:0.8em;'>(Choix masqué aux joueurs)</span>"; }
                    ?>
                        <input type="hidden" id="f_<?php echo $choix['cible']; ?>" value='<?php echo json_encode($choix['set']); ?>'>
                        <button type="submit" name="target_scene" value="<?php echo $choix['cible']; ?>" class="btn-choice" style="<?php echo $style_btn; ?>"
                            onclick="this.form.set_flags.value=document.getElementById('f_<?php echo $choix['cible']; ?>').value; this.form.choice_text.value='<?php echo addslashes($choix['texte']); ?>'">
                            ➜ <?php echo $choix['texte'] . $text_add; ?>
                        </button>
                    <?php endforeach; ?>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($is_admin): ?>
    <div class="side-col">
        <h3 style="margin-top:0; border-bottom:1px solid #555; padding-bottom:10px; font-size:1em;">📜 Historique</h3>
        <?php if(empty($state['history'])) echo "<em style='color:#666; font-size:0.8em;'>Début de partie</em>"; ?>
        <?php foreach(array_reverse($state['history']) as $h): ?>
            <div class="hist-item"><strong><?php echo $h['titre']; ?></strong><div class="hist-act">⬇️ <?php echo $h['action']; ?></div></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

</body>
</html>