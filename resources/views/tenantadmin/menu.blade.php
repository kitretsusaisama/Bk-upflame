@extends('layouts.dashboard')

@section('title', 'Menu Management')

@section('breadcrumb')
    <span>Tenant Admin</span> <span class="breadcrumb-sep">/</span> <span>Menu Management</span>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Menu Management</h1>
    <div class="page-actions">
        <button class="btn btn-secondary mr-2" id="clearCache">
            <span>🧹</span> Clear Cache
        </button>
        <button class="btn btn-primary" id="addMenuItem">
            <span>➕</span> Add Menu Item
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Menu Items</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Type</th>
                        <th>Route/URL</th>
                        <th>Permission</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="menuItemsTable">
                    <!-- Menu items will be loaded here via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Menu Item Modal -->
<div class="modal" id="menuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuModalTitle">Add Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="menuForm">
                    <input type="hidden" id="menuId">
                    <div class="mb-3">
                        <label for="menuKey" class="form-label">Key</label>
                        <input type="text" class="form-control" id="menuKey" required>
                    </div>
                    <div class="mb-3">
                        <label for="menuLabel" class="form-label">Label</label>
                        <input type="text" class="form-control" id="menuLabel" required>
                    </div>
                    <div class="mb-3">
                        <label for="menuType" class="form-label">Type</label>
                        <select class="form-control" id="menuType" required>
                            <option value="link">Link</option>
                            <option value="heading">Heading</option>
                            <option value="separator">Separator</option>
                            <option value="module">Module</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="menuRoute" class="form-label">Route</label>
                        <input type="text" class="form-control" id="menuRoute">
                    </div>
                    <div class="mb-3">
                        <label for="menuUrl" class="form-label">URL</label>
                        <input type="text" class="form-control" id="menuUrl">
                    </div>
                    <div class="mb-3">
                        <label for="menuPermission" class="form-label">Permission</label>
                        <input type="text" class="form-control" id="menuPermission">
                    </div>
                    <div class="mb-3">
                        <label for="menuIcon" class="form-label">Icon</label>
                        <input type="text" class="form-control" id="menuIcon">
                    </div>
                    <div class="mb-3">
                        <label for="menuOrder" class="form-label">Order</label>
                        <input type="number" class="form-control" id="menuOrder" value="0">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="menuEnabled" checked>
                        <label class="form-check-label" for="menuEnabled">Enabled</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveMenuItem">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Load menu items when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadMenuItems();
        
        // Add event listeners
        document.getElementById('addMenuItem').addEventListener('click', showAddModal);
        document.getElementById('saveMenuItem').addEventListener('click', saveMenuItem);
        document.getElementById('clearCache').addEventListener('click', clearMenuCache);
    });
    
    // Load menu items from API
    function loadMenuItems() {
        fetch('/api/v1/menus/all')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('menuItemsTable');
                tableBody.innerHTML = '';
                
                data.menus.forEach(menu => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${menu.label}</td>
                        <td>${menu.type}</td>
                        <td>${menu.route || menu.url || ''}</td>
                        <td>${menu.permission || ''}</td>
                        <td>${menu.order}</td>
                        <td>${menu.is_enabled ? 'Enabled' : 'Disabled'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="editMenuItem('${menu.id}')">Edit</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem('${menu.id}')">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error loading menu items:', error);
            });
    }
    
    // Show add menu item modal
    function showAddModal() {
        document.getElementById('menuModalTitle').textContent = 'Add Menu Item';
        document.getElementById('menuForm').reset();
        document.getElementById('menuId').value = '';
        new bootstrap.Modal(document.getElementById('menuModal')).show();
    }
    
    // Edit menu item
    function editMenuItem(id) {
        fetch(`/api/v1/menus/${id}`)
            .then(response => response.json())
            .then(data => {
                const menu = data.menu;
                document.getElementById('menuModalTitle').textContent = 'Edit Menu Item';
                document.getElementById('menuId').value = menu.id;
                document.getElementById('menuKey').value = menu.key;
                document.getElementById('menuLabel').value = menu.label;
                document.getElementById('menuType').value = menu.type;
                document.getElementById('menuRoute').value = menu.route || '';
                document.getElementById('menuUrl').value = menu.url || '';
                document.getElementById('menuPermission').value = menu.permission || '';
                document.getElementById('menuIcon').value = menu.icon || '';
                document.getElementById('menuOrder').value = menu.order;
                document.getElementById('menuEnabled').checked = menu.is_enabled;
                new bootstrap.Modal(document.getElementById('menuModal')).show();
            })
            .catch(error => {
                console.error('Error loading menu item:', error);
            });
    }
    
    // Save menu item
    function saveMenuItem() {
        const id = document.getElementById('menuId').value;
        const data = {
            key: document.getElementById('menuKey').value,
            label: document.getElementById('menuLabel').value,
            type: document.getElementById('menuType').value,
            route: document.getElementById('menuRoute').value || null,
            url: document.getElementById('menuUrl').value || null,
            permission: document.getElementById('menuPermission').value || null,
            icon: document.getElementById('menuIcon').value || null,
            order: parseInt(document.getElementById('menuOrder').value),
            is_enabled: document.getElementById('menuEnabled').checked
        };
        
        const method = id ? 'PUT' : 'POST';
        const url = id ? `/api/v1/menus/${id}` : '/api/v1/menus';
        
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('menuModal')).hide();
            loadMenuItems();
        })
        .catch(error => {
            console.error('Error saving menu item:', error);
        });
    }
    
    // Delete menu item
    function deleteMenuItem(id) {
        if (confirm('Are you sure you want to delete this menu item?')) {
            fetch(`/api/v1/menus/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                loadMenuItems();
            })
            .catch(error => {
                console.error('Error deleting menu item:', error);
            });
        }
    }
    
    // Clear menu cache
    function clearMenuCache() {
        if (confirm('Are you sure you want to clear the menu cache?')) {
            fetch('/api/v1/menus/clear-cache', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                alert('Menu cache cleared successfully');
                loadMenuItems();
            })
            .catch(error => {
                console.error('Error clearing menu cache:', error);
                alert('Error clearing menu cache');
            });
        }
    }
</script>
@endsection