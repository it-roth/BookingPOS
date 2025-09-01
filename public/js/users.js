document.addEventListener('DOMContentLoaded', function() {
    console.log('Users.js loaded successfully');

    // Handle user deletion with enhanced confirmation
    const deleteButtons = document.querySelectorAll('.delete-user');
    console.log('Found delete buttons:', deleteButtons.length);

    // Debug: Log each button
    deleteButtons.forEach((btn, index) => {
        console.log(`Delete button ${index}:`, btn, 'User ID:', btn.dataset.userId);
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.dataset.userId;
            const username = this.dataset.username;

            console.log('Delete button clicked for user:', userId, username);

            // Enhanced confirmation with SweetAlert2 if available, otherwise fallback to confirm
            if (typeof Swal !== 'undefined') {
                console.log('Using SweetAlert2 for confirmation');
                Swal.fire({
                    title: 'Delete User?',
                    text: `Are you sure you want to delete user "${username}"? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete user!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitDeleteForm(userId);
                    }
                });
            } else {
                // Fallback to native confirm
                console.log('Using native confirm dialog');
                if (confirm(`Are you sure you want to delete user "${username}"?\n\nThis action cannot be undone.`)) {
                    submitDeleteForm(userId);
                }
            }
        });
    });

    function submitDeleteForm(userId) {
        console.log('submitDeleteForm called with userId:', userId);
        const form = document.getElementById(`delete-form-${userId}`);
        console.log('Delete form found:', form);
        console.log('Form action:', form ? form.action : 'N/A');
        console.log('Form method:', form ? form.method : 'N/A');

        if (form) {
            console.log('Submitting delete form for user:', userId);

            // Add loading state to button
            const deleteButton = document.querySelector(`[data-user-id="${userId}"]`);
            console.log('Delete button found:', deleteButton);
            if (deleteButton) {
                deleteButton.disabled = true;
                deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            }

            // Submit the form
            console.log('About to submit form...');
            form.submit();
        } else {
            console.error('Delete form not found for user:', userId);
            console.log('Available forms:', document.querySelectorAll('form[id^="delete-form-"]'));
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error!', 'Delete form not found. Please refresh the page and try again.', 'error');
            } else {
                alert('Error: Delete form not found. Please refresh the page and try again.');
            }
        }
    }

    // Real-time search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit search after 500ms of no typing
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.closest('form').submit();
                }
            }, 500);
        });
    }

    // Form validation enhancement
    const userForm = document.getElementById('user-form');
    if (userForm) {
        userForm.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            }
        });
    }

    // Bulk actions functionality
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');

    if (selectAllCheckbox && userCheckboxes.length > 0) {
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        // Individual checkbox functionality
        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkActions();

                // Update select all checkbox state
                const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
                selectAllCheckbox.checked = checkedCount === userCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < userCheckboxes.length;
            });
        });
    }

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkActions.classList.remove('d-none');
            selectedCount.textContent = `${count} selected`;
        } else {
            bulkActions.classList.add('d-none');
        }
    }
});

// Global functions for bulk actions
function selectAll() {
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.dispatchEvent(new Event('change'));
    }
}

function bulkToggleStatus() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    if (checkedBoxes.length === 0) return;

    const userIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Toggle Status?',
            text: `Toggle status for ${userIds.length} selected users?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, toggle status!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implementation would require a new route for bulk status toggle
                console.log('Bulk toggle status for users:', userIds);
                Swal.fire('Info', 'Bulk status toggle feature needs to be implemented in the backend.', 'info');
            }
        });
    }
}

function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    if (checkedBoxes.length === 0) return;

    const userIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Users?',
            text: `Are you sure you want to delete ${userIds.length} selected users? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implementation would require a new route for bulk delete
                console.log('Bulk delete users:', userIds);
                Swal.fire('Info', 'Bulk delete feature needs to be implemented in the backend.', 'info');
            }
        });
    }
}