<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string|null $description
 * @property array<array-key, mixed>|null $permissions
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property int $usage_count
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $masked_key
 * @property-read string $status
 * @property-read string $status_color
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereUsageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiKey whereUserId($value)
 */
	class ApiKey extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $serial_number
 * @property int|null $vendor_id
 * @property string $category
 * @property string $condition
 * @property string $status
 * @property numeric|null $purchase_price
 * @property \Illuminate\Support\Carbon|null $purchase_date
 * @property \Illuminate\Support\Carbon|null $warranty_until
 * @property string|null $location
 * @property string|null $assigned_to
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $category_color
 * @property-read string $category_label
 * @property-read string $condition_color
 * @property-read string $status_color
 * @property-read \App\Models\Vendor|null $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereWarrantyUntil($value)
 */
	class Asset extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $clock_in
 * @property \Illuminate\Support\Carbon|null $clock_out
 * @property string $status
 * @property string|null $clock_in_location
 * @property string|null $clock_out_location
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read string|null $working_hours
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereClockIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereClockInLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereClockOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereClockOutLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUserId($value)
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $user_name
 * @property string $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string $description
 * @property array<array-key, mixed>|null $old_values
 * @property array<array-key, mixed>|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $url
 * @property string|null $method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $action_color
 * @property-read string $action_icon
 * @property-read string $model_name
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereNewValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereOldValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereUserName($value)
 */
	class AuditLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $download_speed
 * @property int $upload_speed
 * @property int|null $burst_download
 * @property int|null $burst_upload
 * @property int $priority
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $burst_label
 * @property-read string $speed_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereBurstDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereBurstUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereDownloadSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BandwidthPlan whereUploadSpeed($value)
 */
	class BandwidthPlan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property string $status
 * @property string $message_template
 * @property array<array-key, mixed>|null $target_audience
 * @property int $total_recipients
 * @property int $sent_count
 * @property int $delivered_count
 * @property int $failed_count
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * @property-read float $delivery_rate
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read string $type_icon
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDeliveredCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereFailedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereMessageTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereSentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereTargetAudience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereTotalRecipients($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereUpdatedAt($value)
 */
	class Campaign extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property string $contract_number
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string $status
 * @property string|null $terms
 * @property string|null $scanned_copy_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read string $status_color
 * @property-read string $status_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereContractNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereScannedCopyPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contract whereUpdatedAt($value)
 */
	class Contract extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $credit_number
 * @property int $customer_id
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon $issue_date
 * @property string $reason
 * @property string|null $notes
 * @property string $status
 * @property int|null $applied_to_invoice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Invoice|null $appliedInvoice
 * @property-read \App\Models\Customer $customer
 * @property-read string $formatted_amount
 * @property-read string $reason_label
 * @property-read string $status_color
 * @property-read string $status_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereAppliedToInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereCreditNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CreditNote whereUpdatedAt($value)
 */
	class CreditNote extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $customer_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $payment_token
 * @property string|null $email
 * @property string $address
 * @property string|null $rt_rw
 * @property string|null $kelurahan
 * @property string|null $kecamatan
 * @property string|null $kabupaten
 * @property string|null $provinsi
 * @property string|null $kode_pos
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property int $package_id
 * @property int|null $partner_id
 * @property int|null $assigned_to
 * @property int|null $team_size
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $installation_date
 * @property \Illuminate\Support\Carbon|null $billing_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $full_address
 * @property-read string $payment_label
 * @property-read string $payment_label_color
 * @property-read float|null $payment_score
 * @property-read array $payment_stats
 * @property-read string $qr_code
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \App\Models\Package $package
 * @property-read \App\Models\Partner|null $partner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerStatusLog> $statusLogs
 * @property-read int|null $status_logs_count
 * @property-read \App\Models\User|null $technician
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereInstallationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereKelurahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePartnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePaymentToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRtRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTeamSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property string $status
 * @property string|null $previous_status
 * @property int|null $changed_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $changed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $changedByUser
 * @property-read \App\Models\Customer $customer
 * @property-read string $previous_status_label
 * @property-read string $status_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog whereChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog whereChangedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog wherePreviousStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerStatusLog whereUpdatedAt($value)
 */
	class CustomerStatusLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $work_order_id
 * @property int $technician_id
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon $installation_date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string $status
 * @property string $work_performed
 * @property array<array-key, mixed>|null $equipment_used
 * @property string|null $issues_found
 * @property string|null $resolution
 * @property string|null $customer_signature
 * @property int|null $customer_rating
 * @property string|null $customer_feedback
 * @property array<array-key, mixed>|null $photos
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read string|null $duration
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \App\Models\User $technician
 * @property-read \App\Models\WorkOrder $workOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereCustomerFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereCustomerRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereCustomerSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereEquipmentUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereInstallationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereIssuesFound($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereTechnicianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereWorkOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InstallationReport whereWorkPerformed($value)
 */
	class InstallationReport extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $source_system
 * @property string $target_system
 * @property string $action ex: CREATE_USER, SUSPEND_USER
 * @property array<array-key, mixed>|null $payload Data yang dikirim
 * @property string|null $response Balasan error/success
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $executed_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog whereSourceSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IntegrationLog whereTargetSystem($value)
 */
	class IntegrationLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryCategory whereUpdatedAt($value)
 */
	class InventoryCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $category_id
 * @property string|null $sku
 * @property string $name
 * @property string|null $description
 * @property string $unit
 * @property numeric $min_stock_alert
 * @property numeric $purchase_price
 * @property numeric $selling_price
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InventoryCategory $category
 * @property-read mixed $total_stock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockMovement> $stockMovements
 * @property-read int|null $stock_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereMinStockAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem whereUpdatedAt($value)
 */
	class InventoryItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $invoice_number
 * @property int $customer_id
 * @property float $amount
 * @property Carbon $period_start
 * @property Carbon $period_end
 * @property Carbon $due_date
 * @property string $status
 * @property Carbon|null $payment_date
 * @property string|null $payment_method
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read int|null $days_late
 * @property-read string $formatted_amount
 * @property-read string $formatted_period
 * @property-read bool|null $is_on_time
 * @property-read string $payment_status_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice paid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice unpaid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice wherePeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice wherePeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $ip_pool_id
 * @property string $address
 * @property string $status
 * @property int|null $customer_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \App\Models\IpPool $pool
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress whereIpPoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpAddress whereUpdatedAt($value)
 */
	class IpAddress extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $network
 * @property int $prefix
 * @property string|null $gateway
 * @property string|null $dns_primary
 * @property string|null $dns_secondary
 * @property string $type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IpAddress> $addresses
 * @property-read int|null $addresses_count
 * @property-read int $available_ips
 * @property-read string $network_cidr
 * @property-read int $total_ips
 * @property-read string $type_color
 * @property-read float $usage_percent
 * @property-read int $used_ips
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereDnsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereDnsSecondary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereNetwork($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IpPool whereUpdatedAt($value)
 */
	class IpPool extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $category
 * @property array<array-key, mixed>|null $tags
 * @property int $author_id
 * @property int $views
 * @property int $helpful_count
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read string $category_color
 * @property-read string $category_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereHelpfulCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBaseArticle whereViews($value)
 */
	class KnowledgeBaseArticle extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $lead_number
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $rt_rw
 * @property string|null $kelurahan
 * @property string|null $kecamatan
 * @property string|null $kabupaten
 * @property string|null $provinsi
 * @property string|null $kode_pos
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string $source
 * @property int|null $interested_package_id
 * @property int|null $assigned_to
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $converted_at
 * @property int|null $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $assignedTo
 * @property-read \App\Models\Customer|null $customer
 * @property-read string $full_address
 * @property-read string $source_label
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \App\Models\Package|null $interestedPackage
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead new()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereConvertedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereInterestedPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereKelurahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereLeadNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereRtRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lead whereUpdatedAt($value)
 */
	class Lead extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int $days
 * @property string $reason
 * @property string $status
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $rejection_reason
 * @property string|null $attachment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read string $type_color
 * @property-read string $type_label
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Leave whereUserId($value)
 */
	class Leave extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $address
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereUpdatedAt($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $user_id
 * @property string $direction
 * @property string $channel
 * @property string $content
 * @property string $status
 * @property string|null $external_id
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read string $channel_icon
 * @property-read string $status_color
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int $total_ports
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $coordinates
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereTotalPorts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Odp whereUpdatedAt($value)
 */
	class Odp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $ip_address
 * @property string|null $brand
 * @property string $type
 * @property int $total_pon_ports
 * @property string|null $location
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereTotalPonPorts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olt whereUpdatedAt($value)
 */
	class Olt extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $speed_down
 * @property int $speed_up
 * @property numeric $price
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $formatted_price
 * @property-read string $formatted_speed
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereSpeedDown($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereSpeedUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereUpdatedAt($value)
 */
	class Package extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $email
 * @property string $phone
 * @property string|null $address
 * @property numeric|null $balance Saldo Deposit Reseller
 * @property numeric|null $commission_rate Persentase Komisi (ex: 10%)
 * @property string $status
 * @property string|null $notes
 * @property string|null $erp_supplier_id Link ke Vendor di ERPNext (utk pembayaran komisi)
 * @property string $password_hash Untuk login Portal Reseller
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @property-read string $status_color
 * @property-read string $status_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereErpSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner wherePasswordHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partner whereUpdatedAt($value)
 */
	class Partner extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $payment_number
 * @property int $invoice_id
 * @property int $customer_id
 * @property numeric $amount
 * @property string $payment_method
 * @property string|null $reference_number
 * @property \Illuminate\Support\Carbon $paid_at
 * @property int|null $processed_by
 * @property string $status
 * @property string|null $notes
 * @property string|null $proof_file
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read string $payment_method_label
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \App\Models\Invoice $invoice
 * @property-read \App\Models\User|null $processedBy
 * @property-read \App\Models\User|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment confirmed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment thisMonth()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProcessedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProofFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereVerifiedBy($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $period
 * @property numeric $basic_salary
 * @property numeric $allowances
 * @property numeric $overtime
 * @property numeric $bonus
 * @property numeric $deductions
 * @property numeric $tax
 * @property numeric $net_salary
 * @property int $working_days
 * @property int $present_days
 * @property int $absent_days
 * @property int $late_days
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $gross_pay
 * @property-read string $period_label
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read float $total_deductions
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereAbsentDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereAllowances($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereBasicSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereLateDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereNetSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereOvertime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll wherePresentDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payroll whereWorkingDays($value)
 */
	class Payroll extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $proforma_number
 * @property int $customer_id
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon $issue_date
 * @property \Illuminate\Support\Carbon $valid_until
 * @property string|null $notes
 * @property string $status
 * @property int|null $converted_invoice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Invoice|null $convertedInvoice
 * @property-read \App\Models\Customer $customer
 * @property-read string $formatted_amount
 * @property-read string $status_color
 * @property-read string $status_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereConvertedInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereProformaNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProformaInvoice whereValidUntil($value)
 */
	class ProformaInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property string $type
 * @property numeric $value
 * @property numeric|null $min_purchase
 * @property numeric|null $max_discount
 * @property int|null $usage_limit
 * @property int $usage_count
 * @property int|null $per_customer_limit
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property array<array-key, mixed>|null $applicable_packages
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $discount_label
 * @property-read string $status
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read string $type_color
 * @property-read string $type_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereApplicablePackages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereMaxDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereMinPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion wherePerCustomerLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereUsageCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereValue($value)
 */
	class Promotion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $po_number
 * @property int $vendor_id
 * @property \Illuminate\Support\Carbon $order_date
 * @property \Illuminate\Support\Carbon|null $expected_date
 * @property \Illuminate\Support\Carbon|null $received_date
 * @property string $status
 * @property numeric $subtotal
 * @property numeric $tax
 * @property numeric $discount
 * @property numeric $total
 * @property string|null $notes
 * @property int|null $created_by
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\User|null $creator
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseOrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Vendor $vendor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereExpectedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder wherePoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereReceivedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereVendorId($value)
 */
	class PurchaseOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $purchase_order_id
 * @property int|null $inventory_item_id
 * @property string $item_name
 * @property string|null $item_code
 * @property string|null $description
 * @property int $quantity
 * @property int $received_quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $is_fully_received
 * @property-read int $remaining_quantity
 * @property-read \App\Models\InventoryItem|null $inventoryItem
 * @property-read \App\Models\PurchaseOrder $purchaseOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereItemCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereItemName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem wherePurchaseOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereReceivedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrderItem whereUpdatedAt($value)
 */
	class PurchaseOrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property int $referrer_id
 * @property int|null $referred_id
 * @property string $status
 * @property numeric $reward_amount
 * @property bool $reward_paid
 * @property \Illuminate\Support\Carbon|null $qualified_at
 * @property \Illuminate\Support\Carbon|null $rewarded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \App\Models\Customer|null $referred
 * @property-read \App\Models\Customer $referrer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereQualifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereReferredId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereReferrerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereRewardAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereRewardPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereRewardedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereUpdatedAt($value)
 */
	class Referral extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $ip_address
 * @property int $port
 * @property string $username
 * @property string|null $password
 * @property string $type
 * @property string $status
 * @property string|null $identity
 * @property string|null $version
 * @property string|null $model
 * @property \Illuminate\Support\Carbon|null $last_sync_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read string $type_icon
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereLastSyncAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Router whereVersion($value)
 */
	class Router extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string $group
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $priority
 * @property int $first_response_hours
 * @property int $resolution_hours
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $priority_color
 * @property-read string $priority_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy whereFirstResponseHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy whereResolutionHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlaPolicy whereUpdatedAt($value)
 */
	class SlaPolicy extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $inventory_item_id
 * @property int $location_id
 * @property numeric $quantity
 * @property string|null $aisle
 * @property string|null $bin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InventoryItem $item
 * @property-read \App\Models\Location $location
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereAisle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereBin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereUpdatedAt($value)
 */
	class Stock extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $inventory_item_id
 * @property int $location_id
 * @property int $user_id
 * @property string $type
 * @property numeric $quantity
 * @property numeric $previous_quantity
 * @property numeric $new_quantity
 * @property string|null $reference_number
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InventoryItem $item
 * @property-read \App\Models\Location $location
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereNewQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement wherePreviousQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereUserId($value)
 */
	class StockMovement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $company_name
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 */
	class Supplier extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $erp_customer_id ID Customer di ERPNext (ex: CUST-2024-001)
 * @property string $radius_username Username PPPoE didaloRADIUS
 * @property string|null $inventory_device_sn Serial Number Modem di InvenTree/Rumah
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $last_synced_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping whereErpCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping whereInventoryDeviceSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping whereLastSyncedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping whereRadiusUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SyncMapping whereUpdatedAt($value)
 */
	class SyncMapping extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ticket_number
 * @property int $customer_id
 * @property int|null $technician_id
 * @property string $subject
 * @property string $description
 * @property string $status
 * @property string $priority
 * @property string|null $admin_notes
 * @property \Illuminate\Support\Carbon|null $resolved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\User|null $technician
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket open()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTechnicianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTicketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $photo
 * @property bool $is_active
 * @property float|null $last_latitude
 * @property float|null $last_longitude
 * @property \Illuminate\Support\Carbon|null $last_location_update
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $assignedCustomers
 * @property-read int|null $assigned_customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $completedCustomers
 * @property-read int|null $completed_customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $currentTasks
 * @property-read int|null $current_tasks_count
 * @property-read string $computed_status
 * @property-read \App\Models\Customer|null $current_customer
 * @property-read string $photo_url
 * @property-read string $status_color
 * @property-read string $status_label
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User technicians()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLocationUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string $type
 * @property string $status
 * @property string|null $bank_name
 * @property string|null $bank_account
 * @property string|null $npwp
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $type_color
 * @property-read string $type_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereUpdatedAt($value)
 */
	class Vendor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ticket_number
 * @property int|null $customer_id
 * @property string $type
 * @property string $status
 * @property string $priority
 * @property \Illuminate\Support\Carbon|null $scheduled_date
 * @property \Illuminate\Support\Carbon|null $completed_date
 * @property int|null $technician_id
 * @property string|null $description
 * @property string|null $technician_notes
 * @property array<array-key, mixed>|null $photos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $odp_id
 * @property-read \App\Models\Customer|null $customer
 * @property-read mixed $status_color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkOrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Odp|null $odp
 * @property-read \App\Models\User|null $technician
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereCompletedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereOdpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereScheduledDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereTechnicianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereTechnicianNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereTicketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrder whereUpdatedAt($value)
 */
	class WorkOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $work_order_id
 * @property int $inventory_item_id
 * @property numeric $quantity
 * @property string $unit
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InventoryItem $inventoryItem
 * @property-read \App\Models\WorkOrder $workOrder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkOrderItem whereWorkOrderId($value)
 */
	class WorkOrderItem extends \Eloquent {}
}

