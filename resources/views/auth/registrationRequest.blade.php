<x-guest-layout>
    <x-status-message />
    <div class="wrapper-page">
        <h4 class="text-muted text-center font-size-18"><b>Register</b></h4>
        <div class="p-3">

            <form method="POST" action="{{ route('user.registrationRequestStore') }}">
                @csrf
                <!-- Full Name -->
                <div>
                    <x-text-input id="full_name" class="block mt-1 w-full form-control" type="text" name="full_name"
                        :value="old('full_name')" required autofocus autocomplete="full_name" placeholder="Full Name" />
                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                </div>

                 <!-- Email Address -->
                 <div class="mt-4">
                    <x-text-input id="email" class="block mt-1 w-full form-control" type="email" name="email"
                        :value="old('email')" required autocomplete="username" placeholder="Email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>


                <!-- Library Name Address -->
                <div class="mt-4">
                    <x-text-input id="library_name" class="block mt-1 w-full form-control" type="text"
                        name="library_name" :value="old('library_name')" required autofocus autocomplete="library_name"
                        placeholder="Library Name" />
                    <x-input-error :messages="$errors->get('library_name')" class="mt-2" />
                </div>

                <!-- Contact Number -->
                <div class="mt-4">
                    <div>
                        <x-text-input id="contact_number" class="block mt-1 w-full form-control" type="text"
                            name="contact_number" :value="old('contact_number')" required autofocus autocomplete="contact_number"
                            placeholder="Contact Number" />
                        <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                    </div>
                </div>

                <!-- Library Address -->
                <div class="mt-4">
                    <div>
                        {{-- <x-text-input id="library_address" class="block mt-1 w-full form-control" type="text"
                            name="library_address" :value="old('library_address')" required autofocus autocomplete="library_address"
                            placeholder="Library Address" /> --}}
                        <x-form.textarea name="library_address" label="Library Address" placeholder="Library Address" />


                        <x-input-error :messages="$errors->get('library_address')" class="mt-2" />
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-lg-10">
                        <label for="">Library Logo</label>
                        <input type="file" accept="image/*" name="logo" id="logo"
                            onchange="loadFile(event)" class="form-control">
                    </div>

                    <div class="col-lg-2">
                        <img id="output" src="" alt=""
                            style="max-width: 50%; max-height: 100px;">
                    </div>
                </div> --}}

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button class="btn btn-info w-100 waves-effect waves-light" type="submit">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

@push('script')
    <script>
        alert("hiii")
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src)
            }
        };
    </script>
@endpush
