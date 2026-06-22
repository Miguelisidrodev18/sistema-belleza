<?php

namespace App\Policies;

use App\Models\CourseSection;
use App\Models\User;

class CourseSectionPolicy
{
    public function viewAny(User $user): bool  { return $user->isAdmin(); }
    public function view(User $user, CourseSection $section): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isDocente()) {
            return $section->teachers()->wherePivot('teacher_id', $user->id)->exists();
        }
        return false;
    }
    public function create(User $user): bool  { return $user->isAdmin(); }
    public function update(User $user, CourseSection $section): bool  { return $user->isAdmin(); }
    public function delete(User $user, CourseSection $section): bool  { return $user->isAdmin(); }
}
