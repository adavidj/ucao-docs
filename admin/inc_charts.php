<?php
// Shared analytics logic for Admin
$type_stats = fetchAll("SELECT type, COUNT(*) as count FROM documents GROUP BY type");
$types = []; $type_counts = [];
foreach($type_stats as $ts) { $types[] = ucfirst($ts['type']); $type_counts[] = $ts['count']; }

$school_stats = fetchAll("SELECT e.nom, COUNT(d.id) as count FROM ecoles e LEFT JOIN filieres f ON f.ecole_id = e.id LEFT JOIN documents d ON d.filiere_id = f.id GROUP BY e.id");
$schools = []; $school_counts = [];
foreach($school_stats as $ss) { $schools[] = $ss['nom']; $school_counts[] = $ss['count']; }

$year_stats = fetchAll("SELECT annee, COUNT(*) as count FROM documents WHERE annee > ".(date('Y')-5)." GROUP BY annee ORDER BY annee ASC");
$years_lbl = []; $years_counts = [];
foreach($year_stats as $ys) { $years_lbl[] = $ys['annee']; $years_counts[] = $ys['count']; }
?>

<!-- Compact Analytics Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="data-card" style="padding: 0.8rem;">
        <h4 style="margin-bottom: 0.8rem; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-chart-pie" style="color: var(--bleu-nuit);"></i> Types d'Archives
        </h4>
        <div style="height: 220px; display: flex; justify-content: center;">
            <canvas id="typeChartShared"></canvas>
        </div>
    </div>
    <div class="data-card" style="padding: 0.8rem;">
        <h4 style="margin-bottom: 0.8rem; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-chart-bar" style="color: var(--rouge-nuit);"></i> Volumes / Faculté
        </h4>
        <div style="height: 220px;">
            <canvas id="schoolChartShared"></canvas>
        </div>
    </div>
    <div class="data-card" style="padding: 0.8rem;">
        <h4 style="margin-bottom: 0.8rem; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-chart-line" style="color: #4caf50;"></i> Évolution Annuelle
        </h4>
        <div style="height: 220px;">
            <canvas id="yearChartShared"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeLabels = <?= json_encode($types) ?>;
        const typeData = <?= json_encode($type_counts) ?>;
        const schoolLabels = <?= json_encode($schools) ?>;
        const schoolData = <?= json_encode($school_counts) ?>;
        const yearLabels = <?= json_encode($years_lbl) ?>;
        const yearData = <?= json_encode($years_counts) ?>;

        const options = { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9 } } } }
        };

        new Chart(document.getElementById('typeChartShared'), {
            type: 'doughnut',
            data: { labels: typeLabels, datasets: [{ data: typeData, backgroundColor: ['#1a237e', '#b71c1c', '#4caf50', '#ffc107', '#9c27b0'] }] },
            options: options
        });

        new Chart(document.getElementById('schoolChartShared'), {
            type: 'bar',
            data: { labels: schoolLabels, datasets: [{ data: schoolData, backgroundColor: '#1a237e', borderRadius: 4 }] },
            options: { ...options, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { font: { size: 9 } } }, x: { ticks: { font: { size: 9 } } } } }
        });

        new Chart(document.getElementById('yearChartShared'), {
            type: 'line',
            data: { labels: yearLabels, datasets: [{ data: yearData, borderColor: '#4caf50', backgroundColor: 'rgba(76,175,80,0.1)', fill: true, tension: 0.3 }] },
            options: { ...options, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { font: { size: 9 } } }, x: { ticks: { font: { size: 9 } } } } }
        });
    });
</script>
