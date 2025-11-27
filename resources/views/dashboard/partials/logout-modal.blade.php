<div id="logoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirm Logout</h3>
            <p style="color: #666; font-size: 14px;">Are you sure you want to logout?</p>
        </div>
        
        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
            @csrf
            
            <label class="checkbox-label">
                <input type="checkbox" name="logout_all" id="logoutAll" value="1">
                <span>Logout from all devices</span>
            </label>
            
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeLogoutModal()" style="background: #95a5a6; color: white;">
                    Cancel
                </button>
                <button type="submit" class="btn btn-danger">
                    Logout
                </button>
            </div>
        </form>
    </div>
</div>
