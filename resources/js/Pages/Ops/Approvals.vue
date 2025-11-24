<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Ops</span> <span class="breadcrumb-sep">/</span> <span>Approvals</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Approval Requests</h1>
    </div>
    
    <Card>
      <template #header>
        <h3 class="card-title">Pending Approvals</h3>
        <div class="card-actions">
          <select class="form-select">
            <option>All Types</option>
            <option>Booking Cancellation</option>
            <option>Service Update</option>
            <option>Account Access</option>
            <option>Payment Refund</option>
          </select>
          <input type="text" placeholder="Search approvals..." class="form-input">
        </div>
      </template>
      
      <Table :headers="['Request ID', 'Type', 'Submitted By', 'Submitted At', 'Details', 'Actions']">
        <tr v-for="i in 10" :key="i">
          <td>#REQ{{ String(i * 9 + 2).padStart(4, '0') }}</td>
          <td>
            <Badge v-if="i % 4 === 0" type="primary">Booking Cancellation</Badge>
            <Badge v-else-if="i % 4 === 1" type="info">Service Update</Badge>
            <Badge v-else-if="i % 4 === 2" type="warning">Account Access</Badge>
            <Badge v-else type="success">Payment Refund</Badge>
          </td>
          <td>
            <div class="table-user">
              <div class="user-avatar">{{ getRandomInitial() }}</div>
              <div class="user-details">
                <div class="user-name">User {{ i }}</div>
              </div>
            </div>
          </td>
          <td>{{ formatDate(subDays(new Date(), i)) }}</td>
          <td>
            <div class="request-details">
              <div class="detail-item">
                <span class="detail-label">Booking:</span>
                <span class="detail-value">#BK{{ String(i * 5 + 3).padStart(4, '0') }}</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Amount:</span>
                <span class="detail-value">${{ (Math.random() * 100 + 50).toFixed(2) }}</span>
              </div>
            </div>
          </td>
          <td>
            <div class="table-actions">
              <button class="btn-icon" title="View Details" @click="showApprovalDetails(i)">👁️</button>
              <button class="btn-icon btn-success" title="Approve" @click="approveRequest(i)">✅</button>
              <button class="btn-icon btn-error" title="Reject" @click="rejectRequest(i)">❌</button>
            </div>
          </td>
        </tr>
      </Table>
      
      <template #footer>
        <div class="pagination">
          <button class="btn btn-outline">Previous</button>
          <span class="pagination-info">Page 1 of 5</span>
          <button class="btn btn-outline">Next</button>
        </div>
      </template>
    </Card>
    
    <!-- Approval Details Modal -->
    <div v-if="showApprovalDetailsModal" class="modal-overlay" @click="showApprovalDetailsModal = false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Approval Request Details</h3>
          <button class="modal-close" @click="showApprovalDetailsModal = false">×</button>
        </div>
        <div class="modal-body">
          <div class="approval-details">
            <div class="detail-group">
              <div class="detail-label">Request ID</div>
              <div class="detail-value">#REQ{{ String(selectedRequest * 9 + 2).padStart(4, '0') }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Type</div>
              <div class="detail-value">
                <Badge v-if="selectedRequest % 4 === 0" type="primary">Booking Cancellation</Badge>
                <Badge v-else-if="selectedRequest % 4 === 1" type="info">Service Update</Badge>
                <Badge v-else-if="selectedRequest % 4 === 2" type="warning">Account Access</Badge>
                <Badge v-else type="success">Payment Refund</Badge>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Submitted By</div>
              <div class="detail-value">
                <div class="table-user">
                  <div class="user-avatar">{{ getRandomInitial() }}</div>
                  <div class="user-details">
                    <div class="user-name">User {{ selectedRequest }}</div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Submitted At</div>
              <div class="detail-value">{{ formatDate(subDays(new Date(), selectedRequest)) }}</div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Reason</div>
              <div class="detail-value">
                Customer requested cancellation due to scheduling conflict. Requested refund of ${{ (Math.random() * 100 + 50).toFixed(2) }}.
              </div>
            </div>
            
            <div class="detail-group">
              <div class="detail-label">Supporting Documents</div>
              <div class="detail-value">
                <div class="document-list">
                  <div class="document-item">
                    <span class="document-icon">📄</span>
                    <span class="document-name">cancellation_request.pdf</span>
                    <button class="btn-icon btn-sm">⬇️</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" @click="showApprovalDetailsModal = false">Close</button>
          <button class="btn btn-error" @click="rejectRequest(selectedRequest)">Reject</button>
          <button class="btn btn-success" @click="approveRequest(selectedRequest)">Approve</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

// Modal state
const showApprovalDetailsModal = ref(false)
const selectedRequest = ref(null)

// Helper functions
function getRandomInitial() {
  const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
  return letters.charAt(Math.floor(Math.random() * letters.length))
}

function formatDate(date) {
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric', 
    year: 'numeric' 
  })
}

function subDays(date, days) {
  const result = new Date(date)
  result.setDate(result.getDate() - days)
  return result
}

// Modal handlers
function showApprovalDetails(requestId) {
  selectedRequest.value = requestId
  showApprovalDetailsModal.value = true
}

function approveRequest(requestId) {
  console.log('Approving request:', requestId)
  alert(`Request #REQ${String(requestId * 9 + 2).padStart(4, '0')} approved successfully!`)
  showApprovalDetailsModal.value = false
}

function rejectRequest(requestId) {
  console.log('Rejecting request:', requestId)
  alert(`Request #REQ${String(requestId * 9 + 2).padStart(4, '0')} rejected!`)
  showApprovalDetailsModal.value = false
}
</script>

<style scoped>
.page-header {
  margin-bottom: 1.5rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
}

.form-select {
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.form-input {
  padding: 0.5rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.table-user {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-avatar {
  width: 2rem;
  height: 2rem;
  border-radius: 9999px;
  background-color: #3b82f6;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 600;
  font-size: 0.75rem;
}

.user-details {
  min-width: 0;
}

.user-name {
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.request-details {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.detail-item {
  display: flex;
  gap: 0.5rem;
  font-size: 0.875rem;
}

.detail-label {
  font-weight: 500;
  color: #64748b;
}

.detail-value {
  color: #1e293b;
}

.table-actions {
  display: flex;
  gap: 0.5rem;
}

.btn-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border-radius: 0.375rem;
  background-color: #f1f5f9;
  color: #64748b;
  text-decoration: none;
  font-size: 0.875rem;
  border: none;
  cursor: pointer;
}

.btn-icon:hover {
  background-color: #e2e8f0;
}

.btn-icon.btn-success {
  background-color: #dcfce7;
  color: #166534;
}

.btn-icon.btn-success:hover {
  background-color: #bbf7d0;
}

.btn-icon.btn-error {
  background-color: #fee2e2;
  color: #991b1b;
}

.btn-icon.btn-error:hover {
  background-color: #fecaca;
}

.pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem 0;
}

.pagination-info {
  color: #64748b;
  font-size: 0.875rem;
}

/* Modal styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #94a3b8;
  padding: 0;
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  color: #64748b;
}

.modal-body {
  padding: 1.5rem;
}

.approval-details {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.detail-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.detail-group .detail-label {
  font-weight: 600;
  color: #1e293b;
}

.document-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.document-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
}

.document-icon {
  font-size: 1.25rem;
}

.document-name {
  flex: 1;
  color: #1e293b;
}

.btn-sm {
  width: 1.5rem;
  height: 1.5rem;
  font-size: 0.75rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1.5rem;
  border-top: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
  .card-actions {
    flex-direction: column;
  }
}
</style>