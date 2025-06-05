
// History Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    const historyTable = document.getElementById('historyTable');
    
    if (monthFilter && yearFilter && historyTable) {
        // Filter functionality
        function filterHistory() {
            const selectedMonth = monthFilter.value;
            const selectedYear = yearFilter.value;
            const rows = historyTable.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const dateData = row.getAttribute('data-date');
                const [year, month] = dateData.split('-');
                
                let showRow = true;
                
                if (selectedYear && year !== selectedYear) {
                    showRow = false;
                }
                
                if (selectedMonth && month !== selectedMonth) {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
            
            updateEmptyState();
        }
        
        function updateEmptyState() {
            const visibleRows = historyTable.querySelectorAll('tbody tr:not([style*="display: none"])');
            const emptyMessage = document.getElementById('emptyFilterMessage');
            
            if (visibleRows.length === 0) {
                if (!emptyMessage) {
                    const tbody = historyTable.querySelector('tbody');
                    const emptyRow = document.createElement('tr');
                    emptyRow.id = 'emptyFilterMessage';
                    emptyRow.innerHTML = `
                        <td colspan="7" class="text-center" style="padding: 40px;">
                            <i class="fas fa-search" style="font-size: 2rem; color: #ddd; margin-bottom: 15px;"></i>
                            <p style="color: #666; margin: 0;">Tidak ada data yang sesuai dengan filter</p>
                        </td>
                    `;
                    tbody.appendChild(emptyRow);
                }
            } else {
                if (emptyMessage) {
                    emptyMessage.remove();
                }
            }
        }
        
        monthFilter.addEventListener('change', filterHistory);
        yearFilter.addEventListener('change', filterHistory);
    }
});

// View booking detail function
function viewBookingDetail(bookingId) {
    // Create modal or redirect to detail page
    const modal = document.createElement('div');
    modal.className = 'booking-detail-modal';
    modal.innerHTML = `
        <div class="modal-backdrop" onclick="closeBookingModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle"></i> Detail Booking</h3>
                <button onclick="closeBookingModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Memuat detail booking...</p>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Fetch booking detail via AJAX
    fetch(`api/get_booking_detail.php?id=${bookingId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modalBody = modal.querySelector('.modal-body');
                modalBody.innerHTML = `
                    <div class="detail-grid">
                        <div class="detail-row">
                            <strong>Arena:</strong>
                            <span>${data.booking.arena_name}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Lokasi:</strong>
                            <span>${data.booking.location}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Tanggal:</strong>
                            <span>${data.booking.formatted_date}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Waktu:</strong>
                            <span>${data.booking.start_time} - ${data.booking.end_time}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Durasi:</strong>
                            <span>${data.booking.duration} Jam</span>
                        </div>
                        <div class="detail-row">
                            <strong>Total Harga:</strong>
                            <span class="price-highlight">Rp ${data.booking.total_price}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Status:</strong>
                            <span class="status-badge status-${data.booking.status.toLowerCase()}">${data.booking.status}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Tanggal Booking:</strong>
                            <span>${data.booking.created_at}</span>
                        </div>
                    </div>
                `;
            } else {
                modal.querySelector('.modal-body').innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Gagal memuat detail booking</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modal.querySelector('.modal-body').innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Terjadi kesalahan saat memuat data</p>
                </div>
            `;
        });
}

function closeBookingModal() {
    const modal = document.querySelector('.booking-detail-modal');
    if (modal) {
        modal.remove();
    }
}

// Add modal styles
const modalStyles = `
<style>
.booking-detail-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background: white;
    border-radius: 15px;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
    z-index: 1;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.modal-header h3 {
    margin: 0;
    color: #2D7298;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    color: #666;
    padding: 5px;
}

.modal-body {
    padding: 20px;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #666;
}

.loading i {
    font-size: 2rem;
    margin-bottom: 15px;
}

.detail-grid {
    display: grid;
    gap: 15px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
    border-bottom: none;
}

.price-highlight {
    color: #27ae60;
    font-weight: 600;
    font-size: 1.1rem;
}

.error-message {
    text-align: center;
    padding: 40px;
    color: #e74c3c;
}

.error-message i {
    font-size: 2rem;
    margin-bottom: 15px;
}
</style>
`;

document.head.insertAdjacentHTML('beforeend', modalStyles);
