    <?php
    use Illuminate\Support\Facades\DB;
    $studentEmail = DB::table('password_reset_tokens')
        ->where('token', $token)
        ->first();
    ?>
    <x-guest-layout>
        <h4 class="text-muted text-center font-size-18"><b>Reset Password</b></h4>
        <form method="POST" class="form-horizontal mt-3" action="{{ route('student.resetPasswordStore') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Address -->
            <div>
                {{-- <x-input-label for="email" :value="__('Email')" /> --}}
                <x-text-input id="email" class="form-control block mt-1 w-full" type="hidden" name="email"
                    :value="old('email', !empty($studentEmail->email) ? $studentEmail->email : 'Invalid token!')" required autofocus autocomplete="username" placeholder="Email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                {{-- <x-input-label for="password" :value="__('Password')" /> --}}
                <x-text-input id="password" class="form-control block mt-1 w-full" type="password" name="password"
                    required autocomplete="new-password" placeholder="New Password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                {{-- <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> --}}

                <x-text-input id="password_confirmation" class="form-control block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password"
                    placeholder="Password Confirmation" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="btn btn-info w-100 waves-effect waves-light">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
