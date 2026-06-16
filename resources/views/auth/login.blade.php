<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - NusaMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nusa: {
                            light: '#E0F2F1',
                            DEFAULT: '#008B81',
                            dark: '#00736B',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Animasi sederhana untuk elemen melayang di background */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen relative overflow-hidden font-sans">

    <div class="absolute top-0 w-full py-6 px-10 flex justify-between items-center z-20">
        <h1 class="text-2xl font-bold text-nusa tracking-wider">NusaMart</h1>
        <nav class="hidden md:flex space-x-6 text-sm font-semibold text-gray-600 uppercase tracking-widest">
            <a href="{{ route('home') }}" class="hover:text-nusa transition">Home</a>
            <a href="#" class="hover:text-nusa transition">Services</a>
            <a href="#" class="hover:text-nusa transition">About Us</a>
        </nav>
    </div>

    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl h-full flex justify-center items-center opacity-60 pointer-events-none z-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-2xl animate-blob"></div>
        <div class="absolute top-20 right-10 w-72 h-72 bg-nusa-light rounded-full mix-blend-multiply filter blur-2xl animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-emerald-200 rounded-full mix-blend-multiply filter blur-2xl animate-blob animation-delay-4000"></div>
        
        <img src="https://via.placeholder.com/400x400.png?text=Ilustrasi+NusaMart" alt="Ilustrasi" class="absolute -right-20 bottom-10 w-96 h-96 object-contain drop-shadow-2xl z-10 hidden lg:block">
    </div>

    <div class="relative z-10 w-full max-w-md bg-white/90 backdrop-blur-md p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,139,129,0.15)] border border-white/50 text-center mx-4">
        
        <h2 class="text-3xl font-extrabold text-nusa mb-8 tracking-wide">WELCOME!</h2>

        @if($errors->any())
            <div class="bg-red-50 text-red-500 py-2 px-4 rounded-full text-sm mb-6 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <input type="text" name="emailOrUsername" value="{{ old('emailOrUsername') }}" placeholder="USERNAME OR EMAIL" required 
                    class="w-full bg-transparent border-2 border-nusa/30 rounded-full py-3 px-6 text-center text-sm font-semibold text-gray-700 placeholder-gray-400 focus:border-nusa focus:bg-white focus:outline-none focus:ring-0 transition-all">
            </div>
            
            <div>
                <input type="password" name="password" placeholder="PASSWORD" required 
                    class="w-full bg-transparent border-2 border-nusa/30 rounded-full py-3 px-6 text-center text-sm font-semibold text-gray-700 placeholder-gray-400 focus:border-nusa focus:bg-white focus:outline-none focus:ring-0 transition-all">
            </div>

            <div class="text-center pt-2">
                <a href="#" class="text-xs font-medium text-gray-400 hover:text-nusa transition">Forgot Password?</a>
            </div>
            
            <button type="submit" class="w-2/3 mx-auto block bg-nusa hover:bg-nusa-dark text-white font-bold py-3 px-8 rounded-full shadow-[0_10px_20px_rgba(0,139,129,0.3)] hover:shadow-[0_10px_25px_rgba(0,139,129,0.5)] transform hover:-translate-y-0.5 transition-all uppercase tracking-wider text-sm mt-4">
                Login
            </button>
        </form>

        <div class="mt-8">
            <a href="{{ route('register') }}" class="text-xs font-bold text-nusa hover:text-nusa-dark uppercase tracking-wider transition border-b border-transparent hover:border-nusa">
                Create Account
            </a>
        </div>
    </div>

</body>
</html>