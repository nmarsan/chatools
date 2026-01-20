<?php
// --- 1. CONFIGURATION ET CHARGEMENT ---
$state_file = 'gamestate.json';

// Initialisation si fichier absent
if (!file_exists($state_file)) {
    file_put_contents($state_file, json_encode(["scene" => "1", "history" => [], "flags" => []]));
}

// Lecture des données
$state = json_decode(file_get_contents($state_file), true);
$json_data = file_get_contents('scenario.json');
$scenarios = json_decode($json_data, true);

// Détection de la vue (Orga ou Joueur)
$view = $_GET['view'] ?? 'orga';
$is_orga = ($view === 'orga');

// --- 2. FONCTION D'EXPORT PDF ---
if (isset($_GET['export']) && $is_orga) {
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
        <a href="javascript:window.print()" class="btn-print no-print">🖨️ Enregistrer en PDF</a>
        <div class="header">
            <div class="title">BRAQUAGE</div>
            <div class="meta">Rapport de la session du <?php echo date('d/m/Y à H:i'); ?></div>
        </div>
        <div class="timeline">
            <?php 
            $last_act = 0;
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
            <?php endforeach; ?>
            <div class="step">
                <div class="step-header"><span class="step-id">FIN</span><span class="step-title">Situation Finale : Scène <?php echo $state['scene']; ?></span></div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// --- 3. LOGIQUE DE JEU (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_orga) {
    
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
    }
    elseif (isset($_POST['action']) && $_POST['action'] === 'back') {
        if (!empty($state['history'])) {
            $last = array_pop($state['history']);
            $state['scene'] = $last['id'];
        }
    }
    elseif (isset($_POST['jump_scene']) && !empty($_POST['jump_scene'])) {
        $prev_id = $state['scene'];
        $prev_title = $scenarios[$prev_id]['titre'] ?? 'Scène '.$prev_id;
        $state['history'][] = ['id' => $prev_id, 'titre' => $prev_title, 'action' => '🚀 Saut rapide'];
        $state['scene'] = $_POST['jump_scene'];
    }
    elseif (isset($_POST['action']) && $_POST['action'] === 'reset') {
        $state = ["scene" => "1", "history" => [], "flags" => []];
    }

    file_put_contents($state_file, json_encode($state));
    header("Location: index.php?view=orga");
    exit;
}

// --- 4. PRÉPARATION AFFICHAGE ---
$current_id = $state['scene'];
$scene = $scenarios[$current_id] ?? null;
if (!$scene) die("Erreur scène $current_id. <a href='?action=reset'>Reset</a>");

// Config Joueurs
$config = [
    'orga'    => ['name' => 'ORGA (NATH)', 'bg' => '#2c3e50', 'color' => '#fff'],
    'alex'    => ['name' => 'ALEX',       'bg' => '#f1c40f', 'color' => '#000'],
    'andrea'  => ['name' => 'ANDRÉA',     'bg' => '#2ecc71', 'color' => '#000'],
    'camille' => ['name' => 'CAMILLE',    'bg' => '#3498db', 'color' => '#fff'],
    'charlie' => ['name' => 'CHARLIE',    'bg' => '#9b59b6', 'color' => '#fff'],
];
$current_theme = $config[$view];

$p_info = $scene['joueurs'][$view] ?? [];
$role_txt = $p_info['role'] ?? $config[$view]['name'];
$cons_txt = $p_info['consignes'] ?? "Pas d'instructions pour cette scène.";

