<?php
namespace App\Modules\Companies\Actions;

use App\Modules\Leaves\Models\LeaveType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Modules\Companies\Models\Company;
use App\Models\User;
use App\Modules\Employees\Models\EmployeeProfile;
use App\Modules\Companies\Repositories\CompanyRepositoryInterface;

class CreateWorkspaceAction
{
    public function __construct(protected CompanyRepositoryInterface $companies)
    {
    }

    /**
     * Create workspace + owner + defaults in a transaction
     *
     * @param array $companyData
     * @param array $ownerData
     * @param string|null $plan
     * @param string|null $ip
     * @return array
     */
    public function execute(array $companyData, array $ownerData, ?string $plan = null, ?string $ip = null): array
    {
        return DB::transaction(function () use ($companyData, $ownerData, $plan, $ip) {
            // 1. create company
            $company = Company::create([
                'id' => Str::uuid()->toString(),
                'name' => $companyData['name'],
                'domain' => $companyData['domain'] ?? null,
                'timezone' => $companyData['timezone'] ?? 'UTC',
                'locale' => $companyData['locale'] ?? 'en',
                'status' => 'active',
            ]);

            // 2. create owner user
            $user = User::create([
                'id' => Str::uuid()->toString(),
                'company_id' => $company->id,
                'email' => $ownerData['email'],
                'full_name' => trim(($ownerData['first_name'] ?? '') . ' ' . ($ownerData['last_name'] ?? '')),
                'password' => Hash::make($ownerData['password']),
                'role' => 'company_admin',
                'is_active' => true,
            ]);

            // 3. create employee profile for owner
            EmployeeProfile::create([
                'id' => Str::uuid()->toString(),
                'user_id' => $user->id,
                'company_id' => $company->id,
                'job_title' => $ownerData['job_title'] ?? 'Owner',
                'hire_date' => now()->toDateString(),
            ]);

            // 4. create default leave types & workflows (helper methods)
            $this->createDefaultLeaveTypes($company);
            $this->createDefaultApprovalWorkflow($company);

            // 5. create subscription/trial if required
            // $this->createTrialSubscription($company, $plan);

            // 6. optional: audit log
            // Audit::log($user, 'created_company', ['company_id' => $company->id]);

            // 7. auto-login for SPA (optional)
            // Auth::loginUsingId($user->id);

            // 8. return result
            return [
                'company' => $company,
                'owner' => $user,
                // optionally: 'token' => $user->createToken('workspace-owner')->plainTextToken
            ];
        }, 5);
    }

    protected function createDefaultLeaveTypes(Company $company)
    {
        // Create e.g. Annual Leave, Sick Leave
        LeaveType::insert([
            [
                'id' => Str::uuid()->toString(),
                'company_id' => $company->id,
                'name' => 'Annual Leave',
                'code' => 'ANNUAL',
                'max_days_per_year' => 20,
                'payable' => true,
                'carry_over_allowed' => true,
                'requires_document' => false,
                'requires_approval_level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'company_id' => $company->id,
                'name' => 'Sick Leave',
                'code' => 'SICK',
                'max_days_per_year' => 10,
                'payable' => true,
                'carry_over_allowed' => false,
                'requires_document' => true,
                'requires_approval_level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    protected function createDefaultApprovalWorkflow(Company $company)
    {
        // create a simple 1-step workflow: manager or company admin approves
        $workflow = \App\Modules\Workflows\Models\ApprovalWorkflow::create([
            'id' => Str::uuid()->toString(),
            'company_id' => $company->id,
            'name' => 'Default workflow',
            'scope' => 'company',
            'is_default' => true,
        ]);

        \App\Modules\Workflows\Models\ApprovalWorkflowStep::create([
            'id' => Str::uuid()->toString(),
            'workflow_id' => $workflow->id,
            'step_order' => 1,
            'selector_type' => 'role',
            'selector_value' => 'company_admin',
            'allow_override' => false,
        ]);
    }
}
