# 🗄️ Database Schema Plan

## New Tables Required for 46-Menu Implementation

---

## Phase 1 Tables (Priority High)

### 1. leads

```sql
CREATE TABLE leads (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    lead_number VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255),
    address TEXT,
    rt_rw VARCHAR(20),
    kelurahan VARCHAR(100),
    kecamatan VARCHAR(100),
    kabupaten VARCHAR(100),
    provinsi VARCHAR(100),
    kode_pos VARCHAR(10),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    source ENUM('website', 'whatsapp', 'referral', 'walk-in', 'social_media', 'other'),
    interested_package_id BIGINT NULL,
    assigned_to BIGINT NULL,
    status ENUM('new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost') DEFAULT 'new',
    notes TEXT,
    converted_at TIMESTAMP NULL,
    customer_id BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (interested_package_id) REFERENCES packages(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);
```

### 2. payments

```sql
CREATE TABLE payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    payment_number VARCHAR(50) UNIQUE,
    invoice_id BIGINT NOT NULL,
    customer_id BIGINT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method ENUM('cash', 'transfer', 'qris', 'midtrans', 'other') NOT NULL,
    payment_date DATE NOT NULL,
    reference_number VARCHAR(100),
    notes TEXT,
    received_by BIGINT NULL,
    status ENUM('pending', 'confirmed', 'failed', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (received_by) REFERENCES users(id)
);
```

### 3. recurring_billings

```sql
CREATE TABLE recurring_billings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT NOT NULL,
    package_id BIGINT NOT NULL,
    billing_day INT NOT NULL DEFAULT 1,
    amount DECIMAL(15,2) NOT NULL,
    discount DECIMAL(15,2) DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    next_billing_date DATE,
    last_billed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (package_id) REFERENCES packages(id)
);
```

### 4. work_orders

```sql
CREATE TABLE work_orders (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    work_order_number VARCHAR(50) UNIQUE,
    type ENUM('installation', 'repair', 'maintenance', 'relocation', 'disconnection', 'survey') NOT NULL,
    customer_id BIGINT NULL,
    lead_id BIGINT NULL,
    assigned_to BIGINT NULL,
    team_size INT DEFAULT 1,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    scheduled_date DATE,
    scheduled_time TIME,
    estimated_duration INT,
    status ENUM('pending', 'assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    notes TEXT,
    completion_notes TEXT,
    created_by BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (lead_id) REFERENCES leads(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### 5. inventories

```sql
CREATE TABLE inventories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    sku VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    category ENUM('ont', 'router', 'cable', 'connector', 'tools', 'accessories', 'other') NOT NULL,
    description TEXT,
    unit VARCHAR(20) DEFAULT 'pcs',
    quantity INT DEFAULT 0,
    min_quantity INT DEFAULT 5,
    purchase_price DECIMAL(15,2),
    selling_price DECIMAL(15,2),
    location VARCHAR(255),
    supplier_id BIGINT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);
```

### 6. inventory_transactions

```sql
CREATE TABLE inventory_transactions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    inventory_id BIGINT NOT NULL,
    type ENUM('in', 'out', 'adjustment', 'return') NOT NULL,
    quantity INT NOT NULL,
    previous_quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    reference_type VARCHAR(50),
    reference_id BIGINT,
    notes TEXT,
    performed_by BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inventory_id) REFERENCES inventories(id),
    FOREIGN KEY (performed_by) REFERENCES users(id)
);
```

### 7. audit_logs

```sql
CREATE TABLE audit_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    action VARCHAR(50) NOT NULL,
    model_type VARCHAR(255),
    model_id BIGINT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## Phase 2 Tables

### 8. contracts

```sql
CREATE TABLE contracts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    contract_number VARCHAR(50) UNIQUE,
    customer_id BIGINT NOT NULL,
    package_id BIGINT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    monthly_fee DECIMAL(15,2) NOT NULL,
    installation_fee DECIMAL(15,2) DEFAULT 0,
    deposit DECIMAL(15,2) DEFAULT 0,
    contract_duration INT,
    auto_renew BOOLEAN DEFAULT FALSE,
    terms TEXT,
    status ENUM('draft', 'active', 'expired', 'terminated', 'suspended') DEFAULT 'draft',
    signed_at TIMESTAMP NULL,
    signed_by VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (package_id) REFERENCES packages(id)
);
```

### 9. network_devices (OLT, ODP, Routers)

```sql
CREATE TABLE network_devices (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    device_type ENUM('olt', 'odp', 'odc', 'router', 'switch', 'ap') NOT NULL,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100),
    model VARCHAR(100),
    serial_number VARCHAR(100),
    ip_address VARCHAR(45),
    mac_address VARCHAR(17),
    location VARCHAR(255),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    capacity INT,
    used_ports INT DEFAULT 0,
    parent_device_id BIGINT NULL,
    status ENUM('active', 'inactive', 'maintenance', 'faulty') DEFAULT 'active',
    installed_at DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_device_id) REFERENCES network_devices(id)
);
```

### 10. ip_pools

