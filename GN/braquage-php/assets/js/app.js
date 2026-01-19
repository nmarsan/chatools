// Application JavaScript

// Modal handling
const modal = document.getElementById('new-session-modal');
const newSessionBtn = document.getElementById('new-session-btn');
const closeBtn = document.querySelector('.close');

if (newSessionBtn) {
    newSessionBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });
}

if (closeBtn) {
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });
}

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Character tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const character = btn.dataset.character;
        
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        // Update content panels
        document.querySelectorAll('.character-panel').forEach(panel => {
            panel.classList.remove('active');
            if (panel.dataset.character === character) {
                panel.classList.add('active');
            }
        });
    });
});

// New session form
const newSessionForm = document.getElementById('new-session-form');
if (newSessionForm) {
    newSessionForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('name', document.getElementById('session-name').value);
        
        try {
            const response = await fetch('?action=create_session', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                modal.style.display = 'none';
                location.reload();
            } else {
                alert('Erreur: ' + (result.error || 'Impossible de créer la partie'));
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
}

// Session selection
const sessionSelect = document.getElementById('session-select');
if (sessionSelect) {
    sessionSelect.addEventListener('change', async (e) => {
        const sessionId = e.target.value;
        if (!sessionId) return;
        
        const formData = new FormData();
        formData.append('session_id', sessionId);
        
        try {
            const response = await fetch('?action=load_session', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('Erreur: ' + (result.error || 'Impossible de charger la partie'));
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
}

// Delete session
const deleteSessionBtn = document.getElementById('delete-session-btn');
if (deleteSessionBtn) {
    deleteSessionBtn.addEventListener('click', async () => {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cette partie ?')) {
            return;
        }
        
        const sessionId = deleteSessionBtn.dataset.sessionId;
        const formData = new FormData();
        formData.append('session_id', sessionId);
        
        try {
            const response = await fetch('?action=delete_session', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('Erreur: ' + (result.error || 'Impossible de supprimer la partie'));
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
}

// Choice buttons
document.querySelectorAll('.choice-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const sceneId = btn.dataset.targetScene;
        const choiceId = btn.dataset.choiceId;
        
        const formData = new FormData();
        formData.append('scene_id', sceneId);
        formData.append('choice_id', choiceId);
        
        try {
            const response = await fetch('?action=go_to_scene', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('Erreur: ' + (result.error || 'Impossible de changer de scène'));
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
});

// Go back button
const goBackBtn = document.getElementById('go-back-btn');
if (goBackBtn && !goBackBtn.disabled) {
    goBackBtn.addEventListener('click', async () => {
        try {
            const response = await fetch('?action=go_back', {
                method: 'POST'
            });
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('Erreur: ' + (result.error || 'Impossible de revenir en arrière'));
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
}

// Scene tree nodes - click to navigate
document.querySelectorAll('.scene-node:not(.not-accessible)').forEach(node => {
    node.addEventListener('click', async (e) => {
        // Don't navigate if clicking on complete/uncomplete button
        if (e.target.classList.contains('complete-btn') || e.target.classList.contains('uncomplete-btn')) {
            return;
        }
        
        const sceneId = node.dataset.sceneId;
        const formData = new FormData();
        formData.append('scene_id', sceneId);
        
        try {
            const response = await fetch('?action=go_to_scene', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('Erreur: ' + (result.error || 'Impossible de changer de scène'));
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
});

// Complete/Uncomplete scene buttons
document.querySelectorAll('.complete-btn, .uncomplete-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.stopPropagation();
        const sceneId = btn.dataset.sceneId;
        const action = btn.classList.contains('complete-btn') ? 'complete_scene' : 'uncomplete_scene';
        
        const formData = new FormData();
        formData.append('scene_id', sceneId);
        
        try {
            const response = await fetch(`?action=${action}`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('Erreur: ' + (result.error || 'Impossible de modifier la scène'));
            }
        } catch (error) {
            alert('Erreur: ' + error.message);
        }
    });
});

