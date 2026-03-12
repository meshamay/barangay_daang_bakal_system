<nav class="bg-white/95 backdrop-blur-md text-slate-900 shadow-lg w-full z-50 fixed top-0 left-0 right-0 border-b-2 border-blue-100">
    <div class="max-w-[1920px] mx-auto px-3 sm:px-4 md:px-8">
        <div class="flex justify-between items-center h-16 sm:h-20 md:h-24">

            <!-- Logo & Branding -->
            <div class="flex items-center gap-1.5 sm:gap-2 md:gap-3 cursor-pointer hover:opacity-90 transition-opacity">
                <div class="flex space-x-1 sm:space-x-1.5 md:space-x-2 flex-shrink-0">
                    <img src="{{ asset('images/Ph_seal_Mandaluyong.png') }}"
                        alt="Mandaluyong Seal" class="h-10 w-10 sm:h-12 sm:w-12 md:h-14 md:w-14 lg:h-16 lg:w-16 object-contain drop-shadow-md">
                    <img src="{{ asset('images/Brgy.jpeg') }}"
                        alt="Barangay Seal" class="h-10 w-10 sm:h-12 sm:w-12 md:h-14 md:w-14 lg:h-16 lg:w-16 object-contain drop-shadow-md rounded-full">
                </div>
                <div class="flex flex-col">
                    <h1 class="font-barlow font-bold text-[14px] leading-tight tracking-wide text-[#0052CC]">Barangay Daang Bakal</h1>
                    <span class="font-barlow text-[14px] font-semibold text-slate-600">City of Mandaluyong</span>
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
                <button @click="open = !open" class="text-slate-900 focus:outline-none p-2 hover:bg-blue-100 rounded-lg transition">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="4" y="6" width="16" height="2" rx="1" fill="#0052CC" />
                        <rect x="4" y="12" width="16" height="2" rx="1" fill="#0052CC" />
                        <rect x="4" y="18" width="16" height="2" rx="1" fill="#0052CC" />
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="absolute top-20 right-0 left-0 flex justify-center z-50">
                    <div class="w-[90vw] max-w-xs bg-white rounded-2xl shadow-xl border border-blue-100 p-4 flex flex-col gap-3">
                        <!-- <div class="border-b-4 border-[#4285F4] mb-2"></div> -->
                        <a href="#hero" class="font-bold text-[14px] py-3 px-4 bg-blue-50 rounded-xl shadow-sm transition text-slate-900 hover:bg-blue-100 hover:text-[#0052CC]">
                            Home
                        </a>
                        <a href="#services" class="font-bold text-[14px] py-3 px-4 bg-blue-50 rounded-xl shadow-sm transition text-slate-900 hover:bg-blue-100 hover:text-[#0052CC]">
                            Services
                        </a>
                        <a href="#about" class="font-bold text-[14px] py-3 px-4 bg-blue-50 rounded-xl shadow-sm transition text-slate-900 hover:bg-blue-100 hover:text-[#0052CC]">
                            About
                        </a>
                        <div class="flex justify-center mt-2">
                            <a href="{{ url('/login') }}" class="w-full font-bold text-[14px] py-3 px-4 bg-[#4285F4] text-white rounded-xl shadow-lg transition text-center hover:bg-[#0052CC]">
                                Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

