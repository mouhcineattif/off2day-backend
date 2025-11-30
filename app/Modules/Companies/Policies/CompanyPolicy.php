<?php

namespace App\Modules\Companies\Policies;

use App\Models\User;
use App\Modules\Companies\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Super admins bypass checks.
     */
    public function before(?User $user, $ability)
    {
        if (! $user) {
            return null; // not authenticated — continue to other checks
        }

        if ($user->role === 'super_admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any companies.
     * Usually only super admins should list all companies.
     */
    public function viewAny(?User $user): bool
    {
        // allow only super admins to list all companies
        return $user && $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can view the company.
     * company_admin and employees of the company can view it.
     */
    public function view(?User $user, Company $company): bool
    {
        if (! $user) {
            return false;
        }

        // company admins and employees of the same company can view
        return $user->company_id === $company->id
            || $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can create companies.
     * By default only super_admin can create companies — change if you want
     * to allow self-registration flows.
     */
    public function create(?User $user): bool
    {
        return $user && $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can update the company.
     * Company admins for the same company can update.
     */
    public function update(?User $user, Company $company): bool
    {
        if (! $user) {
            return false;
        }

        return $user->company_id === $company->id && $user->role === 'company_admin';
    }

    /**
     * Determine whether the user can delete the company.
     * Dangerous operation — require company_admin of same company or super_admin.
     */
    public function delete(?User $user, Company $company): bool
    {
        if (! $user) {
            return false;
        }

        // company admin of same company can delete (you might restrict this)
        return $user->company_id === $company->id && $user->role === 'company_admin';
    }

    /**
     * Determine whether the user can restore the company.
     */
    public function restore(?User $user, Company $company): bool
    {
        return $this->delete($user, $company);
    }

    /**
     * Determine whether the user can permanently delete the company.
     * Usually reserved for super_admin only, but we keep same logic as delete.
     */
    public function forceDelete(?User $user, Company $company): bool
    {
        return $user && $user->role === 'super_admin';
    }
}
