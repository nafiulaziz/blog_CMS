let deletePostId = null;

function deletePost(id) {
    deletePostId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deletePostId) {
        fetch('delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + deletePostId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                const row = document.querySelector(`tr[data-post-id="${deletePostId}"]`);
                if (row) {
                    row.remove();
                }
                
                // Show success message
                showMessage('Post deleted successfully!', 'success');
            } else {
                showMessage(data.message || 'Failed to delete post', 'error');
            }
            closeDeleteModal();
        })
        .catch(error => {
            showMessage('An error occurred while deleting the post', 'error');
            closeDeleteModal();
        });
    }
});

document.getElementById('cancelDelete').addEventListener('click', closeDeleteModal);

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    deletePostId = null;
}

function showMessage(message, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-md ${
        type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 
        'bg-red-100 border border-red-400 text-red-700'
    }`;
    messageDiv.textContent = message;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}