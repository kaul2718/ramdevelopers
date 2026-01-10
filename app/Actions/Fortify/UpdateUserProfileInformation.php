<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'idiomas' => ['nullable', 'string', 'max:255'],
            'usr_id_ctry' => ['nullable', 'integer', 'exists:countries,ctry_id'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
                'phone' => $input['phone'] ?? null,
                'idiomas' => $input['idiomas'] ?? null,
                'usr_id_ctry' => $input['usr_id_ctry'] ?? null,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'idiomas' => $input['idiomas'] ?? null,
            'usr_id_ctry' => $input['usr_id_ctry'] ?? null,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
