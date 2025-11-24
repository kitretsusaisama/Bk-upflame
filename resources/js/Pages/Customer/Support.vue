<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Customer</span> <span class="breadcrumb-sep">/</span> <span>Support</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Support Center</h1>
    </div>
    
    <div class="grid grid-2">
      <Card title="Submit a Ticket">
        <form @submit.prevent="submitTicket">
          <div class="form-group">
            <label class="form-label">Subject</label>
            <input type="text" class="form-input" v-model="ticketForm.subject" placeholder="Brief description of your issue">
          </div>
          
          <div class="form-group">
            <label class="form-label">Category</label>
            <select class="form-select" v-model="ticketForm.category">
              <option value="">Select a category</option>
              <option value="booking">Booking Issues</option>
              <option value="payment">Payment Problems</option>
              <option value="account">Account Access</option>
              <option value="service">Service Quality</option>
              <option value="technical">Technical Support</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div class="form-group">
            <label class="form-label">Priority</label>
            <div class="priority-options">
              <label class="priority-option">
                <input type="radio" v-model="ticketForm.priority" value="low">
                <span class="priority-label priority-low">Low</span>
              </label>
              <label class="priority-option">
                <input type="radio" v-model="ticketForm.priority" value="medium">
                <span class="priority-label priority-medium">Medium</span>
              </label>
              <label class="priority-option">
                <input type="radio" v-model="ticketForm.priority" value="high">
                <span class="priority-label priority-high">High</span>
              </label>
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea class="form-textarea" v-model="ticketForm.description" rows="6" placeholder="Please provide detailed information about your issue"></textarea>
          </div>
          
          <div class="form-group">
            <label class="form-label">Attachments</label>
            <div class="file-upload">
              <button type="button" class="btn btn-outline btn-sm">Choose Files</button>
              <span class="file-info">No files selected</span>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit Ticket</button>
          </div>
        </form>
      </Card>
      
      <div class="support-sidebar">
        <Card title="Contact Information">
          <div class="contact-info">
            <div class="contact-item">
              <div class="contact-icon">📧</div>
              <div class="contact-details">
                <div class="contact-label">Email</div>
                <div class="contact-value">support@example.com</div>
              </div>
            </div>
            
            <div class="contact-item">
              <div class="contact-icon">📞</div>
              <div class="contact-details">
                <div class="contact-label">Phone</div>
                <div class="contact-value">+1 (555) 123-4567</div>
              </div>
            </div>
            
            <div class="contact-item">
              <div class="contact-icon">🕒</div>
              <div class="contact-details">
                <div class="contact-label">Hours</div>
                <div class="contact-value">Mon-Fri: 9AM-6PM EST</div>
              </div>
            </div>
          </div>
        </Card>
        
        <Card title="Quick Help">
          <div class="quick-help">
            <div class="help-item">
              <h4 class="help-title">Booking Issues</h4>
              <p class="help-description">Troubleshoot problems with creating or managing bookings.</p>
              <a href="#" class="help-link">View Guide</a>
            </div>
            
            <div class="help-item">
              <h4 class="help-title">Payment Problems</h4>
              <p class="help-description">Resolve issues with payments, refunds, or billing.</p>
              <a href="#" class="help-link">View Guide</a>
            </div>
            
            <div class="help-item">
              <h4 class="help-title">Account Access</h4>
              <p class="help-description">Get help with login, password, or account settings.</p>
              <a href="#" class="help-link">View Guide</a>
            </div>
          </div>
        </Card>
      </div>
    </div>
    
    <Card title="Recent Tickets">
      <Table :headers="['Ticket ID', 'Subject', 'Category', 'Date', 'Status', 'Actions']">
        <tr v-for="i in 5" :key="i">
          <td class="ticket-id">#TK{{ String(i * 7).padStart(4, '0') }}</td>
          <td>Issue with booking #BK{{ String(i * 3).padStart(4, '0') }}</td>
          <td>
            <Badge v-if="i % 3 === 0" type="info">Booking</Badge>
            <Badge v-else-if="i % 3 === 1" type="warning">Payment</Badge>
            <Badge v-else type="success">Account</Badge>
          </td>
          <td>{{ formatDate(subDays(new Date(), i)) }}</td>
          <td>
            <Badge v-if="i % 2 === 0" type="success">Resolved</Badge>
            <Badge v-else type="warning">Open</Badge>
          </td>
          <td>
            <a href="#" class="btn btn-sm btn-outline">View</a>
          </td>
        </tr>
      </Table>
    </Card>
  </DashboardLayout>
</template>

<script setup>
import { reactive } from 'vue'
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

// Form data
const ticketForm = reactive({
  subject: '',
  category: '',
  priority: 'medium',
  description: ''
})

// Form submission handler
function submitTicket() {
  console.log('Submitting ticket:', ticketForm)
  // In a real app, you would send this data to your backend
  alert('Ticket submitted successfully! We will respond within 24 hours.')
  
  // Reset form
  ticketForm.subject = ''
  ticketForm.category = ''
  ticketForm.priority = 'medium'
  ticketForm.description = ''
}

// Helper functions
function formatDate(date) {
  return date.toLocaleDateString('en-US', { 
    month: 'short', 
    day: 'numeric' 
  })
}

function subDays(date, days) {
  const result = new Date(date)
  result.setDate(result.getDate() - days)
  return result
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

.grid {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid-2 {
  grid-template-columns: 2fr 1fr;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #1e293b;
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.priority-options {
  display: flex;
  gap: 1rem;
}

.priority-option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.priority-label {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
}

.priority-low {
  background-color: #dcfce7;
  color: #166534;
}

.priority-medium {
  background-color: #fef3c7;
  color: #92400e;
}

.priority-high {
  background-color: #fee2e2;
  color: #991b1b;
}

.file-upload {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.file-info {
  color: #64748b;
  font-size: 0.875rem;
}

.form-actions {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
}

.support-sidebar {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.contact-item {
  display: flex;
  gap: 1rem;
}

.contact-icon {
  font-size: 1.5rem;
}

.contact-details {
  flex: 1;
}

.contact-label {
  font-size: 0.875rem;
  color: #64748b;
  margin-bottom: 0.25rem;
}

.contact-value {
  font-weight: 500;
  color: #1e293b;
}

.quick-help {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.help-item {
  padding-bottom: 1rem;
  border-bottom: 1px solid #f1f5f9;
}

.help-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.help-title {
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}

.help-description {
  color: #64748b;
  margin: 0 0 0.5rem 0;
  font-size: 0.875rem;
  line-height: 1.4;
}

.help-link {
  color: #3b82f6;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 500;
}

.help-link:hover {
  text-decoration: underline;
}

.ticket-id {
  font-weight: 600;
  color: #1e293b;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .priority-options {
    flex-direction: column;
    gap: 0.5rem;
  }
}
</style>