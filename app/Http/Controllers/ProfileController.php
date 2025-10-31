<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\Skill;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit(Request $request): View
    {
    $user = Auth::user();
    $originalEmail = $user->email;

        return view('profile.edit', [
            'user' => $user,
            'workExperiences' => $user->workExperiences()->get(),
            'educations' => $user->educations()->get(),
            'skills' => $user->skills()->get(),
            'certifications' => $user->certifications()->get(),
        ]);
    }

    /**
     * Update the user's profile and CV information.
     */
    public function update(Request $request): RedirectResponse
    {
    $user = Auth::user();
    $originalEmail = $user->email;

        // Validate all CV data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'professional_summary' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],

            // Work Experience
            'work_experiences' => ['nullable', 'array'],
            'work_experiences.*.id' => ['nullable', 'integer'],
            'work_experiences.*.job_title' => ['required_with:work_experiences', 'string', 'max:255'],
            'work_experiences.*.company_name' => ['required_with:work_experiences', 'string', 'max:255'],
            'work_experiences.*.description' => ['nullable', 'string', 'max:2000'],
            'work_experiences.*.start_date' => ['required_with:work_experiences', 'date'],
            'work_experiences.*.end_date' => ['nullable', 'date', 'after_or_equal:work_experiences.*.start_date'],
            'work_experiences.*.currently_working' => ['boolean'],
            'work_experiences.*.order' => ['integer'],

            // Education
            'educations' => ['nullable', 'array'],
            'educations.*.id' => ['nullable', 'integer'],
            'educations.*.school_name' => ['required_with:educations', 'string', 'max:255'],
            'educations.*.degree' => ['required_with:educations', 'string', 'max:255'],
            'educations.*.field_of_study' => ['required_with:educations', 'string', 'max:255'],
            'educations.*.description' => ['nullable', 'string', 'max:1000'],
            'educations.*.start_date' => ['required_with:educations', 'date'],
            'educations.*.end_date' => ['nullable', 'date'],
            'educations.*.order' => ['integer'],

            // Skills
            'skills' => ['nullable', 'array'],
            'skills.*.id' => ['nullable', 'integer'],
            'skills.*.skill_name' => ['required_with:skills', 'string', 'max:255'],
            'skills.*.proficiency' => ['required_with:skills', 'in:beginner,intermediate,advanced,expert'],
            'skills.*.order' => ['integer'],

            // Certifications
            'certifications' => ['nullable', 'array'],
            'certifications.*.id' => ['nullable', 'integer'],
            'certifications.*.certification_name' => ['required_with:certifications', 'string', 'max:255'],
            'certifications.*.issuer' => ['required_with:certifications', 'string', 'max:255'],
            'certifications.*.description' => ['nullable', 'string', 'max:1000'],
            'certifications.*.issue_date' => ['required_with:certifications', 'date'],
            'certifications.*.expiry_date' => ['nullable', 'date'],
            'certifications.*.credential_url' => ['nullable', 'url', 'max:255'],
            'certifications.*.order' => ['integer'],
        ]);

        // Use transaction to ensure data integrity
        DB::transaction(function () use ($user, $validated, $originalEmail) {
            // Update user profile and reset verification when email changes
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'professional_summary' => $validated['professional_summary'] ?? null,
                'location' => $validated['location'] ?? null,
                'website' => $validated['website'] ?? null,
                'linkedin_url' => $validated['linkedin_url'] ?? null,
                'github_url' => $validated['github_url'] ?? null,
            ]);

            if ($validated['email'] !== $originalEmail) {
                $user->email_verified_at = null;
            }

            $user->save();

            // Update work experiences
            $this->syncWorkExperiences($user, $validated['work_experiences'] ?? []);

            // Update educations
            $this->syncEducations($user, $validated['educations'] ?? []);

            // Update skills
            $this->syncSkills($user, $validated['skills'] ?? []);

            // Update certifications
            $this->syncCertifications($user, $validated['certifications'] ?? []);
        });

        return redirect()
            ->route('profile.edit')
            ->with('status', 'CV updated successfully!');
    }

    /**
     * Sync work experiences for the user.
     */
    private function syncWorkExperiences(User $user, array $workExperiences): void
    {
        // Get existing IDs
        $existingIds = collect($workExperiences)
            ->filter(fn($item) => isset($item['id']) && $item['id'])
            ->pluck('id')
            ->toArray();

        // Delete removed items
        $user->workExperiences()
            ->whereNotIn('id', $existingIds)
            ->delete();

        // Create or update
        foreach ($workExperiences as $index => $item) {
            if (isset($item['id']) && $item['id']) {
                // Update existing
                WorkExperience::find($item['id'])->update([
                    'job_title' => $item['job_title'],
                    'company_name' => $item['company_name'],
                    'description' => $item['description'] ?? null,
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'] ?? null,
                    'currently_working' => $item['currently_working'] ?? false,
                    'order' => $index,
                ]);
            } else {
                // Create new
                $user->workExperiences()->create([
                    'job_title' => $item['job_title'],
                    'company_name' => $item['company_name'],
                    'description' => $item['description'] ?? null,
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'] ?? null,
                    'currently_working' => $item['currently_working'] ?? false,
                    'order' => $index,
                ]);
            }
        }
    }

    /**
     * Sync educations for the user.
     */
    private function syncEducations(User $user, array $educations): void
    {
        $existingIds = collect($educations)
            ->filter(fn($item) => isset($item['id']) && $item['id'])
            ->pluck('id')
            ->toArray();

        $user->educations()
            ->whereNotIn('id', $existingIds)
            ->delete();

        foreach ($educations as $index => $item) {
            if (isset($item['id']) && $item['id']) {
                Education::find($item['id'])->update([
                    'school_name' => $item['school_name'],
                    'degree' => $item['degree'],
                    'field_of_study' => $item['field_of_study'],
                    'description' => $item['description'] ?? null,
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'] ?? null,
                    'order' => $index,
                ]);
            } else {
                $user->educations()->create([
                    'school_name' => $item['school_name'],
                    'degree' => $item['degree'],
                    'field_of_study' => $item['field_of_study'],
                    'description' => $item['description'] ?? null,
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'] ?? null,
                    'order' => $index,
                ]);
            }
        }
    }

    /**
     * Sync skills for the user.
     */
    private function syncSkills(User $user, array $skills): void
    {
        $existingIds = collect($skills)
            ->filter(fn($item) => isset($item['id']) && $item['id'])
            ->pluck('id')
            ->toArray();

        $user->skills()
            ->whereNotIn('id', $existingIds)
            ->delete();

        foreach ($skills as $index => $item) {
            if (isset($item['id']) && $item['id']) {
                Skill::find($item['id'])->update([
                    'skill_name' => $item['skill_name'],
                    'proficiency' => $item['proficiency'],
                    'order' => $index,
                ]);
            } else {
                $user->skills()->create([
                    'skill_name' => $item['skill_name'],
                    'proficiency' => $item['proficiency'],
                    'order' => $index,
                ]);
            }
        }
    }

    /**
     * Sync certifications for the user.
     */
    private function syncCertifications(User $user, array $certifications): void
    {
        $existingIds = collect($certifications)
            ->filter(fn($item) => isset($item['id']) && $item['id'])
            ->pluck('id')
            ->toArray();

        $user->certifications()
            ->whereNotIn('id', $existingIds)
            ->delete();

        foreach ($certifications as $index => $item) {
            if (isset($item['id']) && $item['id']) {
                Certification::find($item['id'])->update([
                    'certification_name' => $item['certification_name'],
                    'issuer' => $item['issuer'],
                    'description' => $item['description'] ?? null,
                    'issue_date' => $item['issue_date'],
                    'expiry_date' => $item['expiry_date'] ?? null,
                    'credential_url' => $item['credential_url'] ?? null,
                    'order' => $index,
                ]);
            } else {
                $user->certifications()->create([
                    'certification_name' => $item['certification_name'],
                    'issuer' => $item['issuer'],
                    'description' => $item['description'] ?? null,
                    'issue_date' => $item['issue_date'],
                    'expiry_date' => $item['expiry_date'] ?? null,
                    'credential_url' => $item['credential_url'] ?? null,
                    'order' => $index,
                ]);
            }
        }
    }

    /**
     * Remove the authenticated user's account after password confirmation.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/');
    }

    /**
     * Generate and download CV as PDF.
     */
    public function generatePDF()
    {
        $user = Auth::user()->load([
            'workExperiences',
            'educations',
            'skills',
            'certifications'
        ]);

        $pdf = Pdf::loadView('cv.pdf', ['user' => $user])
            ->setPaper('a4')
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);

        return $pdf->download('CV_' . $user->name . '_' . now()->format('Y-m-d') . '.pdf');
    }
}
