<?php
$raw = file_get_contents("Quiz.txt");
$raw = str_replace("\r", "", $raw);
$blocks = preg_split("/\\n\\s*\\n/", trim($raw));
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Quiz</title></head>
<body>
<h1>Quiz</h1>
<ol>
<?php foreach ($blocks as $b): 
  $lines = array_filter(array_map('trim', explode("\n", $b)));
  if (!$lines) continue;
  $q = array_shift($lines);
?>
  <li>
    <p><strong><?= htmlspecialchars($q) ?></strong></p>
    <?php foreach ($lines as $c): ?>
      <label><input type="radio"> <?= htmlspecialchars(rtrim($c, '*')) ?></label><br>
    <?php endforeach; ?>
  </li>
<?php endforeach; ?>
</ol>
</body>
</html>
