
// Booking Actions JavaScript
function cancelBooking(bookingId) {
    if (confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
        const formData = new FormData();
        formData.append('action', 'cancel_booking');
        formData.append('booking_id', bookingId);
        
        fetch('api/booking_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Booking berhasil dibatalkan');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('error', data.message || 'Gagal membatalkan booking');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat membatalkan booking');
        });
    }
}

function viewDetails(bookingId) {
    viewBookingDetail(bookingId);
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} floating-alert`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Add floating alert styles
const alertStyles = `
<style>
.floating-alert {
    position: fixed;
    top: 90px;
    right: 20px;
    z-index: 1500;
    max-width: 300px;
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', alertStyles);
