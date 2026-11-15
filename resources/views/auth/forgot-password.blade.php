<x-guest-layout>
    <div style="margin-bottom: 20px; font-size: 14px; color: #6c757d; line-height: 1.5;">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status (Biasanya dari Breeze) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; font-weight: 600; margin-bottom: 5px; color: #1a1a2e;">Email</label>
            
            <!-- Input Email -->
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                   style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; box-sizing: border-box; outline: none; transition: border-color 0.3s;"
                   onfocus="this.style.borderColor='#4f46e5'" 
                   onblur="this.style.borderColor='#e5e7eb'">
            
            @error('email')
                <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tombol Submit -->
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <button type="submit" style="background-color: #4f46e5; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.3s;"
                    onmouseover="this.style.backgroundColor='#4338ca'" 
                    onmouseout="this.style.backgroundColor='#4f46e5'">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>