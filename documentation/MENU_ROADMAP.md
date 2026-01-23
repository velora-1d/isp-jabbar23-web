# 📋 JABBAR23 ISP - Menu Roadmap Plan

## Complete 46-Menu Implementation for International-Standard ISP Management System

> ## Document Version: 1.0
>
> **Created:** 2026-01-18  
> **Status:** Planning Phase

---

## 📊 Executive Summary

| Metric                       | Value  |
| ---------------------------- | ------ |
| Total Menus Planned          | **46** |
| Currently Implemented        | **46** |
| To Be Developed              | **0**  |
| Total Roles                  | **8**  |
| Estimated Development Phases | **4**  |

---

## 🎭 ROLES DEFINITION

| #   | Role ID       | Role Name        | Description                          |
| --- | ------------- | ---------------- | ------------------------------------ |
| 1   | `super-admin` | Super Admin      | Full system access, all menus        |
| 2   | `sales-cs`    | Sales & CS       | Customer acquisition & support       |
| 3   | `finance`     | Finance          | Billing, payments, financial reports |
| 4   | `warehouse`   | Admin Gudang     | Inventory & asset management         |
| 5   | `noc`         | Admin NOC        | Network operations center            |
| 6   | `technician`  | Teknisi Lapangan | Field installation & maintenance     |
| 7   | `hrd`         | HRD Manager      | Human resources & payroll            |
| 8   | `reseller`    | Reseller         | Partner/reseller portal              |

---

## 📁 COMPLETE MENU STRUCTURE (46 MENUS)

### KATEGORI 1: CORE OPERATIONS (5 Menus)

| #   | Menu Name        | Route        | Icon            | Roles                                   | Status  | Priority |
| --- | ---------------- | ------------ | --------------- | --------------------------------------- | ------- | -------- |
| 1   | Dashboard        | `/dashboard` | `chart-pie`     | ALL                                     | ✅ Done | -        |
| 2   | Customers        | `/customers` | `users`         | SA, Sales, Finance, NOC, Tech, Reseller | ✅ Done | -        |
| 3   | Leads/Prospects  | `/leads`     | `user-plus`     | SA, Sales, Reseller                     | ✅ Done | -        |
| 4   | Packages/Tariffs | `/packages`  | `gift`          | SA, Sales, Finance, Reseller            | ✅ Done | -        |
| 5   | Contracts        | `/contracts` | `document-text` | SA, Sales, Finance                      | ✅ Done | -        |

**Legend:** SA=Super Admin, Sales=Sales & CS, Finance=Finance, Warehouse=Admin Gudang, NOC=Admin NOC, Tech=Teknisi, HRD=HRD Manager, Reseller=Reseller

---

### KATEGORI 2: BILLING & FINANCE (7 Menus)

| #   | Menu Name         | Route                        | Icon                 | Roles                 | Status  | Priority |
| --- | ----------------- | ---------------------------- | -------------------- | --------------------- | ------- | -------- |
| 6   | Invoices          | `/invoices`                  | `document-duplicate` | SA, Finance, Reseller | ✅ Done | -        |
| 7   | Payments          | `/payments`                  | `credit-card`        | SA, Finance           | ✅ Done | -        |
| 8   | Recurring Billing | `/billing/recurring`         | `refresh`            | SA, Finance           | ✅ Done | -        |
| 9   | Proforma Invoice  | `/billing/proforma`          | `document`           | SA, Finance           | ✅ Done | -        |
| 10  | Credit Notes      | `/billing/credit-notes`      | `receipt-refund`     | SA, Finance           | ✅ Done | -        |
| 11  | Financial Reports | `/reports`                   | `chart-bar`          | SA, Finance           | ✅ Done | -        |
| 12  | Payment Gateways  | `/settings/payment-gateways` | `cog`                | SA                    | ✅ Done | -        |

---

### KATEGORI 3: NETWORK & INFRASTRUCTURE (7 Menus)