$act_lbl = "ACTE INDÉFINI"; $act_col = "#777";
if ($current_id <= 28) { $act_lbl = "ACTE 1"; $act_col = "#3498db"; } 
elseif ($current_id >= 29 && $current_id <= 67) { $act_lbl = "ACTE 2.1"; $act_col = "#e67e22"; } 
elseif ($current_id >= 68) { $act_lbl = "ACTE 2.2"; $act_col = "#9b59b6"; }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Braquage - <?php echo $current_theme['name']; ?></title>
    <?php if (!$is_orga): ?><meta http-equiv="refresh" content="3"><?php endif; ?>
    <style>
        body { background: #1a1a1a; color: #eee; font-family: sans-serif; margin: 0; padding-top: 60px; }
        
        .tabs { position: fixed; top: 0; left: 0; width: 100%; height: 50px; background: #000; display: flex; z-index: 1000; border-bottom: 2px solid #444; }
        .tab { flex: 1; text-align: center; line-height: 50px; color: #777; text-decoration: none; font-weight: bold; font-size: 0.9em; transition:0.2s; }
        .tab:hover { background: #222; color: #fff; }
        .tab.active { background: <?php echo $current_theme['bg']; ?>; color: <?php echo $current_theme['color']; ?>; border-bottom: 4px solid #fff; }

        .wrapper { display: flex; max-width: 1600px; margin: 20px auto; gap: 20px; padding: 0 10px; align-items: flex-start; }
        .main-col { flex: 3; background: #2b2b2b; padding: 30px; border-radius: 8px; border-top: 5px solid <?php echo $current_theme['bg']; ?>; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        .side-col { flex: 1; background: #222; padding: 20px; border-radius: 8px; border-left: 1px solid #444; position: sticky; top: 80px; max-height: 85vh; overflow-y: auto; min-width: 280px; }

        .badge { display: inline-block; padding: 4px 10px; border-radius: 4px; background: <?php echo $act_col; ?>; font-weight: bold; font-size: 0.8em; }
        h1 { margin: 10px 0; font-size: 1.8em; }
        
        .box { background: #333; padding: 15px; border-radius: 5px; margin-bottom: 15px; border-left: 4px solid #555; }
        .box-title { display: block; color: #aaa; font-size: 0.75em; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        
        .role-display { text-align: center; padding: 20px; background: <?php echo $current_theme['bg']; ?>; color: <?php echo $current_theme['color']; ?>; font-size: 1.3em; font-weight: bold; border-radius: 8px; margin-bottom: 20px; }
        
        /* Contrôles Orga */
        .controls-bar { background:#222; padding:10px; margin-bottom:20px; border-radius:5px; display:flex; gap:10px; align-items: center; }
        .btn-mini { padding:8px 12px; cursor:pointer; border:none; border-radius:4px; font-weight:bold; }
        .btn-export { background: #3498db; color: white; text-decoration: none; padding: 8px 12px; border-radius: 4px; font-weight: bold; font-size: 0.9em; display: flex; align-items: center; }
        
        .btn-choice { display: block; width: 100%; padding: 15px; margin: 10px 0; background: #c0392b; color: white; border: none; text-align: left; cursor: pointer; border-radius: 4px; font-weight: bold; font-size: 1.1em; transition:0.2s; }
        .btn-choice:hover { background: #e74c3c; padding-left: 20px; }

        .hist-item { font-size: 0.85em; border-bottom: 1px solid #333; padding: 8px 0; color: #aaa; }
        .hist-act { color: #3498db; font-style: italic; margin-top:3px; }
    </style>
</head>
<body>

<div class="tabs">
    <?php foreach($config as $k=>$v): ?>
        <a href="?view=<?php echo $k; ?>" class="tab <?php echo ($view==$k)?'active':''; ?>"><?php echo $v['name']; ?></a>
    <?php endforeach; ?>
</div>

<div class="wrapper">
    <div class="main-col">
        <span class="badge"><?php echo $act_lbl; ?></span>
        <h1>Scène <?php echo $current_id; ?> : <?php echo $scene['titre']; ?></h1>
        
        <?php if (!$is_orga): ?>
            <div class="role-display">
                <?php echo $role_txt; ?>
            </div>
            <div class="box" style="border-left-color: #f1c40f;"><span class="box-title">TES INSTRUCTIONS</span><?php echo nl2br($cons_txt); ?></div>
            <div class="box"><span class="box-title">CONTEXTE</span><em style="color:#ccc;"><?php echo nl2br($scene['intro']); ?></em></div>

        <?php else: ?>
            <div class="controls-bar">
                <form method="POST" style="flex:1;">
                    <select name="jump_scene" onchange="this.form.submit()" style="width:100%; padding:8px; border-radius:4px;">
                        <option value="">-- Aller à... --</option>
                        <?php foreach($scenarios as $id=>$s) echo "<option value='$id'>$id. {$s['titre']}</option>"; ?>
                    </select>
                </form>
                <form method="POST"><input type="hidden" name="action" value="back"><button class="btn-mini" style="background:#555; color:#fff;" title="Retour Arrière">⬅</button></form>
                <a href="?view=orga&export=1" target="_blank" class="btn-export" title="Générer PDF">📄 PDF</a>
                <form method="POST"><input type="hidden" name="action" value="reset"><button class="btn-mini" style="background:#444; color:#e74c3c;" onclick="return confirm('Tout effacer ?');">⚠️ Reset</button></form>
            </div>

            <?php if(!empty($scene['musique'])): ?>
                <div class="box" style="border-left-color:#9b59b6;">
                    <span class="box-title">🎵 Musique</span>
                    <strong style="color:#e0b0ff;"><?php echo $scene['musique']; ?></strong>
                </div>
            <?php endif; ?>

            <div class="box">
                <span class="box-title">👥 Personnages Présents</span>
                <?php echo $scene['personnages'] ?? '-'; ?>
            </div>

            <div class="box">
                <span class="box-title">📖 Introduction</span>
                <?php echo nl2br($scene['intro']); ?>
            </div>

            <?php if(!empty($scene['mise_en_scene'])): ?>
                <div class="box">
                    <span class="box-title">🎬 Mise en Scène</span>
                    <?php echo nl2br($scene['mise_en_scene']); ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($scene['infos'])): ?>
                <div class="box" style="border-left-color:#e67e22; background:#3e332a;">
                    <span class="box-title">ℹ️ Informations Orga</span>
                    <div style="color:#f39c12; font-weight:500;"><?php echo nl2br($scene['infos']); ?></div>
                </div>
            <?php endif; ?>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin:20px 0;">
                <?php foreach($config as $k=>$v): if($k=='orga') continue; 
                    $p = $scene['joueurs'][$k] ?? []; $r = $p['role'] ?? '-';
                    $is_secondary = (strpos($r, $v['name']) === false && $r !== '-');
                    $style_box = $is_secondary ? 'border:1px solid #e74c3c; background:rgba(231, 76, 60, 0.2);' : 'background:#333; border:1px solid #444;';
                    $name_color = $v['bg']; 
                ?>
                    <div style="padding:10px; font-size:0.9em; border-radius:4px; <?php echo $style_box; ?>">
                        <strong style="color:<?php echo $name_color; ?>; text-transform:uppercase;"><?php echo $v['name']; ?></strong>
                        <span style="color:#aaa;"> joue </span>
                        <span style="color:#fff; font-weight:bold;"><?php echo $r; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:30px;">
                <form method="POST">
                    <input type="hidden" name="choice_text" value="">
                    <input type="hidden" name="set_flags" value="">
                    <?php foreach($scene['choix'] as $choix): 
                        $show = true;
                        if(isset($choix['condition'])) {
                            if ($choix['condition'] === 'aucune_prison') {
                                if (!empty($state['flags']['camille_prison']) || !empty($state['flags']['charlie_prison'])) $show = false;
                            } elseif (empty($state['flags'][$choix['condition']])) $show = false;
                        }
                        if($show): ?>
                        <input type="hidden" id="f_<?php echo $choix['cible']; ?>" value='<?php echo json_encode($choix['set']); ?>'>
                        <button type="submit" name="target_scene" value="<?php echo $choix['cible']; ?>" class="btn-choice"
                            onclick="this.form.set_flags.value=document.getElementById('f_<?php echo $choix['cible']; ?>').value; this.form.choice_text.value='<?php echo addslashes($choix['texte']); ?>'">
                            ➜ <?php echo $choix['texte']; ?>
                        </button>
                    <?php endif; endforeach; ?>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($is_orga): ?>
    <div class="side-col">
        <h3 style="margin-top:0; border-bottom:1px solid #555; padding-bottom:10px; font-size:1em;">📜 Historique</h3>
        <?php if(empty($state['history'])) echo "<em style='color:#666; font-size:0.8em;'>Début de partie</em>"; ?>
        <?php foreach($state['history'] as $h): ?>
            <div class="hist-item">
                <strong><?php echo $h['titre']; ?></strong>
                <div class="hist-act">⬇️ <?php echo $h['action']; ?></div>
            </div>
        <?php endforeach; ?>
        <div style="margin-top:15px; padding:10px; background:#e74c3c; color:white; text-align:center; font-weight:bold; border-radius:4px;">
            ACTUEL : Scène <?php echo $current_id; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>