document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const schoolFilter = document.getElementById('schoolFilter');
    const programFilter = document.getElementById('programFilter');
    const levelFilter = document.getElementById('levelFilter');
    const typeFilter = document.getElementById('typeFilter');
    const semesterFilter = document.getElementById('semesterFilter');
    const documentsList = document.getElementById('documentsList');
    const resultsCount = document.getElementById('resultsCount');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');

    if (!documentsList) return; // Exit if not on archives page

    let allDocuments = [];
    let allPrograms = [];

    // --- UI HELPER FUNCTIONS ---
    function showLoader() {
        documentsList.innerHTML = '<div class="loader" style="grid-column: 1/-1; text-align: center; padding: 3rem;"><i class="fas fa-spinner fa-spin fa-3x" style="color: var(--bleu-nuit)"></i></div>';
    }

    function updateResultsCount(count) {
        if (!resultsCount) return;
        if (count > 1) {
            resultsCount.textContent = `${count} résultats trouvés`;
        } else if (count === 1) {
            resultsCount.textContent = `1 résultat trouvé`;
        } else {
            resultsCount.textContent = `Aucun résultat`;
        }
        if (resetFiltersBtn) {
            resetFiltersBtn.style.display = (searchInput.value || schoolFilter.value || programFilter.value || levelFilter.value || (typeFilter && typeFilter.value) || (semesterFilter && semesterFilter.value)) ? 'inline-block' : 'none';
        }
    }

    // --- DATA LOADING ---
    async function loadInitialData() {
        showLoader();
        try {
            const [documents, filters] = await Promise.all([
                fetch('php/documents.php?action=get_documents').then(res => res.json()),
                fetch('php/documents.php?action=get_filters').then(res => res.json())
            ]);

            if (documents.error || filters.error) {
                console.error('Error loading data:', documents.error || filters.error);
                documentsList.innerHTML = '<div class="no-results" style="grid-column: 1/-1;">Erreur de chargement des données.</div>';
                return;
            }
            
            allDocuments = documents;
            allPrograms = filters.filieres;
            populateFilters(filters);
            
            // Check for URL parameters (e.g., from ecoles.php)
            const urlParams = new URLSearchParams(window.location.search);
            const filiereId = urlParams.get('filiere');
            if (filiereId) {
                programFilter.value = filiereId;
            }

            applyFilters();

        } catch (error) {
            console.error('Fetch error:', error);
            documentsList.innerHTML = '<div class="no-results" style="grid-column: 1/-1;">Impossible de se connecter au serveur.</div>';
        }
    }

    // --- FILTER POPULATION ---
    function populateFilters(filters) {
        if (!schoolFilter || !programFilter || !levelFilter) return;

        filters.ecoles.forEach(ecole => {
            const option = document.createElement('option');
            option.value = ecole.id;
            option.textContent = ecole.nom;
            schoolFilter.appendChild(option);
        });

        filters.filieres.forEach(filiere => {
            const option = document.createElement('option');
            option.value = filiere.id;
            option.textContent = filiere.nom;
            programFilter.appendChild(option);
        });

        filters.niveaux.forEach(niveau => {
            const option = document.createElement('option');
            option.value = niveau.id;
            option.textContent = niveau.nom;
            levelFilter.appendChild(option);
        });
    }

    // --- DOCUMENT RENDERING ---
    function renderDocuments(documents) {
        documentsList.innerHTML = '';
        updateResultsCount(documents.length);

        if (documents.length === 0) {
            documentsList.innerHTML = '<div class="no-results" style="grid-column: 1/-1; text-align: center; padding: 3rem; background: white; border-radius: 15px; box-shadow: var(--shadow); color: var(--rouge-nuit); font-weight: 600;">Aucun document ne correspond à vos critères de recherche.</div>';
            return;
        }

        documents.forEach((doc, index) => {
            const card = document.createElement('div');
            card.className = 'doc-card';
            if (doc.type === 'compilation') card.classList.add('compilation-card'); // Special styling
            card.style.animationDelay = `${index * 0.05}s`;
            
            let badgeClass = 'badge-autre';
            let icon = 'fa-file-pdf';
            let typeLabel = doc.type;

            if (doc.type === 'epreuve') {
                badgeClass = 'badge-epreuve';
                icon = 'fa-file-signature';
                typeLabel = 'Épreuve / Examen';
            } else if (doc.type === 'cours') {
                badgeClass = 'badge-cours';
                icon = 'fa-book-open';
                typeLabel = 'Support de Cours';
            } else if (doc.type === 'compilation') {
                badgeClass = 'badge-compilation';
                icon = 'fa-layer-group';
                typeLabel = 'Compilation (Pack)';
            }

            const semesterTag = doc.semestre ? `<span class="semester-tag">${doc.semestre}</span>` : '';

            card.innerHTML = `
                <div class="doc-content">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.8rem;">
                        <span class="doc-type ${badgeClass}"><i class="fas ${icon}"></i> ${typeLabel}</span>
                        ${semesterTag}
                    </div>
                    <h3>${escapeHTML(doc.titre)}</h3>
                    <p style="font-size: 0.9rem; color: #555; margin-bottom: 1rem;">${escapeHTML(doc.description || 'Consultation des archives académiques.')}</p>
                    <div class="doc-meta">
                        <div style="margin-bottom: 0.5rem;"><i class="fas fa-university" style="color: var(--bleu-clair)"></i> ${escapeHTML(doc.ecole_nom)}</div>
                        <div style="margin-bottom: 0.5rem;"><i class="fas fa-graduation-cap" style="color: var(--bleu-clair)"></i> ${escapeHTML(doc.filiere_nom)}</div>
                        <div><i class="fas fa-calendar-alt" style="color: var(--bleu-clair)"></i> ${escapeHTML(doc.niveau)} • ${doc.annee}</div>
                    </div>
                </div>
                <div class="doc-footer">
                    <span style="font-size: 0.8rem; font-weight: 600; color: var(--rouge-nuit); text-transform: uppercase;">${escapeHTML(doc.type === 'compilation' ? 'Semestre complet' : (doc.session || ''))}</span>
                    <a href="php/download.php?file=${encodeURIComponent(doc.file_path.split('/').pop())}" class="download-link">
                        TÉLÉCHARGER <i class="fas fa-download"></i>
                    </a>
                </div>
            `;
            documentsList.appendChild(card);
        });
    }

    // --- FILTERING LOGIC ---
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedSchool = schoolFilter.value;
        const selectedProgram = programFilter.value;
        const selectedLevel = levelFilter.value ? (levelFilter.options[levelFilter.selectedIndex].text === 'Tous les niveaux' ? '' : levelFilter.value) : '';
        const selectedType = typeFilter ? typeFilter.value : '';
        const selectedSemester = semesterFilter ? semesterFilter.value : '';

        const filteredDocuments = allDocuments.filter(doc => {
            const searchContent = `${doc.titre} ${doc.description} ${doc.annee} ${doc.semestre} ${doc.type}`.toLowerCase();
            const matchesSearch = searchContent.includes(searchTerm);
            const matchesSchool = !selectedSchool || String(doc.ecole_id) === String(selectedSchool);
            const matchesProgram = !selectedProgram || String(doc.filiere_id) === String(selectedProgram);
            const matchesLevel = !selectedLevel || String(doc.niveau) === String(selectedLevel);
            const matchesType = !selectedType || String(doc.type) === String(selectedType);
            const matchesSemester = !selectedSemester || String(doc.semestre) === String(selectedSemester);

            return matchesSearch && matchesSchool && matchesProgram && matchesLevel && matchesType && matchesSemester;
        });

        renderDocuments(filteredDocuments);
    }
    
    // --- EVENT LISTENERS ---
    function setupEventListeners() {
        if (searchButton) searchButton.addEventListener('click', applyFilters);
        if (searchInput) {
            searchInput.addEventListener('keyup', (e) => {
                if (e.key === 'Enter') applyFilters();
            });
        }
        if (schoolFilter) schoolFilter.addEventListener('change', handleSchoolChange);
        if (programFilter) programFilter.addEventListener('change', applyFilters);
        if (levelFilter) levelFilter.addEventListener('change', applyFilters);
        if (typeFilter) typeFilter.addEventListener('change', applyFilters);
        if (semesterFilter) semesterFilter.addEventListener('change', applyFilters);
        if (resetFiltersBtn) resetFiltersBtn.addEventListener('click', resetAllFilters);
    }

    // --- DYNAMIC PROGRAM FILTERING & FILTER HANDLERS ---
    function handleSchoolChange() {
        const selectedSchoolId = schoolFilter.value;
        
        programFilter.innerHTML = '<option value="">Toutes les filières</option>';
        
        let programsToShow = [];
        if (selectedSchoolId) {
            const schoolPrograms = allDocuments
                .filter(doc => String(doc.ecole_id) === String(selectedSchoolId))
                .map(doc => ({ id: doc.filiere_id, nom: doc.filiere_nom }));
            
            const uniquePrograms = schoolPrograms.filter((program, index, self) => 
                self.findIndex(p => p.id === program.id) === index
            );
            programsToShow = uniquePrograms;
        } else {
            programsToShow = allPrograms.filter((program, index, self) => 
                self.findIndex(p => p.id === program.id) === index
            );
        }

        programsToShow.forEach(filiere => {
            const option = document.createElement('option');
            option.value = filiere.id;
            option.textContent = filiere.nom;
            programFilter.appendChild(option);
        });
        
        applyFilters();
    }

    // --- RESET FUNCTIONALITY ---
    function resetAllFilters() {
        searchInput.value = '';
        schoolFilter.value = '';
        levelFilter.value = '';
        if (typeFilter) typeFilter.value = '';
        if (semesterFilter) semesterFilter.value = '';
        
        programFilter.innerHTML = '<option value="">Toutes les filières</option>';
        allPrograms.forEach(filiere => {
            const option = document.createElement('option');
            option.value = filiere.id;
            option.textContent = filiere.nom;
            programFilter.appendChild(option);
        });

        applyFilters();
    }

    // --- UTILITY ---
    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return str.toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // --- INITIALIZATION ---
    loadInitialData();
    setupEventListeners();
});