| #   | Menu Name             | Route                 | Icon          | Roles         | Status  | Priority |
| --- | --------------------- | --------------------- | ------------- | ------------- | ------- | -------- |
| 13  | Network Monitoring    | `/network/monitoring` | `server`      | SA, NOC       | ✅ Done | -        |
| 14  | OLT Management        | `/network/olt`        | `cube`        | SA, NOC       | ✅ Done | -        |
| 15  | ODP/ODC Management    | `/network/odp`        | `map-pin`     | SA, NOC, Tech | ✅ Done | -        |
| 16  | Routers/Mikrotik      | `/network/routers`    | `wifi`        | SA, NOC       | ✅ Done | -        |
| 17  | IP Address Management | `/network/ipam`       | `globe`       | SA, NOC       | ✅ Done | -        |
| 18  | Bandwidth Management  | `/network/bandwidth`  | `adjustments` | SA, NOC       | ✅ Done | -        |
| 19  | Network Topology      | `/network/topology`   | `share`       | SA, NOC, Tech | ✅ Done | -        |

---

### KATEGORI 4: SUPPORT & HELPDESK (4 Menus)

| #   | Menu Name         | Route             | Icon        | Roles                | Status  | Priority |
| --- | ----------------- | ----------------- | ----------- | -------------------- | ------- | -------- |
| 20  | Tickets           | `/tickets`        | `ticket`    | SA, Sales, NOC, Tech | ✅ Done | -        |
| 21  | Knowledge Base    | `/knowledge-base` | `book-open` | SA, Sales, NOC, Tech | ✅ Done | -        |
| 22  | SLA Management    | `/sla`            | `clock`     | SA, NOC              | ✅ Done | -        |
| 23  | Customer Messages | `/messages`       | `chat`      | SA, Sales            | ✅ Done | -        |

---

### KATEGORI 5: FIELD OPERATIONS (5 Menus)

| #   | Menu Name            | Route                   | Icon              | Roles         | Status  | Priority |
| --- | -------------------- | ----------------------- | ----------------- | ------------- | ------- | -------- |
| 24  | Technicians          | `/technicians`          | `wrench`          | SA, NOC       | ✅ Done | -        |
| 25  | Work Orders          | `/work-orders`          | `clipboard-list`  | SA, NOC, Tech | ✅ Done | -        |
| 26  | Scheduling           | `/scheduling`           | `calendar`        | SA, NOC, Tech | ✅ Done | -        |
| 27  | GPS Tracking         | `/tracking`             | `location-marker` | SA, NOC       | ✅ Done | -        |
| 28  | Installation Reports | `/installation-reports` | `document-report` | SA, NOC, Tech | ✅ Done | -        |

---

### KATEGORI 6: INVENTORY & ASSETS (4 Menus)

| #   | Menu Name         | Route              | Icon               | Roles                  | Status  | Priority |
| --- | ----------------- | ------------------ | ------------------ | ---------------------- | ------- | -------- |
| 29  | Inventory         | `/inventory`       | `archive`          | SA, Warehouse          | ✅ Done | -        |
| 30  | Assets            | `/assets`          | `desktop-computer` | SA, Warehouse          | ✅ Done | -        |
| 31  | Suppliers/Vendors | `/vendors`         | `truck`            | SA, Warehouse          | ✅ Done | -        |
| 32  | Purchase Orders   | `/purchase-orders` | `shopping-cart`    | SA, Warehouse, Finance | ✅ Done | -        |

---

### KATEGORI 7: HRD & INTERNAL (4 Menus)

| #   | Menu Name        | Route         | Icon           | Roles            | Status                | Priority |
| --- | ---------------- | ------------- | -------------- | ---------------- | --------------------- | -------- |
| 33  | Employees        | `/employees`  | `users`        | SA, HRD          | ✅ Done (as Karyawan) | -        |
| 34  | Attendance       | `/attendance` | `finger-print` | SA, HRD          | ✅ Done               | -        |
| 35  | Payroll          | `/payroll`    | `cash`         | SA, HRD, Finance | ✅ Done               | -        |
| 36  | Leave Management | `/leave`      | `calendar`     | SA, HRD          | ✅ Done               | -        |

