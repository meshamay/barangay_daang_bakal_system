<div id="hero"
    class="relative w-full h-screen sm:h-screen md:h-[100vh] flex flex-col justify-center items-center overflow-hidden pt-16 sm:pt-20 md:pt-24"
    style="background-image: url('{{ asset('images/HOME_PAGE.jpg') }}'); background-size: cover; background-position: center 40%; background-repeat: no-repeat;">

    <!-- Dark Overlay for better text readability -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#0052CC]/80 via-[#1565C0]/80 to-[#0D47A1]/80"></div>

    <!-- Decorative Yellow Accent Bars -->
    <div class="absolute top-0 left-0 right-0 h-2 bg-[#FFD700] z-10"></div>
    <div class="absolute bottom-0 left-0 right-0 h-2 bg-[#FFD700] z-10"></div>
    
    <!-- Yellow accent elements -->
    <div class="hidden sm:block absolute top-20 right-10 w-40 h-40 bg-[#FFD700] rounded-lg opacity-10 blur-3xl"></div>
    <div class="hidden sm:block absolute bottom-20 left-10 w-48 h-48 bg-[#FFD700] rounded-lg opacity-10 blur-3xl"></div>

    <!-- Overlay for better text readability -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/20"></div>

    <div class="relative z-10 flex flex-col items-center justify-center w-full max-w-[90rem] mx-auto px-4 text-center mt-0 sm:mt-28">
        <!-- Main Heading with Color Accent -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-black text-white mb-3 sm:mb-6 font-barlow tracking-tight leading-tight">
            Welcome to <span class="text-[#FFD700]">Barangay Daang Bakal</span>
        </h1>
        
        <!-- Subtitle -->
        <p class="text-base sm:text-lg md:text-xl text-white/95 mb-8 max-w-3xl font-light leading-relaxed">
            Your trusted source for <span class="text-[#FFD700] font-semibold">barangay services</span> and <span class="text-[#FFD700] font-semibold">community updates</span>
        </p>

        <!-- CTA Button with Modern Design -->
        <a href="{{ route('register') }}" class="group inline-flex items-center justify-center gap-3 
            bg-[#FFD700] text-white 
            px-8 py-4 rounded-full 
            text-base font-bold tracking-wide 
            transition-all duration-300 
            hover:bg-[#FFBF00] hover:scale-105 
            shadow-lg hover:shadow-2xl
            border-4 border-[#FFF9C4]">
            <span>Get Started</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
            </svg>
        </a>


    </div>
</div>
