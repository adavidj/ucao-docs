<?php require_once \ ../php/config.php\; \->query(\ALTER TABLE documents ADD COLUMN semestre VARCHAR 20 AFTER annee\); echo \Migration complete\; unlink(__FILE__); ?>