---

### KATEGORI 8: ADMINISTRATION (6 Menus)

| #   | Menu Name           | Route             | Icon           | Roles | Status             | Priority |
| --- | ------------------- | ----------------- | -------------- | ----- | ------------------ | -------- |
| 37  | User Management     | `/users`          | `user-circle`  | SA    | ✅ Done            | -        |
| 38  | Roles & Permissions | `/roles`          | `shield-check` | SA    | ✅ Done (in Users) | -        |
| 39  | Settings            | `/settings`       | `cog`          | SA    | ✅ Done            | -        |
| 40  | Audit Logs          | `/audit-logs`     | `eye`          | SA    | ✅ Done            | -        |
| 41  | Backup & Restore    | `/backup`         | `database`     | SA    | ✅ Done            | -        |
| 42  | API Management      | `/api-management` | `code`         | SA    | ✅ Done            | -        |

---

### KATEGORI 9: MARKETING & CRM (4 Menus)

| #   | Menu Name           | Route         | Icon           | Roles              | Status  | Priority |
| --- | ------------------- | ------------- | -------------- | ------------------ | ------- | -------- |
| 43  | Partners/Resellers  | `/partners`   | `user-group`   | SA, Sales          | ✅ Done | -        |
| 44  | Campaigns           | `/campaigns`  | `speakerphone` | SA, Sales          | ✅ Done | -        |
| 45  | Promotions/Vouchers | `/promotions` | `tag`          | SA, Sales, Finance | ✅ Done | -        |
| 46  | Referral Program    | `/referrals`  | `share`        | SA, Sales          | ✅ Done | -        |

---

## 📅 IMPLEMENTATION PHASES

### Phase 1: Core Business (PRIORITY 1) - 7 Menus

### Timeline: Sprint 1-2

| #         | Menu                 | Category  | Est. Days   |
| --------- | -------------------- | --------- | ----------- |
| 1         | Leads/Prospects      | Core      | 3           |
| 2         | Payments (Dedicated) | Finance   | 2           |
| 3         | Recurring Billing    | Finance   | 4           |
| 4         | Network Monitoring   | Network   | 5           |
| 5         | Work Orders          | Field Ops | 4           |
| 6         | Scheduling           | Field Ops | 3           |
| 7         | Inventory            | Inventory | 4           |
| 8         | Audit Logs           | Admin     | 2           |
| **Total** |                      |           | **27 days** |

---

### Phase 2: Operations Enhancement (PRIORITY 2) - 12 Menus

### Timeline: Sprint 3-5

| #         | Menu                    | Category  | Est. Days   |
| --------- | ----------------------- | --------- | ----------- |
| 1         | Contracts               | Core      | 3           |
| 2         | Payment Gateways Config | Finance   | 2           |
| 3         | OLT Management          | Network   | 5           |
| 4         | ODP/ODC Management      | Network   | 4           |
| 5         | Routers/Mikrotik        | Network   | 5           |
| 6         | IP Address Management   | Network   | 3           |
| 7         | Customer Messages       | Support   | 3           |
| 8         | Installation Reports    | Field Ops | 2           |
| 9         | Assets                  | Inventory | 3           |
| 10        | Suppliers/Vendors       | Inventory | 2           |
| 11        | Purchase Orders         | Inventory | 3           |
| 12        | Attendance              | HRD       | 3           |
| 13        | Payroll                 | HRD       | 4           |
| 14        | Promotions/Vouchers     | Marketing | 3           |
| **Total** |                         |           | **45 days** |

---

### Phase 3: Advanced Features (PRIORITY 3) - 14 Menus

### Timeline: Sprint 6-8

