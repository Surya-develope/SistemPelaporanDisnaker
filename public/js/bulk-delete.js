document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const checkItems = document.querySelectorAll('.checkItem');
    const formBulkDelete = document.getElementById('formBulkDelete');

    // Create Floating Action Bar container dynamically
    const actionBar = document.createElement('div');
    actionBar.id = 'bulkActionBar';
    actionBar.innerHTML = `
        <div class="d-flex align-items-center gap-3">
            <span id="bulkCountText" class="fw-bold" style="font-size: 14px;">0 data terpilih</span>
            <button id="btnConfirmBulkDelete" class="btn btn-danger btn-sm shadow-sm" style="border-radius: 8px;">
                <i class="fa fa-trash me-1"></i> Hapus
            </button>
        </div>
    `;
    
    // Modern CSS for the Action Bar
    Object.assign(actionBar.style, {
        position: 'fixed',
        bottom: '-100px', // Hidden by default (slide up)
        left: '50%',
        transform: 'translateX(-50%)',
        background: '#ffffff',
        border: '1px solid #e2e8f0',
        padding: '12px 24px',
        borderRadius: '50px',
        boxShadow: '0 10px 25px rgba(0,0,0,0.15)',
        zIndex: '1050',
        transition: 'bottom 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        opacity: '0'
    });

    document.body.appendChild(actionBar);

    const btnConfirmBulkDelete = document.getElementById('btnConfirmBulkDelete');
    const bulkCountText = document.getElementById('bulkCountText');

    function updateActionBar() {
        const checkedCount = Array.from(checkItems).filter(cb => cb.checked).length;
        
        if (checkedCount > 0) {
            bulkCountText.innerHTML = `<span class="badge bg-primary rounded-pill me-1">${checkedCount}</span> data terpilih`;
            actionBar.style.bottom = '30px';
            actionBar.style.opacity = '1';
        } else {
            actionBar.style.bottom = '-100px';
            actionBar.style.opacity = '0';
        }

        // Hide the original static button if it exists in the view just in case
        const originalBtn = document.getElementById('btnBulkDelete');
        if (originalBtn) originalBtn.style.display = 'none';
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            checkItems.forEach(cb => cb.checked = this.checked);
            updateActionBar();
        });
    }

    checkItems.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked && checkAll) checkAll.checked = false;
            updateActionBar();
        });
    });

    if (btnConfirmBulkDelete && formBulkDelete) {
        btnConfirmBulkDelete.addEventListener('click', function(e) {
            e.preventDefault();
            
            const selectedIds = Array.from(checkItems)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length === 0) return;

            // Trigger SweetAlert2 Confirmation Dialog
            Swal.fire({
                title: 'Konfirmasi Hapus Data',
                text: `Apakah Anda yakin ingin menghapus ${selectedIds.length} data yang dipilih secara permanen? Data yang dihapus tidak dapat dikembalikan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger ms-2 shadow-sm',
                    cancelButton: 'btn btn-secondary shadow-sm'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Populate hidden input and submit
                    const inputIds = document.createElement('input');
                    inputIds.type = 'hidden';
                    inputIds.name = 'ids';
                    inputIds.value = JSON.stringify(selectedIds);
                    formBulkDelete.appendChild(inputIds);
                    
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    formBulkDelete.submit();
                }
            });
        });
    }
});
