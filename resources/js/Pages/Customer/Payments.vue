<template>
  <DashboardLayout>
    <template #breadcrumb>
      <span>Customer</span> <span class="breadcrumb-sep">/</span> <span>Payments</span>
    </template>
    
    <div class="page-header">
      <h1 class="page-title">Payment History</h1>
      <div class="page-actions">
        <button class="btn btn-primary">
          <span>💳</span> Add Payment Method
        </button>
      </div>
    </div>
    
    <div class="grid grid-2">
      <Card title="Payment Methods">
        <div class="payment-methods">
          <div class="payment-method">
            <div class="payment-icon">💳</div>
            <div class="payment-details">
              <div class="payment-name">Visa ending in 1234</div>
              <div class="payment-meta">Expires 12/25</div>
            </div>
            <div class="payment-actions">
              <button class="btn btn-sm btn-outline">Edit</button>
              <button class="btn btn-sm btn-error">Remove</button>
            </div>
          </div>
          
          <div class="payment-method">
            <div class="payment-icon">🏦</div>
            <div class="payment-details">
              <div class="payment-name">Bank Account ending in 5678</div>
              <div class="payment-meta">Checking Account</div>
            </div>
            <div class="payment-actions">
              <button class="btn btn-sm btn-outline">Edit</button>
              <button class="btn btn-sm btn-error">Remove</button>
            </div>
          </div>
        </div>
      </Card>
      
      <Card title="Billing Information">
        <div class="billing-info">
          <div class="form-group">
            <label class="form-label">Billing Address</label>
            <div class="billing-address">
              <div>John Doe</div>
              <div>123 Main Street</div>
              <div>Anytown, ST 12345</div>
              <div>United States</div>
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">Tax ID</label>
            <div class="tax-id">Not provided</div>
          </div>
          
          <button class="btn btn-outline">Update Billing Information</button>
        </div>
      </Card>
    </div>
    
    <Card title="Transaction History">
      <template #header>
        <h3 class="card-title">Recent Transactions</h3>
        <div class="card-actions">
          <select class="form-select">
            <option>Last 30 days</option>
            <option>Last 3 months</option>
            <option>Last 6 months</option>
            <option>Last year</option>
          </select>
          <input type="text" placeholder="Search transactions..." class="form-input">
        </div>
      </template>
      
      <Table :headers="['Date', 'Description', 'Booking ID', 'Payment Method', 'Amount', 'Status']">
        <tr v-for="i in 10" :key="i">
          <td>{{ formatDate(subDays(new Date(), i)) }}</td>
          <td>Service {{ i }} booking</td>
          <td>#BK{{ String(i * 3).padStart(4, '0') }}</td>
          <td>
            <div class="payment-method-small">
              <span>💳</span>
              <span>Visa ending in {{ 1000 + i * 123 }}</span>
            </div>
          </td>
          <td class="amount">${{ (Math.random() * 100 + 50).toFixed(2) }}</td>
          <td>
            <Badge type="success">Completed</Badge>
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
  </DashboardLayout>
</template>

<script setup>
import DashboardLayout from '@/Layouts/Dashboard/DashboardLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Table from '@/Components/UI/Table.vue'
import Badge from '@/Components/UI/Badge.vue'

// Helper functions
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
</script>

<style scoped>
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.page-actions {
  display: flex;
  gap: 0.5rem;
}

.grid {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.grid-2 {
  grid-template-columns: 2fr 1fr;
}

.payment-methods {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.payment-method {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
}

.payment-icon {
  font-size: 1.5rem;
}

.payment-details {
  flex: 1;
}

.payment-name {
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.payment-meta {
  font-size: 0.875rem;
  color: #64748b;
}

.payment-actions {
  display: flex;
  gap: 0.5rem;
}

.btn-error {
  background-color: #fee2e2;
  border-color: #fecaca;
  color: #991b1b;
}

.btn-error:hover {
  background-color: #fecaca;
}

.billing-info {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  margin-bottom: 0;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #1e293b;
}

.billing-address {
  background-color: #f8fafc;
  padding: 1rem;
  border-radius: 0.375rem;
  border: 1px solid #e2e8f0;
}

.tax-id {
  background-color: #f8fafc;
  padding: 1rem;
  border-radius: 0.375rem;
  border: 1px solid #e2e8f0;
  color: #64748b;
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

.payment-method-small {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.amount {
  font-weight: 600;
  color: #1e293b;
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

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .page-header {
    flex-direction: column;
    align-items: stretch;
    gap: 1rem;
  }
  
  .page-actions {
    justify-content: flex-end;
  }
}
</style>