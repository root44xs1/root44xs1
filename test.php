<?php
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    
    // Set headers to force download the generated text file
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="BotProfile.db"');
    
    // Start writing the file content
    echo "// Custom Counter-Strike 1.6 Bot Profile\n";
    echo "// Generated via PHP Web Interface\n\n";
    
    // 1. Write standard weapon definitions
    echo "WeaponPreference\n";
    echo "  Default\n";
    echo "    m4a1, ak47, mp5, deagle\n";
    echo "  End\n";
    echo "End\n\n";
    
    // 2. Process and write the Custom Templates from the form
    foreach ($_POST['template'] as $tName => $tConfig) {
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $tName);
        $weapons = htmlspecialchars($tConfig['weapons']);
        $skill = intval($tConfig['skill']);
        $aggr = intval($tConfig['aggression']);
        $react = floatval($tConfig['reaction']);
        
        echo "Template {$cleanName}\n";
        echo "  WeaponPreference = {$weapons}\n";
        echo "  Skill = {$skill}\n";
        echo "  Aggression = {$aggr}\n";
        echo "  ReactionTime = {$react}\n";
        echo "  VoicePitch = 100\n";
        echo "End\n\n";
    }
    
    // 3. Process and write individual bots from the text area
    $botLines = explode("\n", $_POST['bot_list']);
    foreach ($botLines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // Expected format: TemplateName, BotName
        $parts = explode(',', $line);
        if (count($parts) === 2) {
            $template = preg_replace('/[^a-zA-Z0-9]/', '', trim($parts[0]));
            $name = preg_replace('/[^a-zA-Z0-9_]/', '', trim($parts[1]));
            echo "{$template} {$name}\n";
        }
    }
    exit; // Stop executing script so HTML template isn't appended to download
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CS 1.6 Bot Profile Maker</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; background: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
<div class="card">
    <h2>CS 1.6 Bot Profile Maker</h2>
    <form method="POST" action="">
        
        <h3>1. Configure Elite Template</h3>
        <div class="group">
            <label>Weapons (comma separated):</label>
            <input type="text" name="template[EliteSniper][weapons]" value="awp, deagle, scout">
        </div>
        <div class="group">
            <label>Skill Level (0 - 100):</label>
            <input type="number" name="template[EliteSniper][skill]" value="100">
        </div>
        <div class="group">
            <label>Aggression (0 - 100):</label>
            <input type="number" name="template[EliteSniper][aggression]" value="90">
        </div>
        <div class="group">
            <label>Reaction Time (in seconds):</label>
            <input type="text" name="template[EliteSniper][reaction]" value="0.15">
        </div>

        <h3>2. Configure Rusher Template</h3>
        <div class="group">
            <label>Weapons (comma separated):</label>
            <input type="text" name="template[Rusher][weapons]" value="ak47, m4a1, mac10">
        </div>
        <div class="group">
            <label>Skill Level (0 - 100):</label>
            <input type="number" name="template[Rusher][skill]" value="80">
        </div>
        <div class="group">
            <label>Aggression (0 - 100):</label>
            <input type="number" name="template[Rusher][aggression]" value="95">
        </div>
        <div class="group">
            <label>Reaction Time (in seconds):</label>
            <input type="text" name="template[Rusher][reaction]" value="0.25">
        </div>

        <h3>3. Bot Names List</h3>
        <div class="group">
            <label>Format: TemplateName, BotName (One per line)</label>
            <textarea name="bot_list" rows="6">EliteSniper, Alpha
Rusher, Bravo
EliteSniper, Charlie
Rusher, Delta</textarea>
        </div>

        <button type="submit" name="generate">Generate & Download BotProfile.db</button>
    </form>
</div>
</body>
</html>
