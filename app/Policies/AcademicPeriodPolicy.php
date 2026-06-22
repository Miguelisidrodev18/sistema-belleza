<?php

namespace App\Policies;

use App\Models\AcademicPeriod;
use App\Models\User;

class AcademicPeriodPolicy
{
    public function viewAny(User $user): bool  { return $user->isAdmin(); }
    public function view(User $user, AcademicPeriod $period): bool  { return $user->isAdmin(); }
    public function create(User $user): bool  { return $user->isAdmin(); }
    public function update(User $user, AcademicPeriod $period): bool  { return $user->isAdmin(); }
    public function delete(User $user, AcademicPeriod $period): bool  { return $user->isAdmin(); }
}
