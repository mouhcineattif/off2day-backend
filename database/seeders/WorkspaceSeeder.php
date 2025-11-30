<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Modules\Companies\Models\Company;
use App\Models\User;
use App\Modules\Employees\Models\EmployeeProfile;
use App\Modules\Leaves\Models\LeaveType;
use App\Modules\Workflows\Models\ApprovalWorkflow;
use App\Modules\Workflows\Models\ApprovalWorkflowStep;

class WorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        // Clean existing test workspace (optional)
        Company::where('name', 'Off2Day Test Workspace')->delete();
        User::where('email', 'mouhcine@example.com')->delete();

        // 1. Create company
        $company = Company::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Off2Day Test Workspace',
            'domain' => 'off2day.test',
            'timezone' => 'UTC',
            'locale' => 'en',
            'status' => 'active',
        ]);

        // 2. Create owner user
        $owner = User::create([
            'id' => Str::uuid()->toString(),
            'company_id' => $company->id,
            'full_name' => 'Mouhcine Attif',
            'email' => 'mouhcine@example.com',
            'password' => Hash::make('password'),
            'role' => 'company_admin',
            'is_active' => true,
        ]);
        
        // 3. Owner Employee Profile
        EmployeeProfile::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $owner->__get('id'),
            'company_id' => $company->id,
            'job_title' => 'Owner',
            'hire_date' => now()->toDateString(),
        ]);

        // 4. Default Leave Types
        LeaveType::insert([
            [
                'id' => Str::uuid()->toString(),
                'company_id' => $company->id,
                'name' => 'Annual Leave',
                'code' => 'ANNUAL',
                'payable' => true,
                'max_days_per_year' => 20,
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
                'payable' => true,
                'max_days_per_year' => 10,
                'carry_over_allowed' => false,
                'requires_document' => true,
                'requires_approval_level' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Default Approval Workflow
        $workflow = ApprovalWorkflow::create([
            'id' => Str::uuid()->toString(),
            'company_id' => $company->id,
            'name' => 'Default Workflow',
            'scope' => 'company',
            'is_default' => true,
        ]);

        ApprovalWorkflowStep::create([
            'id' => Str::uuid()->toString(),
            'workflow_id' => $workflow->id,
            'step_order' => 1,
            'selector_type' => 'role',       // approve by company_admin
            'selector_value' => 'company_admin',
            'allow_override' => false,
        ]);
    }
}
