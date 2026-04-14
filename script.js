document.addEventListener('DOMContentLoaded', () => {
    const btnYes = document.getElementById('btn-yes');
    const btnNo = document.getElementById('btn-no');
    
    const modalIdentify = document.getElementById('identify-modal');
    const submitIdentifyBtn = document.getElementById('submit-identify');
    const identityInput = document.getElementById('identity-name');
    
    const modalReason = document.getElementById('reason-modal');
    const submitReasonBtn = document.getElementById('submit-reason');
    const declineReasonInput = document.getElementById('decline-reason');
    const cancelReasonBtn = document.getElementById('cancel-reason');
    
    const rsvpControls = document.getElementById('rsvp-controls');
    const alreadyConfirmedBox = document.getElementById('already-confirmed');

    let userFullName = localStorage.getItem('userRetiroName');
    let rsvpDone = localStorage.getItem('userRsvpDone');

    // 1. Verificar Identidad
    if (!userFullName) {
        modalIdentify.style.display = 'flex';
    } else {
        checkRsvpStatus();
    }

    submitIdentifyBtn.addEventListener('click', () => {
        const name = identityInput.value.trim();
        if (name.length < 3) {
            alert('Por favor, ingresa tu nombre completo.');
            return;
        }
        localStorage.setItem('userRetiroName', name);
        userFullName = name;
        modalIdentify.style.display = 'none';
        checkRsvpStatus();
    });

    // 2. Verificar si ya hizo RSVP
    function checkRsvpStatus() {
        if (rsvpDone) {
            rsvpControls.style.display = 'none';
            alreadyConfirmedBox.style.display = 'block';
        }
    }

    // 3. Acciones de Botones
    btnYes.addEventListener('click', () => {
        sendConfirmation('Sí', '');
    });

    btnNo.addEventListener('click', () => {
        modalReason.style.display = 'flex';
    });

    cancelReasonBtn.addEventListener('click', () => {
        modalReason.style.display = 'none';
    });

    submitReasonBtn.addEventListener('click', () => {
        const reason = declineReasonInput.value.trim();
        if (reason.length < 5) {
            alert('Por favor, escribe un motivo válido para tu ausencia.');
            return;
        }
        sendConfirmation('No', reason);
        modalReason.style.display = 'none';
    });

    // 4. Envío de Datos (Email + CSV)
    function sendConfirmation(status, reason) {
        const formData = new FormData();
        formData.append('nombre', userFullName);
        formData.append('asistencia', status);
        formData.append('motivo', reason);

        // Feedback visual en botones
        const targetBtn = (status === 'Sí') ? btnYes : btnNo;
        const originalText = targetBtn.innerText;
        targetBtn.disabled = true;
        targetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch('registro.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            targetBtn.disabled = false;
            targetBtn.innerText = originalText;

            if (data.status === 'success') {
                localStorage.setItem('userRsvpDone', 'true');
                rsvpDone = 'true';
                checkRsvpStatus();
                
                if (status === 'Sí') {
                    alert('¡Gracias! Tu confirmación ha sido enviada con éxito.');
                } else {
                    alert('Entendido. Tu motivo ha sido enviado. Te extrañaremos en el retiro.');
                }
            } else {
                alert('Error al enviar la confirmación. Inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            targetBtn.disabled = false;
            targetBtn.innerText = originalText;
            alert('Error de conexión. Verifica tu internet.');
        });
    }
});
