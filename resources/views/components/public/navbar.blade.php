<nav class="bg-white/95 backdrop-blur-md text-slate-900 shadow-lg w-full z-50 fixed top-0 left-0 right-0 border-b-2 border-blue-100">
    <div class="max-w-[1920px] mx-auto px-3 sm:px-4 md:px-8">
        <div class="flex justify-between items-center h-16 sm:h-20 md:h-24">

            <!-- Logo & Branding -->
            <div class="flex items-center gap-1.5 sm:gap-2 md:gap-3 cursor-pointer hover:opacity-90 transition-opacity">
                <div class="flex space-x-1 sm:space-x-1.5 md:space-x-2 flex-shrink-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Mandaluyong_seal.svg/1024px-Mandaluyong_seal.svg.png"
                        alt="Mandaluyong Seal" class="h-10 w-10 sm:h-12 sm:w-12 md:h-14 md:w-14 lg:h-16 lg:w-16 object-contain drop-shadow-md">
                    <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3"
                        alt="Barangay Seal" class="h-10 w-10 sm:h-12 sm:w-12 md:h-14 md:w-14 lg:h-16 lg:w-16 object-contain drop-shadow-md rounded-full">
                </div>
                <div class="hidden sm:flex flex-col">
                    <h1 class="font-barlow font-bold text-xs sm:text-sm md:text-base lg:text-lg leading-tight tracking-wide text-[#0052CC]">Barangay Daang Bakal</h1>
                    <span class="font-barlow text-[10px] sm:text-xs md:text-sm lg:text-base font-semibold text-slate-600">City of Mandaluyong</span>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="#hero" class="font-semibold text-sm text-slate-600 hover:text-[#0052CC] transition px-4 py-2 rounded-lg hover:bg-blue-50">
                    Home
                </a>
                <a href="#services" class="font-semibold text-sm text-slate-600 hover:text-[#0052CC] transition px-4 py-2 rounded-lg hover:bg-blue-50">
                    Services
                </a>
                <a href="#about" class="font-semibold text-sm text-slate-600 hover:text-[#0052CC] transition px-4 py-2 rounded-lg hover:bg-blue-50">
                    About
                </a>
                <a href="{{ url('/login') }}"
                    class="font-semibold text-sm text-slate-600 hover:text-[#0052CC] transition px-4 py-2 rounded-lg hover:bg-blue-50">
                    Login
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="text-slate-900 focus:outline-none p-2 hover:bg-blue-50 rounded-lg transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false"
                    class="absolute top-20 right-0 left-0 bg-white/95 backdrop-blur-md text-slate-900 p-6 shadow-xl z-50 flex flex-col gap-2 border-t-2 border-blue-100">
                    <a href="#hero" class="font-bold text-base py-3 px-4 hover:bg-blue-50 rounded-lg transition text-slate-600">Home</a>
                    <a href="#services" class="font-bold text-base py-3 px-4 hover:bg-blue-50 rounded-lg transition text-slate-600">Services</a>
                    <a href="#about" class="font-bold text-base py-3 px-4 hover:bg-blue-50 rounded-lg transition text-slate-600">About</a>
                    <div class="pt-2 border-t border-slate-200 mt-2 flex justify-center">
                        <a href="{{ url('/login') }}" class="font-bold text-base py-3 px-4 bg-white text-slate-600 rounded-lg transition text-center hover:shadow-lg border border-gray-300">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