| #         | Menu                 | Category  | Est. Days   |
| --------- | -------------------- | --------- | ----------- |
| 1         | Proforma Invoice     | Finance   | 2           |
| 2         | Credit Notes         | Finance   | 2           |
| 3         | Bandwidth Management | Network   | 4           |
| 4         | Network Topology     | Network   | 5           |
| 5         | Knowledge Base       | Support   | 4           |
| 6         | SLA Management       | Support   | 3           |
| 7         | GPS Tracking         | Field Ops | 5           |
| 8         | Leave Management     | HRD       | 2           |
| 9         | Backup & Restore     | Admin     | 3           |
| 10        | API Management       | Admin     | 4           |
| 11        | Campaigns            | Marketing | 3           |
| 12        | Referral Program     | Marketing | 3           |
| **Total** |                      |           | **40 days** |

---

## 🗂️ SIDEBAR STRUCTURE (Grouped)

```text
📊 MAIN
├── Dashboard
│
📋 CRM & SALES
├── Leads/Prospects
├── Customers
├── Contracts
├── Partners/Resellers
│
💰 BILLING & FINANCE
├── Invoices
├── Payments
├── Recurring Billing
├── Proforma Invoice
├── Credit Notes
├── Financial Reports
│
🌐 NETWORK
├── Network Monitoring
├── OLT Management
├── ODP/ODC Management
├── Routers/Mikrotik
├── IP Address Management
├── Bandwidth Management
├── Network Topology
│
🎫 SUPPORT
├── Tickets
├── Customer Messages
├── Knowledge Base
├── SLA Management
│
🔧 FIELD OPERATIONS
├── Technicians
├── Work Orders
├── Scheduling
├── Installation Reports
├── GPS Tracking
│
📦 INVENTORY
├── Inventory
├── Assets
├── Suppliers/Vendors
├── Purchase Orders
│
👥 HRD
├── Employees
├── Attendance
├── Payroll
├── Leave Management
│
📢 MARKETING
├── Campaigns
├── Promotions/Vouchers
├── Referral Program
│
⚙️ SETTINGS
├── General Settings
├── Payment Gateways
├── User Management
├── Roles & Permissions
├── Audit Logs
├── Backup & Restore
├── API Management
├── Packages/Tariffs
```

---

## 🔐 ROLE-MENU ACCESS MATRIX

