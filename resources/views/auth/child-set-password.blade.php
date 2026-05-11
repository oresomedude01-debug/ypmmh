@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                <h1 class="text-3xl font-bold text-white mb-2">Welcome to YPMMH!</h1>
                <p class="text-blue-100">Set Your Password</p>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                <p class="text-gray-600 text-center mb-6">
                    Alhamdulillah! Your email has been verified successfully. 
                    Please set your password to complete your registration.
                </p>

                <form action="{{ route('child.set-password.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Enter your password"
                            required
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Must be at least 8 characters with uppercase, lowercase, and numbers
                        </p>
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Confirm your password"
                            required
                        >
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200"
                    >
                        Set Password & Continue
                    </button>
                </form>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Password Requirements:</strong><br>
                        • At least 8 characters<br>
                        • At least one uppercase letter<br>
                        • At least one number<br>
                        • At least one special character
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-sm mt-6">
            Already have a password? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Log in here</a>
        </p>
    </div>
</div>
@endsection