```sql
CREATE TABLE ip_pools (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    network VARCHAR(18) NOT NULL,
    gateway VARCHAR(45),
    dns_primary VARCHAR(45),
    dns_secondary VARCHAR(45),
    type ENUM('public', 'private', 'management') DEFAULT 'private',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 11. ip_addresses

```sql
CREATE TABLE ip_addresses (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    ip_pool_id BIGINT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    customer_id BIGINT NULL,
    device_id BIGINT NULL,
    status ENUM('available', 'assigned', 'reserved', 'blocked') DEFAULT 'available',
    assigned_at TIMESTAMP NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ip_pool_id) REFERENCES ip_pools(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (device_id) REFERENCES network_devices(id)
);
```

### 12. suppliers

```sql
CREATE TABLE suppliers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE,
    name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(255),
    address TEXT,
    city VARCHAR(100),
    payment_terms VARCHAR(100),
    notes TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 13. purchase_orders

```sql
CREATE TABLE purchase_orders (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    po_number VARCHAR(50) UNIQUE,
    supplier_id BIGINT NOT NULL,
    order_date DATE NOT NULL,
    expected_date DATE,
    received_date DATE,
    total_amount DECIMAL(15,2),
    status ENUM('draft', 'submitted', 'approved', 'ordered', 'partial', 'received', 'cancelled') DEFAULT 'draft',
    notes TEXT,
    created_by BIGINT,
    approved_by BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);
```

### 14. purchase_order_items

```sql
CREATE TABLE purchase_order_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    purchase_order_id BIGINT NOT NULL,
    inventory_id BIGINT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    total_price DECIMAL(15,2) NOT NULL,
    received_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (purchase_order_id) REFERENCES purchase_orders(id),
    FOREIGN KEY (inventory_id) REFERENCES inventories(id)
);
```

### 15. assets

```sql
CREATE TABLE assets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    asset_code VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    category ENUM('vehicle', 'equipment', 'furniture', 'electronics', 'building', 'other') NOT NULL,
    brand VARCHAR(100),
    model VARCHAR(100),
    serial_number VARCHAR(100),
    purchase_date DATE,
    purchase_price DECIMAL(15,2),
    current_value DECIMAL(15,2),
    depreciation_rate DECIMAL(5,2),
    location VARCHAR(255),
    assigned_to BIGINT NULL,
    status ENUM('active', 'maintenance', 'disposed', 'lost') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);
```

### 16. attendances

```sql
CREATE TABLE attendances (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    date DATE NOT NULL,
    clock_in TIME,
    clock_out TIME,
    clock_in_location VARCHAR(255),
    clock_out_location VARCHAR(255),
    status ENUM('present', 'late', 'absent', 'leave', 'sick', 'holiday') DEFAULT 'present',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY unique_attendance (user_id, date)
);
```

### 17. payrolls

```sql
CREATE TABLE payrolls (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    period_month INT NOT NULL,
    period_year INT NOT NULL,
    basic_salary DECIMAL(15,2) NOT NULL,
    allowances DECIMAL(15,2) DEFAULT 0,
    deductions DECIMAL(15,2) DEFAULT 0,
    overtime DECIMAL(15,2) DEFAULT 0,
    bonus DECIMAL(15,2) DEFAULT 0,
    net_salary DECIMAL(15,2) NOT NULL,
    status ENUM('draft', 'approved', 'paid') DEFAULT 'draft',
    paid_at TIMESTAMP NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY unique_payroll (user_id, period_month, period_year)
);
```

### 18. customer_messages

```sql
CREATE TABLE customer_messages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT NULL,
    type ENUM('broadcast', 'individual', 'group') NOT NULL,
    channel ENUM('whatsapp', 'email', 'sms', 'push') NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    template_id BIGINT NULL,
    status ENUM('draft', 'scheduled', 'sent', 'failed') DEFAULT 'draft',
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    sent_by BIGINT,
    recipients_count INT DEFAULT 0,
    delivered_count INT DEFAULT 0,
    read_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (sent_by) REFERENCES users(id)
);
```

### 19. installation_reports

```sql
CREATE TABLE installation_reports (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    work_order_id BIGINT NOT NULL,
    customer_id BIGINT NOT NULL,
    technician_id BIGINT NOT NULL,
    ont_serial VARCHAR(100),
    onu_id VARCHAR(50),
    odp_port VARCHAR(50),
    signal_level DECIMAL(6,2),
    speed_test_download DECIMAL(10,2),
    speed_test_upload DECIMAL(10,2),
    photos JSON,
    customer_signature TEXT,
    notes TEXT,
    completed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (work_order_id) REFERENCES work_orders(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (technician_id) REFERENCES users(id)
);
```

### 20. promotions

```sql
CREATE TABLE promotions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    type ENUM('discount_percent', 'discount_amount', 'free_months', 'cashback') NOT NULL,
    value DECIMAL(15,2) NOT NULL,
    min_purchase DECIMAL(15,2) DEFAULT 0,
    max_discount DECIMAL(15,2),
    applicable_packages JSON,
    start_date DATE,
    end_date DATE,
    usage_limit INT,
    used_count INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    terms TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## Phase 3 Tables

### 21-30: Additional tables for advanced features

- `proforma_invoices`
- `credit_notes`
- `bandwidth_profiles`
- `knowledge_base_articles`
- `knowledge_base_categories`
- `sla_policies`
- `technician_locations` (GPS tracking)
- `leaves`
- `leave_types`
- `backups`
- `api_keys`
- `campaigns`
- `campaign_recipients`
- `referrals`
- `referral_rewards`

---

## Summary

| Phase     | New Tables | Total Fields |
| --------- | ---------- | ------------ |
| Phase 1   | 7          | ~80          |
| Phase 2   | 13         | ~150         |
| Phase 3   | 15         | ~120         |
| **Total** | **35**     | **~350**     |

---

_Last Updated: 2026-01-18_