```text
┌─────────────────────────┬────┬───────┬───────┬───────┬─────┬──────┬─────┬─────────┐
│ Menu                    │ SA │ Sales │ Fin   │ WH    │ NOC │ Tech │ HRD │ Reseller│
├─────────────────────────┼────┼───────┼───────┼───────┼─────┼──────┼─────┼─────────┤
│ Dashboard               │ ✓  │ ✓     │ ✓     │ ✓     │ ✓   │ ✓    │ ✓   │ ✓       │
│ Leads/Prospects         │ ✓  │ ✓     │       │       │     │      │     │ ✓       │
│ Customers               │ ✓  │ ✓     │ ✓     │       │ ✓   │ ✓    │     │ ✓       │
│ Contracts               │ ✓  │ ✓     │ ✓     │       │     │      │     │         │
│ Partners/Resellers      │ ✓  │ ✓     │       │       │     │      │     │         │
│ Invoices                │ ✓  │       │ ✓     │       │     │      │     │ ✓       │
│ Payments                │ ✓  │       │ ✓     │       │     │      │     │         │
│ Recurring Billing       │ ✓  │       │ ✓     │       │     │      │     │         │
│ Proforma Invoice        │ ✓  │       │ ✓     │       │     │      │     │         │
│ Credit Notes            │ ✓  │       │ ✓     │       │     │      │     │         │
│ Financial Reports       │ ✓  │       │ ✓     │       │     │      │     │         │
│ Network Monitoring      │ ✓  │       │       │       │ ✓   │      │     │         │
│ OLT Management          │ ✓  │       │       │       │ ✓   │      │     │         │
│ ODP/ODC Management      │ ✓  │       │       │       │ ✓   │ ✓    │     │         │
│ Routers/Mikrotik        │ ✓  │       │       │       │ ✓   │      │     │         │
│ IP Address Management   │ ✓  │       │       │       │ ✓   │      │     │         │
│ Bandwidth Management    │ ✓  │       │       │       │ ✓   │      │     │         │
│ Network Topology        │ ✓  │       │       │       │ ✓   │ ✓    │     │         │
│ Tickets                 │ ✓  │ ✓     │       │       │ ✓   │ ✓    │     │         │
│ Customer Messages       │ ✓  │ ✓     │       │       │     │      │     │         │
│ Knowledge Base          │ ✓  │ ✓     │       │       │ ✓   │ ✓    │     │         │
│ SLA Management          │ ✓  │       │       │       │ ✓   │      │     │         │
│ Technicians             │ ✓  │       │       │       │ ✓   │      │     │         │
│ Work Orders             │ ✓  │       │       │       │ ✓   │ ✓    │     │         │
│ Scheduling              │ ✓  │       │       │       │ ✓   │ ✓    │     │         │
│ Installation Reports    │ ✓  │       │       │       │ ✓   │ ✓    │     │         │
│ GPS Tracking            │ ✓  │       │       │       │ ✓   │      │     │         │
│ Inventory               │ ✓  │       │       │ ✓     │     │      │     │         │
│ Assets                  │ ✓  │       │       │ ✓     │     │      │     │         │
│ Suppliers/Vendors       │ ✓  │       │       │ ✓     │     │      │     │         │
│ Purchase Orders         │ ✓  │       │ ✓     │ ✓     │     │      │     │         │
│ Employees               │ ✓  │       │       │       │     │      │ ✓   │         │
│ Attendance              │ ✓  │       │       │       │     │      │ ✓   │         │
│ Payroll                 │ ✓  │       │ ✓     │       │     │      │ ✓   │         │
│ Leave Management        │ ✓  │       │       │       │     │      │ ✓   │         │
│ Campaigns               │ ✓  │ ✓     │       │       │     │      │     │         │
│ Promotions/Vouchers     │ ✓  │ ✓     │ ✓     │       │     │      │     │         │
│ Referral Program        │ ✓  │ ✓     │       │       │     │      │     │         │
│ General Settings        │ ✓  │       │       │       │     │      │     │         │
│ Payment Gateways        │ ✓  │       │       │       │     │      │     │         │
│ User Management         │ ✓  │       │       │       │     │      │     │         │
│ Roles & Permissions     │ ✓  │       │       │       │     │      │     │         │
│ Audit Logs              │ ✓  │       │       │       │     │      │     │         │
│ Backup & Restore        │ ✓  │       │       │       │     │      │     │         │
│ API Management          │ ✓  │       │       │       │     │      │     │         │
│ Packages/Tariffs        │ ✓  │ ✓     │ ✓     │       │     │      │     │ ✓       │
└─────────────────────────┴────┴───────┴───────┴───────┴─────┴──────┴─────┴─────────┘
```

---

## 📊 STATISTICS

| Metric                   | Count    |
| ------------------------ | -------- |
| **Total Menus**          | 46       |
| **Phase 1 (P1)**         | 2 menus  |
| **Phase 2 (P2)**         | 11 menus |
| **Phase 3 (P3)**         | 12 menus |
| **Already Done**         | 21 menus |
| **Total Roles**          | 8        |
| **Estimated Total Days** | ~80 days |

---

## 🚀 NEXT STEPS

1. [ ] Review and approve this roadmap
2. [ ] Set up placeholder routes for all 46 menus
3. [ ] Create placeholder controllers
4. [ ] Update sidebar with collapsible menu groups
5. [ ] Begin Phase 1 development

---

_Document maintained by: Development Team_  
_Last Updated: 2026-01-18_
